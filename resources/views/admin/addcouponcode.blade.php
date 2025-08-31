@extends('admin.maindesign')


@section('add_coupon_code')
    @if (session('coupon_code_message'))
        <div class="alert alert-success" role="alert">
            {{ session('coupon_code_message')}}
        </div>
    @endif

    <div class="container-fluid">
        <form action="{{route('admin.postaddcouponcode')}}" method="POST" style="margin: 0 100px">
            @csrf
            <input class="form-control" type="text" name="coupon_code" placeholder="Enter Coupon Code Here!"> <br>
            <input class="form-control" type="text" name="discount" placeholder="Enter Discount Amount!"> <br>
            <input  type="submit" class="btn btn-primary" name="submit" value="Add Coupon Code">
        </form>
    </div>

@endsection