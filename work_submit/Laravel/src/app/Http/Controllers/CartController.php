<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\ProductVariation;

class CartController extends Controller
{
    // カート画面の表示
// app/Http/Controllers/CartController.php

    public function index()
    {
        // 1. ログイン中はログインユーザーのカートのみ、未ログイン時はセッションIDのカートのみ取得する
        $query = Cart::with(['cartItems.productVariation.color', 'cartItems.productVariation.size', 'cartItems.product']);

        if (Auth::check()) {
            $query->where('user_id', Auth::id());
        } else {
            $query->where('session_key', session()->getId());
        }

        $cart = $query->first();

        // 2. カートがまだ存在しない場合は、空の配列を渡す
        $cartItems = $cart ? $cart->cartItems : collect();

        // 3. カート一覧画面（Blade）にデータを渡す
        // ★ ここで渡す変数名（'cartItems'）と、Blade側で使う変数名を一致させます
        return view('carts.table', compact('cartItems'));
    }
    // カートへの追加処理（ここに追加します）
    public function store(Request $request)
    {
        $request->validate([
            'product_variation_id' => 'required', // 必須チェック
            'quantity' => 'required|integer|min:1',
        ]);
        // 1. 画面から送られてきた product_variation_id と quantity を取得
        // ※ フォーム側のname属性（product_variation_id）と一致させてください
        $variationId = $request->input('product_variation_id');
        $quantity = $request->input('quantity', 1);

        // バリエーション（在庫）を取得
        $variation = ProductVariation::find($variationId);
        if (!$variation) {
            return redirect()->route('cart.index')->with('error', '商品が見つかりません。');
        }
        $stock = (int) $variation->stock;

        // 2. ログイン中なら user_id、未ログインなら session_key を取得
        $userId = Auth::id();
        $sessionKey = session()->getId();

        // 3. 親カート（cartsテーブル）を取得、なければ作成
        $cart = Cart::firstOrCreate(
            $userId ? ['user_id' => $userId] : ['session_key' => $sessionKey],
            [
                'user_id' => $userId,
                'session_key' => $userId ? null : $sessionKey,
            ]
        );

        // 4. カート明細に同じ商品が既にあるか確認し、在庫を超えないように調整して登録
        $cartDetail = CartItem::where('cart_id', $cart->id)
            ->where('product_variation_id', $variationId)
            ->first();

        $existingQty = $cartDetail ? (int) $cartDetail->quantity : 0;
        $availableToAdd = max(0, $stock - $existingQty);

        if ($availableToAdd <= 0) {
            return redirect()->route('cart.index')->with('warning', '在庫がありません。');
        }

        $addQty = min((int) $quantity, $availableToAdd);

        if ($cartDetail) {
            $cartDetail->quantity += $addQty;
            $cartDetail->save();
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_variation_id' => $variationId,
                'quantity' => $addQty,
                'is_checked' => 1,
            ]);
        }

        if ($addQty < $quantity) {
            return redirect()->route('cart.index')->with('warning', '在庫が不足していたため、追加数を' . $addQty . 'に調整しました。');
        }

        // 5. カート一覧画面にリダイレクト
        return redirect()->route('cart.index')->with('success', 'カートに商品を追加しました。');
    }
    public function destroy(Request $request, $id)
    {
        $cartDetail = CartItem::findOrFail($id);
        $cart = $cartDetail->cart;
        $userId = Auth::id();

        if ($userId) {
            if ($cart->user_id !== $userId) {
                abort(403);
            }
        } else {
            if ($cart->session_key !== session()->getId()) {
                abort(403);
            }
        }

        $cartDetail->delete();

        return redirect()->route('cart.index')->with('success', '商品をカートから削除しました。');
    }
    public function update(Request $request, $id)
    {
        $cartItem = CartItem::with('productVariation')->findOrFail($id);
        $newQuantity = $request->input('quantity');

        // 1. バリデーション（最低1個以上）
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        // 2. 🚨 DBの最新在庫数をチェック
        $dbStock = $cartItem->productVariation->stock ?? 0;

        if ($newQuantity > $dbStock) {
            // ★ 上限を超えていた場合は、エラーメッセージをJSONで返却（またはリダイレクト）
            return response()->json([
                'status' => 'error',
                'message' => "申し訳ありません。この商品の在庫数は最大 {$dbStock} 点です。"
            ], 422); // 422はバリデーションエラーのステータスコード
        }

        // 3. 在庫以内なら保存
        $cartItem->quantity = $newQuantity;
        $cartItem->save();

        return response()->json([
            'status' => 'success',
            'message' => '数量を更新しました。',
            'new_quantity' => $cartItem->quantity
        ]);
    }

    /**
     * ★【追加】購入に進むボタンを押した際のログインチェック
     */
    public function checkLoginBeforeCheckout(Request $request)
    {
        if (!Auth::check()) {
            // 未ログインなら、現在のカート画面（/cart）を戻り先に指定してログインへ
            return redirect()->route('login', ['redirect_to' => route('cart.index')]);
        }

        // ⭕ ログイン済みの場合は、購入確認画面に進める
        return redirect()->route('checkout.index');
    }
}