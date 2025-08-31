<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\ProductCart;
use App\Models\Order;
use Session;
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
        $cart_product_id = ProductCart::where('user_id', Auth::id())->get();
        $address = $request->receiver_address;
        $phone = $request->receiver_phone;
        foreach($cart_product_id as $cart_product){
            $order = new Order();

            $order->receiver_address = $address;
            $order->receiver_phone = $phone;
            $order->user_id = Auth::id();
            $order->product_id = $cart_product->product_id ;

            $order->save();
            $cart_product->delete();
        }
        // $cart = ProductCart::where('user_id',Auth::id())->get();
        // foreach ($cart as $cart) {
        //     $cart_id = ProductCart::findOrFail($cart->id);
        //     $cart_id->delete();
        // }
        
        return redirect()->back()->with('confirm_order_message','Your order is confirmed!');
    }
    public function myOrders(){
        $orders = Order::where('user_id',Auth::id())->get();
        return view('viewmyorders',compact('orders'));
    }

    public function stripe($price, $id = null)
    {   
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

    public function stripePost(Request $request, $price)
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
