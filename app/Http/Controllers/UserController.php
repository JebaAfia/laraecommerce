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

    public function stripe( Request $request)
    {
        $userId = Auth::id();
        $cartItems = ProductCart::with('product')->where('user_id', $userId)->get();
        $totalAmount = $cartItems->sum(function ($cartItem) {
        return $cartItem->product->product_price;
        });

        $payableAmount = $totalAmount;

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

        if (Auth::check()) {
            $count = ProductCart::where('user_id', Auth::id())->count();
            $cart = ProductCart::where('user_id',Auth::id())->get();
        }
        else {
            $count = '';
        }
        if ($id) {
            $product = Product::findOrFail($id);
            ProductCart::firstOrCreate([
                'user_id' => Auth::id(),
                'product_id' => $product->id
            ]);
        }
        $price = $price;

        return view('stripe', compact('count', 'price'));
    }

    public function stripePost(Request $request)
    {
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([

                "amount" => $price * 100,

                "currency" => "usd",

                "source" => $request->stripeToken,

                "description" => "Test payment from itsolutionstuff.com."
        ]);
        $cart_product_id = ProductCart::where('user_id', Auth::id())->get();
        $address = $request->receiver_address;
        $phone = $request->receiver_phone;
        foreach($cart_product_id as $cart_product){
            $order = new Order();

            $order->receiver_address = $address;
            $order->receiver_phone = $phone;
            $order->user_id = Auth::id();
            $order->product_id = $cart_product->product_id ;
            $order->payment_status = "paid";

            $order->save();
            $cart_product->delete();
        }
        return redirect(route('stripe',['price'=>$price]))->with('success', 'Payment successful!');
        return back();

    }
}
