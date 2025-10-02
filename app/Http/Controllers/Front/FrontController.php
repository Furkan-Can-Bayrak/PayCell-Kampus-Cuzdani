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
        
        return view('front.anaSayfa.index', compact('wallet'));
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
}
