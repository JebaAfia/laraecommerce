@extends('maindesign')
<base href="/public">
@section('product_details')
@if (session('cart_message'))
        <div class="alert alert-success" role="alert">
            {{ session('cart_message')}}
        </div>
    @endif
 
<div class="container my-5">
    <a href="{{route('index')}}" style="display: inline-block; margin-bottom:20px; color:2a5885; text-decoration:none;">&larr;Back to products</a>
    <div class="row g-5">
        <!-- Product Images -->
        <div class="col-md-6">
            <img id="mainImage" src="{{asset('products/'.$product->product_image)}}" class="product-image mb-3" alt="Product">
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h2>{{$product->product_title}}</h2>
            <p class="price">
                ${{$product->product_price}}
            </p>

            <p>{{$product->product_description}}</p>

            <form>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" id="quantity" name="quantity" value="1" class="form-control w-25">
                </div>
                <a href="{{route('add_to_cart',$product->id)}}" type="submit" class="btn btn-primary">Add to Cart</a>
            <a href="{{route('stripe',['price'=>$product->product_price, 'id'=>$product->id])}}" type="button" class="btn btn-warning">PAY NOW</a>
            </form>
        </div>
    </div>
</div>
@endsection
