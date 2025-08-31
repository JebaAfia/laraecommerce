@extends('admin.maindesign')

<base href="/public">
@section('update_category')
    @if (session('category_updated_message'))
        <div class="alert alert-info" role="alert">
            {{ session('category_updated_message')}}
        </div>
    @endif

    <div class="container-fluid">
        <form action="{{route('admin.postupdatecategory', $category->id)}}" method="POST">
            @csrf
            <input type="text" name="category" value="{{$category->category}}">
            <input type="submit" name="submit" value="Update Category">
        </form>
    </div>

@endsection