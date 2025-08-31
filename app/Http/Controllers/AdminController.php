<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Coupon;
use Barryvdh\DomPDF\Facade\Pdf;
class AdminController extends Controller
{
    public function addCategory(){
        return view('admin.addcategory');
    }

    public function postAddCategory(Request $request){
        $category = new Category();
        $category->category = $request->category;
        $category->save();
        return redirect()->back()->with('category_message', 'Category added successfully!');
        
    }

    public function viewCategory(){
        $categories =  Category::all();
        return view('admin.viewcategory', compact('categories'));
    }

    public function updateCategory($id){
        $category = Category::findOrFail($id);
        return view('admin.updatecategory', compact('category'));
    }

    public function deleteCategory($id){
        $category = Category::findOrFail($id);

        $category->delete();
        return redirect()->back()->with('deletecategory_message', 'Category is deleted!');
    }

    public function postUpdateCategory(Request $request, $id){
        $category = Category::findOrFail($id);
        $category->category=$request->category;
        $category->save();
        return redirect()->back()->with('category_updated_message', 'Category updated successfully!');

    }

    public function addProduct(){
        $categories =  Category::all();
        return view('admin.addproduct', compact('categories'));
    }

    public function postAddProduct(Request $request){
        $product = new Product();
        $product->product_title = $request->product_title;
        $product->product_description = $request->product_description;
        $product->product_quantity = $request->product_quantity;
        $product->product_price = $request->product_price;

        $image = $request->product_image;
        if ($image) {
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $product->product_image = $imagename;
        }

        $product->product_category = $request->product_category;
        $product->save();

        if ($image && $product->save()) {
            $request->product_image->move('products', $imagename);
        }
        return redirect()->back()->with('product_message', 'Product added successfully!');
        
    }

    public function viewProduct() {
        $products =  Product::paginate(2);
        return view('admin.viewproduct', compact('products'));
    }

    public function deleteProduct($id){
        $product = Product::findOrFail($id);
        $image_path = public_path('products/'.$product->product_image);

        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $product->delete();
        return redirect()->back()->with('deleteproduct_message', 'Product is deleted!');
    }

    public function updateProduct($id){
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.updateproduct', compact('product', 'categories'));
    }

    public function postUpdateProduct(Request $request, $id){
        $product = Product::findOrFail($id);
        $product->product_title = $request->product_title;
        $product->product_description = $request->product_description;
        $product->product_quantity = $request->product_quantity;
        $product->product_price = $request->product_price;

        $image = $request->product_image;
        if ($image) {
            $imagename = time().'.'.$image->getClientOriginalExtension();
            $product->product_image = $imagename;
        }

        $product->product_category = $request->product_category;
        $product->save();

        if ($image && $product->save()) {
            $request->product_image->move('products', $imagename);
        }
        return redirect()->back()->with('product_updated_message', 'Product updated successfully!');

    }
    public function searchProduct(Request $request){
        $products = Product::where('product_title','LIKE','%'.$request->search.'%')->
                            orWhere('product_description','LIKE','%'.$request->search.'%')->
                            orWhere('product_category','LIKE','%'.$request->search.'%')->paginate(2);
        return view('admin.viewproduct', compact('products'));
    }

    public function viewOrder(){
        $orders = Order::all();
        return view('admin.vieworder', compact('orders'));
    }
    public function changeStatus(Request $request, $id){
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();
        return redirect()->back();
    }
    public function downloadPDF($id){
        $data = Order::findOrFail($id);
        $pdf = Pdf::loadView('admin.invoice', compact('data'));
        return $pdf->download('invoice.pdf');
    }

    public function addCouponCode(){
        return view('admin.addcouponcode');
    }

    public function postAddCouponCode(Request $request){
        $coupon = new Coupon();
        $coupon->coupon_code = $request->coupon_code;
        $coupon->discount = $request->discount;
        $coupon->save();
        return redirect()->back()->with('coupon_code_message', 'Coupon Code added successfully!');
        
    }

    public function viewCouponCode(){
        $coupons =  Coupon::all();
        return view('admin.viewcouponcode', compact('coupons'));
    }

    public function deleteCouponCode($id){
        $coupon = Coupon::findOrFail($id);

        $coupon->delete();
        return redirect()->back()->with('deletecoupon_message', 'Coupon Code is deleted!');
    }

    public function postCouponCode(Request $request, $price){
        $coupon = Coupon::where('coupon_code', $request->coupon)->first();
        if ($coupon) {
            if (str_contains($coupon->discount, '%')) {
                $coupon->discount = trim($coupon->discount, '%');
                $new_price = $price - (($price * $coupon->discount) / 100);
                return redirect(route('stripe',['price'=>$new_price]))->with('coupon_message', 'Coupon Code applied successfully!');
            }
        }
        return redirect(route('stripe',['price'=>$price]))->with('coupon_message', 'Coupon Code does not exists!');
    }

}
