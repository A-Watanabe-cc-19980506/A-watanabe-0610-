<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class CheckoutController extends Controller
{
    /**
     * 💡 A. 購入確認画面を表示する
     */
    public function index()
    {
        // ログインユーザーのカート情報を取得（商品データも一緒にロード）
        $cart = Cart::with('cartItems.productVariation.product')
            ->where('user_id', Auth::id())
            ->first();

        // カートが空ならカート画面に戻す
        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // 購入確認画面のView（checkout/index.blade.php）を表示
        return view('checkout.index', compact('cart'));
    }

    /**
     * 💡 B. 確認画面から注文確定ボタンが押されたとき（Stripe決済へリダイレクト）
     */
    public function process(Request $request)
    {
        $cart = Cart::with('cartItems.productVariation.product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart || $cart->cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'カートが空です。');
        }

        // 配送先情報をセッションに保存（Stripe決済後に取得するため）
        session([
            'checkout_data' => [
                'name' => $request->input('name'),
                'postal_code' => $request->input('postal_code'),
                'address' => $request->input('address'),
                'building' => $request->input('building'),
            ]
        ]);

        Stripe::setApiKey(env('STRIPE_SECRET'));

        $lineItems = [];
        foreach ($cart->cartItems as $item) {
            $product = $item->productVariation->product;
            $lineItems[] = [
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $product->name,
                    ],
                    'unit_amount' => $product->price,
                ],
                'quantity' => $item->quantity,
            ];
        }

        // Stripeの決済ページセッションを作成
        $session = StripeSession::create([
            'payment_method_types' => ['card'],
            'line_items' => $lineItems,
            'mode' => 'payment',
            'success_url' => route('checkout.success'),
            'cancel_url' => route('cart.index'),
        ]);

        // Stripe決済セッションIDもセッションに保存
        session(['stripe_session_id' => $session->id]);

        return redirect($session->url, 303);
    }

    /**
     * 💡 C. Stripe決済完了後の処理（提示していただいたコード）
     */
    public function success(Request $request)
    {
        $cart = Cart::with('cartItems.productVariation.product')->where('user_id', Auth::id())->first();
        
        // セッションから配送先情報とStripeセッションIDを取得
        $checkoutData = session('checkout_data', []);
        $stripeSessionId = session('stripe_session_id', '');

        if ($cart && !$cart->cartItems->isEmpty()) {
            DB::transaction(function () use ($cart, $checkoutData, $stripeSessionId) {
                // 合計金額を計算
                $totalPrice = $cart->cartItems->sum(function ($item) {
                    return $item->productVariation->product->price * $item->quantity;
                });

                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $totalPrice,
                    'stripe_session_id' => $stripeSessionId,
                    'status' => 'paid',
                    'name' => $checkoutData['name'] ?? '',
                    'zipcode' => $checkoutData['postal_code'] ?? '',
                    'address' => $checkoutData['address'] ?? '',
                    'building' => $checkoutData['building'] ?? '',
                ]);

                foreach ($cart->cartItems as $item) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_variation_id' => $item->product_variation_id,
                        'quantity' => $item->quantity,
                        'price' => $item->productVariation->product->price,
                    ]);
                }

                $cart->cartItems()->delete();
                $cart->delete();
            });
        }

        // セッションデータをクリア
        session()->forget(['checkout_data', 'stripe_session_id']);

        return view('checkout.success');
    }
}