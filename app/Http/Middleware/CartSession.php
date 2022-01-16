<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use App\Cart;

class CartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    
    // カート生成処理
    public function handle($request, Closure $next)
    {
        // セクションのID有無確認
        if(!Session::has('cart')){
            // 無い場合、カートの作成
            $cart = Cart::create();
            // セクションにカートIDを保存
            Session::put('cart',$cart->id);
        }
        return $next($request);
    }
}
