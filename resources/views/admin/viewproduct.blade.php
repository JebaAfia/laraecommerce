@extends ('admin.maindesign')

@section('view_product')
    @if (session('deleteproduct_message'))
        <div class="alert alert-danger" role="alert">
            {{ session('deleteproduct_message') }}
        </div>
    @endif
    <div class="list-inline-item">>
        <form action="{{route('admin.searchproduct')}}" method="POST">
            @csrf
            <div class="form-group">
                <input type="search" name="search" placeholder="What are you searching for...">
                <button type="submit" class="submit">Search</button>
            </div>
        </form>
    </div>
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">Product ID</th>
                <th scope="col">Product Title</th>
                <th scope="col">Product Description</th>
                <th scope="col">Product Quantity</th>
                <th scope="col">Product Price</th>
                <th scope="col">Product Image</th>
                <th scope="col">Product Category</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <th scope="row">{{ $product->id }}</th>
                    <td>{{ $product->product_title }}</td>
                    <td>{{ Str::limit($product->product_description, 50) }}</td>
                    <td>{{ $product->product_quantity }}</td>
                    <td>{{ $product->product_price }}</td>
                    <td><img src="{{ asset('products/' . $product->product_image) }}" alt=""
                            style="height:100px; width:100px"></td>
                    <td>{{ $product->product_category }}</td>
                    <td><a href="{{ route('admin.updateproduct', $product->id) }}" type="button"
                            class="btn btn-warning">UPDATE</a></td>
                    <td><a href="{{ route('admin.productdelete', $product->id) }}" onclick="return confirm('Are You Sure?')"
                            type="button" class="btn btn-danger">DELETE</a></td>
                </tr>
            @endforeach

            {{ $products->links() }}
        </tbody>
    </table>
@endsection
