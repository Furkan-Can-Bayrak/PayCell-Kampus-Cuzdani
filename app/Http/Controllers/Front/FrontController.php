<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class FrontController extends Controller
{
    /**
     * Ana sayfayı gösterir
     *
     * @return View
     */
    public function index(): View
    {
        return view('front.anaSayfa.index');
    }

    /**
     * Para gönder sayfasını gösterir
     *
     * @return View
     */
    public function paraGonder(): View
    {
        return view('front.paraGonder.index');
    }
}
