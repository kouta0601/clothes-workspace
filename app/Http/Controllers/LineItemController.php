<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Lineltem;

class LineItemController extends Controller
{
    public function create(Request $request)
    {
        // セッションからデータを取得
        $cart_id = Session::get('cart');
        // 取得したデータの有無確認(カートに入っているか確認)
        $line_item = Lineltem::where('cart_id', $cart_id)
            ->where('product_id', $request->input('id'))
            ->first();

        // すでにカートに入れている商品を追加した場合の処理
        if ($line_item) {
            $line_item->quantity += $request->input('quantity');
            $line_item->save();

            // 追加した商品が新規の場合
        } else {
            Lineltem::create([
                'cart_id' => $cart_id,
                'product_id' => $request->input('id'),
                'quantity' => $request->input('quantity'),
            ]);
        }

        return redirect(route('cart.index'));
    }

    public function delete(Request $request)
    {
        // カートの商品IDを取得して削除
        Lineltem::destroy($request->input('id'));
        return redirect(route('cart.index'));
    }
}
