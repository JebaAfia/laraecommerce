<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Coupon;
use Stripe;

class UserController extends Controller
{
    public function index()
    {
        if (Auth::check() && Auth::user()->user_type == "user") {
            return view('dashboard');
        }
        elseif (Auth::check() && Auth::user()->user_type == "admin") {
            return view('admin.dashboard');
        }
    }

    public function home(){
        if (Auth::check()) {
            $count = ProductCart::where('user_id', Auth::id())->count();
        }
        else {
            $count = '';
        }
        $products = Product::latest()->take(2)->get();
        return view('index', compact('products','count'));
    }

    public function productDetails($id){
        if (Auth::check()) {
            $count = ProductCart::where('user_id', Auth::id())->count();
        }
        else {
            $count = '';
        }
        $product = Product::findOrFail($id);
        return view('product_details', compact('product', 'count'));
    }

    public function allProducts(){
        if (Auth::check()) {
            $count = ProductCart::where('user_id', Auth::id())->count();
        }
        else {
            $count = '';
        }
        $products = Product::all();
        return view('allproducts', compact('products', 'count'));
    }

    public function addToCart($id){
        $product = Product::findOrFail($id);
        $product_cart = new ProductCart();
        $product_cart->user_id = Auth::id();
        $product_cart->product_id = $product->id;

        $product_cart->save();
        return redirect()->back()->with('cart_message', 'added to the cart');

    }

    public function cartProducts(){
        if (Auth::check()) {
            $count = ProductCart::where('user_id', Auth::id())->count();
            $cart = ProductCart::where('user_id',Auth::id())->get();
        }
        else {
            $count = '';
        }
        return view('viewcartproducts', compact('count', 'cart'));
    }

    public function removeCartProduct($id){
        $cart_product = ProductCart::findOrFail($id);
        $cart_product->delete();
        return redirect()->back();

    }

    public function confirmOrder(Request $request){
        // print_r($request->all());
        $userId = Auth::id();
        $cartItems = ProductCart::with('product')->where('user_id', $userId)->get();
        $totalAmount = $cartItems->sum(function ($cartItem) {
        return $cartItem->product->product_price;
        });

        $payableAmount = $totalAmount;
        $couponCode = $request->input('coupon_code');
        $coupon = null;
        if ($couponCode) {
            $coupon = Coupon::where('coupon_code', $couponCode)->first();

            if ($coupon) {
                if (str_contains($coupon->discount, '%')) {
                    $discountAmount = trim($coupon->discount, '%');
                    $payableAmount -= ($totalAmount * $discountAmount / 100);
                } else {
                    $payableAmount = $totalAmount - $coupon->discount ;
                }
                $payableAmount = max($payableAmount, 0);
            }
        }

        $order = Order::create([
            'receiver_address' => $request->receiver_address,
            'receiver_phone' => $request->receiver_phone,
            'user_id' => Auth::id(),
            'total_amount' => $totalAmount,
            'payable_amount' => $payableAmount,
            'coupon_code' => $couponCode,
        ]);

        $cart_product_id = ProductCart::where('user_id', Auth::id())->get();
        foreach($cart_product_id as $cart_product){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $cart_product->product_id,
                'quantity' => 1
            ]);
            $cart_product->delete();
        }

        return redirect()->back()->with('confirm_order_message','Your order is confirmed!');
    }

    public function myOrders(){
        $orders = Order::where('user_id',Auth::id())->get();
        return view('viewmyorders',compact('orders'));
    }

    /**
     * this is our payment - card page
     *
     *
     */
    public function stripe( Request $request, $order_id = null)
    {

        $userId = Auth::id();
        $cartItems = ProductCart::with('product')->where('user_id', $userId)->get();
        $totalAmount = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->product_price;
        });


        if(!$order_id){

            $payableAmount = $totalAmount;
            $couponCode = $request->input('coupon_code');
            $coupon = null;
            if ($couponCode) {
                $coupon = Coupon::where('coupon_code', $couponCode)->first();

                if ($coupon) {
                    if (str_contains($coupon->discount, '%')) {
                        $discountAmount = trim($coupon->discount, '%');
                        $payableAmount -= ($totalAmount * $discountAmount / 100);
                    } else {
                        $payableAmount = $totalAmount - $coupon->discount ;
                    }
                    $payableAmount = max($payableAmount, 0);
                }
            }

            $order = Order::create([
                'receiver_address' => $request->receiver_address,
                'receiver_phone' => $request->receiver_phone,
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'payable_amount' => $payableAmount,
                'coupon_code' => $couponCode,
                'payment_status' => 'pending'
            ]);

            $cart_product_id = ProductCart::where('user_id', Auth::id())->get();
            foreach($cart_product_id as $cart_product){
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart_product->product_id,
                    'quantity' => 1
                ]);
                $cart_product->delete();
            }

            return [
                'url' => route('stripe', ['order_id' => $order->id])
            ];
        }


        $order = Order::where('id', $order_id)->first();
        $orderItems =OrderItem::where('order_id', $order_id)->get();
        return view('stripe', compact('order', 'orderItems'));
    }

    public function stripePost(Request $request, $order_id)
    {

        $order = Order::where('id', $order_id)->first();

        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([

                "amount" => $order->payable_amount * 100,

                "currency" => "usd",

                "source" => $request->stripeToken,

                "description" => "order reference #" . $order->id
        ]);


        // update the payment status
        $order->status = 'comfirmed';
        $order->payment_status = 'paid';
        $order->save();

        return redirect(route('stripe',['order_id'=>$order->id]))->with('success', 'Payment successful!');
    }
}
