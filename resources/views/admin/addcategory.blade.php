@extends('admin.maindesign')


@section('add_category')
    @if (session('category_message'))
        <div class="alert alert-success" role="alert">
            {{ session('category_message')}}
        </div>
    @endif

    <div class="container-fluid">
        <form action="{{route('admin.postaddcategory')}}" method="POST" style="margin: 0 100px">
            @csrf
            <input class="form-control" type="text" name="category" placeholder="Enter Category Name!"> <br>
            <input  type="submit" class="btn btn-primary" name="submit" value="AddCategory">
        </form>
    </div>

@endsection