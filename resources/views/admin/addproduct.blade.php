@extends('admin.maindesign')


@section('add_product')
    @if (session('product_message'))
        <div class="alert alert-success" role="alert">
            {{ session('product_message')}}
        </div>
    @endif

    <div class="container-fluid ">
        <form action="{{route('admin.postaddproduct')}}" method="POST" style="margin: 0 100px" enctype="multipart/form-data">
            @csrf
            <input class="form-control" type="text" name="product_title" placeholder="Enter Product Title!"><br>
            <textarea class="form-control" name="product_description" id="" cols="30" rows="10"></textarea><br>
            <input class="form-control" type="number" name="product_quantity" placeholder="Enter Product Quantity Here!"><br>
            <input class="form-control" type="number" name="product_price" placeholder="Enter Product Price Here!"><br>
            <input class="form-control" type="file" name="product_image"><br>
           
            <select name="product_category" class="form-control">
                @foreach ($categories as $category)
                   <option value="{{$category->category}}">{{$category->category}}</option> 
                @endforeach
            </select> <br>

            <input class="btn btn-success" type="submit" name="submit" value="AddProduct">
        </form>
    </div>

@endsection