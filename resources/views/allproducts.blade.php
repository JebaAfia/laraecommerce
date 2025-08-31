@extends('maindesign')

@section('all_products')
    <div class="container">
        <div class="row">
            @foreach ($products as $product)
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="box">
                        <a href="{{ route('product_details', $product->id) }}">
                            <div class="img-box">
                                <img src="{{ asset('products/' . $product->product_image) }}" alt="">
                            </div>
                            <div class="detail-box">
                                <h6>
                                    {{ $product->product_title }}
                                </h6>
                                <h6>
                                    Price
                                    <span>
                                        ${{ $product->product_price }}
                                    </span>
                                </h6>
                            </div>
                            <div class="new">
                                <span>
                                    New
                                </span>
                            </div>
                        </a>
                        <a href="{{route('add_to_cart',$product->id)}}" type="button" class="btn btn-info">ADD TO CART</a>
                        <a href="{{ route('stripe', ['price'=>$product->product_price, 'id'=>$product->id] ) }}" type="button" class="btn btn-warning" style="float: right;">PAY NOW</a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="btn-box">
            <a href="{{ route('index') }}">
                View Latest Products
            </a>
        </div>
    </div>
@endsection
