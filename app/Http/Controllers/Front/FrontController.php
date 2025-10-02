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
}
