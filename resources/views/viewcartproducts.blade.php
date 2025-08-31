@extends('maindesign')
@section('viewcart_products')

<div style="max-width:1000px; margin:0 auto; padding: 20px;">
<table class="table table-dark table-striped">
    <thead>
        <tr>
            <th scope="col">Product ID</th>
            <th scope="col">Product Title</th>
            <th scope="col">Product Price</th>
            <th scope="col">Product Image</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        @php
            $price = 0;
        @endphp
        @foreach ($cart as $cart_product)
            <tr>
                <th scope="row" >{{ $cart_product->product->id }}</th>
                <td>{{ $cart_product->product->product_title }}</td>
                <td>${{ $cart_product->product->product_price }}</td>
                <td><img src="{{ asset('products/' . $cart_product->product->product_image) }}" alt=""
                        style="height:100px; width:100px"></td>
                <td><a href="{{route('removecartproduct',$cart_product->id)}}" type="button" class="btn btn-danger">REMOVE</a></td>
            </tr>
        @php
            $price = $price+$cart_product->product->product_price;
        @endphp
        @endforeach

        <tr scope="row" >
            <td></td>
            <td>Total Price</td>
            <td>=${{$price}}</td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>
@if (session('confirm_order_message'))
    <div class="alert alert-success" role="alert">
        {{session('confirm_order_message')}}
    </div>
@endif
<form action="{{route('confirm_order')}}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="" class="form-label">Address:</label>
        <input  class="form-control" type="text" name="receiver_address" placeholder="Enter your address here!" required>
    </div>
    <div class="mb-3">
        <label for="" class="form-label">Phone Number:</label>
        <input  class="form-control" type="text" name="receiver_phone" placeholder="Enter your phone number here!" required>
    </div>
    <input type="submit" class="btn btn-warning"  name="submit" value="Confirm Order">
    <a href="{{route('stripe',$price)}}" type="button" class="btn btn-info">PAY NOW</a>
    
</form>
</div>
@endsection