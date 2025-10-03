<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\Contracts\WalletServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontController extends Controller
{
    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    /**
     * Ana sayfayı gösterir
     *
     * @return View
     */
    public function index(): View
    {
        $user = auth()->user();
        $wallet = $this->walletService->getUserWallet($user->id);

        // Son 20 transaction'ı getir (scroll için daha fazla veri)
        $recentTransactions = $user->transactions()
            ->with('merchant')
            ->latest()
            ->take(20)
            ->get();

        return view('front.anaSayfa.index', compact('wallet', 'recentTransactions'));
    }

    /**
     * Para gönder sayfasını gösterir
     *
     * @return View
     */
    public function paraGonder(): View
    {
        $user = auth()->user();
        $wallet = $this->walletService->getUserWallet($user->id);
        $friends = $user->friends;

        return view('front.paraGonder.index', compact('wallet', 'friends'));
    }

    /**
     * QR kod ile ödeme sayfasını gösterir
     *
     * @param Request $request
     * @return View
     */
    public function odemeYap(Request $request): View
    {
        $user = auth()->user();
        $wallet = $this->walletService->getUserWallet($user->id);

        // QR kod verisini decode et
        $qrString = $request->query('qr');
        $qrData = null;

        if ($qrString) {
            try {
                $decodedString = base64_decode(urldecode($qrString));
                $qrData = json_decode($decodedString, true);
            } catch (\Exception $e) {
                // Geçersiz QR kod
                return redirect()->route('home')->with('error', 'Geçersiz QR kod');
            }
        }

        // Merchant bilgisini getir
        $merchant = null;
        if ($qrData && isset($qrData['merchant_id'])) {
            $merchantId = $qrData['merchant_id'];

            // M-15 formatındaki merchant_id'yi parse et veya direkt sayı kabul et
            if (is_string($merchantId) && preg_match('/^M-(\d+)$/', $merchantId, $matches)) {
                $merchantId = (int) $matches[1];
            } elseif (is_numeric($merchantId)) {
                $merchantId = (int) $merchantId;
            } else {
                return redirect()->route('home')->with('error', 'Geçersiz merchant ID formatı');
            }

            // Merchant'ı veritabanından bul
            $merchant = \App\Models\Merchant::with('category')->find($merchantId);

            // Merchant bulunamazsa hata ver
            if (!$merchant) {
                return redirect()->route('home')->with('error', "Merchant bulunamadı (ID: {$merchantId})");
            }
        } else {
            return redirect()->route('home')->with('error', 'QR kod verisi eksik veya hatalı');
        }

        return view('front.odemeYap.index', compact('wallet', 'qrData', 'merchant'));
    }

    /**
     * Arkadaşlarla bölüşme sayfasını gösterir
     *
     * @param Request $request
     * @return View
     */
    public function arkadasBol(Request $request): View
    {
        $user = auth()->user();
        $wallet = $this->walletService->getUserWallet($user->id);
        $friends = $user->friends;

        // URL parametrelerinden transaction bilgilerini al
        $transactionData = [
            'transaction_id' => $request->query('transaction_id'),
            'merchant_name' => $request->query('merchant_name'),
            'amount' => $request->query('amount'),
            'date' => $request->query('date'),
        ];

        return view('front.arkadaslaraBol.index', compact('wallet', 'friends', 'transactionData'));
    }

    /**
     * Para yükleme işlemi
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function loadMoney(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'amount' => 'required|integer|min:1|max:10000',
            ]);

            $user = $request->user();
            $wallet = $this->walletService->loadMoney($user->id, $validated['amount']);

            return response()->json([
                'success' => true,
                'message' => 'Para başarıyla yüklendi!',
                'balance' => (float) $wallet->balance,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Geçersiz miktar. Lütfen 1-10000 arasında bir değer girin.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Para yükleme işlemi sırasında bir hata oluştu.',
            ], 500);
        }
    }

    /**
     * Para gönderme işlemi
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function sendMoney(Request $request): JsonResponse
    {
        try {
            \Log::info('Send money request received', [
                'user_id' => $request->user()->id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'friend_ids' => 'required|array|min:1',
                'friend_ids.*' => 'required|integer',
                'amount' => 'required|numeric|min:0.01|max:10000',
            ]);

            // Arkadaşlık kontrolü
            $user = $request->user();
            $validFriendIds = $user->friends()->pluck('users.id')->toArray();
            $invalidFriendIds = array_diff($validated['friend_ids'], $validFriendIds);

            if (!empty($invalidFriendIds)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Seçilen kullanıcılardan bazıları arkadaşınız değil.',
                ], 400);
            }

            $amount = $validated['amount'];
            $friendIds = $validated['friend_ids'];
            $totalAmount = $amount * count($friendIds);

            \Log::info('Validation passed', [
                'amount' => $amount,
                'friend_ids' => $friendIds,
                'total_amount' => $totalAmount
            ]);

            // Gönderenin bakiyesini kontrol et
            $senderWallet = $this->walletService->getUserWallet($user->id);
            if ($senderWallet->balance < $totalAmount) {
                \Log::warning('Insufficient balance', [
                    'user_id' => $user->id,
                    'current_balance' => $senderWallet->balance,
                    'required_amount' => $totalAmount
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Yetersiz bakiye. Mevcut bakiyeniz: ₺ ' . number_format($senderWallet->balance, 2, ',', '.'),
                ], 400);
            }

            // Para transferi işlemi
            $result = $this->walletService->sendMoney($user->id, $friendIds, $amount);

            \Log::info('Money transfer successful', [
                'user_id' => $user->id,
                'result' => $result
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Para başarıyla gönderildi!',
                'balance' => (float) $result['sender_balance'],
                'transferred_amount' => $totalAmount,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Send money error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Para gönderme işlemi sırasında bir hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * QR kod ile ödeme işlemi
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function processQrPayment(Request $request): JsonResponse
    {
        try {
            \Log::info('QR payment request received', [
                'user_id' => $request->user()->id,
                'request_data' => $request->all()
            ]);

            $validated = $request->validate([
                'qr_id' => 'required|string',
                'merchant_id' => 'required',
                'amount' => 'required|numeric|min:0.01|max:10000',
                'currency' => 'required|string|in:TRY',
            ]);

            $user = $request->user();
            $amount = $validated['amount'];
            $merchantIdInput = $validated['merchant_id'];

            // M-15 formatındaki merchant_id'yi parse et veya direkt sayı kabul et
            $merchantId = null;
            if (is_string($merchantIdInput) && preg_match('/^M-(\d+)$/', $merchantIdInput, $matches)) {
                $merchantId = (int) $matches[1];
            } elseif (is_numeric($merchantIdInput)) {
                $merchantId = (int) $merchantIdInput;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Geçersiz merchant ID formatı.',
                ], 400);
            }

            // Merchant bilgisini kontrol et
            $merchant = \App\Models\Merchant::with('category')->find($merchantId);
            if (!$merchant) {
                \Log::warning('Merchant not found', [
                    'merchant_id' => $merchantId,
                    'original_input' => $merchantIdInput
                ]);

                return response()->json([
                    'success' => false,
                    'message' => "Merchant bulunamadı (ID: {$merchantId})",
                ], 400);
            }

            // Kullanıcının bakiyesini kontrol et
            $userWallet = $this->walletService->getUserWallet($user->id);
            if ($userWallet->balance < $amount) {
                \Log::warning('Insufficient balance for QR payment', [
                    'user_id' => $user->id,
                    'current_balance' => $userWallet->balance,
                    'required_amount' => $amount
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Yetersiz bakiye. Mevcut bakiyeniz: ₺ ' . number_format($userWallet->balance, 2, ',', '.'),
                ], 400);
            }

            // Ödeme işlemi - kullanıcının bakiyesinden düş
            $result = $this->walletService->processQrPayment($user->id, $amount, $validated['qr_id'], $merchantId);

            \Log::info('QR payment successful', [
                'user_id' => $user->id,
                'merchant_id' => $merchantId,
                'amount' => $amount,
                'qr_id' => $validated['qr_id'],
                'result' => $result
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Ödeme başarıyla gerçekleştirildi!',
                'balance' => (float) $result['user_balance'],
                'amount_paid' => $amount,
                'merchant' => $merchant->name,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('QR payment validation error', [
                'errors' => $e->errors()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Geçersiz veri.',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('QR payment error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Ödeme işlemi sırasında bir hata oluştu: ' . $e->getMessage(),
            ], 500);
        }
    }
}
