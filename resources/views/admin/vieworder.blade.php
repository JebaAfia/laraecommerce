@extends('admin.maindesign')

@section('view_order')
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">Order ID</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Address</th>
                <th scope="col">Phone Number</th>
                <th scope="col">Product Title</th>
                <th scope="col">Product Price</th>
                <th scope="col">Product Image</th>
                <th scope="col">Status</th>
                <th scope="col">PDF</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr>
                    <th scope="row">{{ $order->id }}</th>
                    <td>{{ $order->user->name }}</td>
                    <td>{{ $order->receiver_address }}</td>
                    <td>{{ $order->receiver_phone }}</td>
                    <td>{{ $order->product->product_title }}</td>
                    <td>{{ $order->product->product_price }}</td>
                    <td><img src="{{ asset('products/' . $order->product->product_image) }}" alt=""
                            style="height:100px; width:100px"></td>
                    <td>
                        <form action="{{route('admin.change_status',$order->id)}}" method="POST">
                            @csrf
                            <select name="status" id="" class="form-select" >
                                <option value="{{ $order->status }}">{{ $order->status }}</option>
                                <option value="Delivered">Delivered</option>
                                <option value="pending">pending</option>
                            </select>
                            <input class="btn btn-info" type="submit" name="submit" value="submit" onclick="return confirm('Are You Sure?')">
                        </form>
                    </td>
                    <td>
                        <a href="{{route('admin.downloadpdf',$order->id)}}" class="btn btn-warning">Download PDF</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
