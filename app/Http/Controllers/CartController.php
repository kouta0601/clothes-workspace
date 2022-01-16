<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Cart;
use App\Lineltem;

class CartController extends Controller
{
    public function index()
    {
        // カートID取得
        $cart_id = Session::get('cart');
        // レコード取得
        $cart = Cart::find($cart_id);

        $total_price = 0;
        // 合計金額の算出
        foreach ($cart->products as $product) {
            $total_price += $product->price * $product->pivot->quantity;
        }

        return view('cart.index')
            ->with('line_items', $cart->products)
            ->with('total_price', $total_price);
    }

    public function checkout()
    {
        // セッションからカートIDを取得
        $cart_id = Session::get('cart');
        $cart = Cart::find($cart_id);

        if (count($cart->products) <= 0) {
            return redirect(route('cart.index'));
        }

        // Stripe Checkoutへ渡す、購入商品リスト作成
        $line_items = [];
        foreach ($cart->products as $product) {
            $line_item = [
                'name'        => $product->name,
                'description' => $product->description,
                'amount'      => $product->price,
                'currency'    => 'jpy',
                'quantity'    => $product->pivot->quantity,
            ];
            // 配列の末尾に値を追加
            // array_push($line_items, $line_item);
            array_push($line_items, $line_item);
        }

        // シークレットキーセット
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Stripe Checkoutセッションの作成
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'], // 支払い方法の指定
            'line_items'           => [$line_items], // 購入商品のセット
            'success_url'          => route('cart.success'), // 決済成功時のリダイレクトURL
            'cancel_url'           => route('cart.index'), // 決済失敗時のリダイレクトURL
        ]);

        return view('cart.checkout', [
            'session' => $session,
            'publicKey' => env('STRIPE_PUBLIC_KEY')
        ]);
    }

    public function success()
    {
        $cart_id = Session::get('cart');
        Lineltem::where('cart_id', $cart_id)->delete();

        return redirect(route('product.index'));
    }
}
