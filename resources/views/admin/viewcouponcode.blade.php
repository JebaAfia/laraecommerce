@extends ('admin.maindesign')

@section('view_coupon_code')
@if (session('deletecoupon_message'))
    <div class="alert alert-danger" role="alert">
        {{session('deletecoupon_message')}}
    </div>
@endif
    <table class="table table-dark table-striped">
        <thead>
            <tr>
                <th scope="col">Coupon ID</th>
                <th scope="col">Coupon Code</th>
                <th scope="col">Discount</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($coupons as $coupon)
                <tr>
                    <th scope="row">{{$coupon->id}}</th>
                    <td>{{$coupon->coupon_code}}</td>
                    <td>{{$coupon->discount}}</td>
                    <td><a href="{{route('admin.coupondelete', $coupon->id)}}" onclick="return confirm('Are You Sure?')" type="button" class="btn btn-danger">DELETE</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
