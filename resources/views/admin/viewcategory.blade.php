@extends ('admin.maindesign')

@section('view_category')
@if (session('deletecategory_message'))
    <div class="alert alert-danger" role="alert">
        {{session('deletecategory_message')}}
    </div>
@endif
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">Category ID</th>
                <th scope="col">Category Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <th scope="row">{{$category->id}}</th>
                    <td>{{$category->category}}</td>
                    <td><a href="{{route('admin.categoryupdate', $category->id)}}" type="button" class="btn btn-info">UPDATE</a></td>
                    <td><a href="{{route('admin.categorydelete', $category->id)}}" onclick="return confirm('Are You Sure?')" type="button" class="btn btn-danger">DELETE</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
