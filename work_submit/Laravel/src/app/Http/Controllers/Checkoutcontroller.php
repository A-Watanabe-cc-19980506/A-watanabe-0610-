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
            'success_url' => route('checkout.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cart.index'),
            'metadata' => [
                'name'     => $request->input('name'),
                'zipcode'  => $request->input('postal_code'),
                'address'  => $request->input('address1'),
                'building' => $request->input('building'),
            ],
        ]);

        return redirect($session->url, 303);
    }

    /**
     * 💡 C. Stripe決済完了後の処理（提示していただいたコード）
     */
    public function success(Request $request)
    {
        $cart = Cart::with('cartItems.productVariation.product')->where('user_id', Auth::id())->first();

        if ($cart && !$cart->cartItems->isEmpty()) {
            DB::transaction(function () use ($cart, $request) {
                
        $sessionId = $request->query('session_id');
        $session = StripeSession::retrieve($sessionId);
        
        // ★ Stripeに預けておいた「荷物（住所データ）」を取り出す
        $meta = $session->metadata;

        // 2. データベースに保存する
        $order = Order::create([
            'user_id'           => Auth::id(),
            'total_price'       => $totalPrice,
            'stripe_session_id' => $sessionId,
            'status'            => 'paid',
            'name'              => $meta->name,
            'zipcode'           => $meta->zipcode, 
            'address'           => $meta->address,
            'building'          => $meta->building,
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

        return view('checkout.success');
    }
}