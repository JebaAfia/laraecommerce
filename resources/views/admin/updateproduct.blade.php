@extends('admin.maindesign')

<base href="/public">
@section('update_product')
    @if (session('product_updated_message'))
        <div class="alert alert-success" role="alert">
            {{ session('product_updated_message')}}
        </div>
    @endif

    <div class="container-fluid ">
        <form action="{{route('admin.postupdateproduct', $product->id)}}" method="POST" style="margin: 0 100px" enctype="multipart/form-data">
            @csrf
            <input class="form-control" type="text" name="product_title" value="{{$product->product_title}}"><br>
            <textarea class="form-control" name="product_description"cols="30" rows="10" >{{$product->product_description}}</textarea><br>
            <input class="form-control" type="number" name="product_quantity" value="{{$product->product_quantity}}"><br>
            <input class="form-control" type="number" name="product_price" value="{{$product->product_price}}"><br>
            <img src="{{asset('products/'.$product->product_image)}}" alt="" style="height: 100px; width:100px;"><label for="">Old Image</label>
            <input class="form-control" type="file" name="product_image"><label for="">Add New Image here!</label><br>
           
            <select name="product_category" class="form-control">
                <option value="{{$product->product_category}}">
                    {{$product->product_category}}
                </option>
                @foreach ($categories as $category)
                   <option value="{{$category->category}}">{{$category->category}}</option> 
                @endforeach
            </select> <br>

            <input class="btn btn-success" type="submit" name="submit" value="AddProduct">
        </form>
    </div>

@endsection