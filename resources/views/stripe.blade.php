@extends('maindesign')
{{-- <base href="/public"> --}}
@section('stripe_view')
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default credit-card-box">
                    <div class="panel-heading display-table">
                        <h3 class="panel-title">Payment Details</h3>

                    </div>

                    <div class="panel-body">



                        @if (Session::has('success'))
                            <div class="alert alert-success text-center">

                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>

                                <p>{{ Session::get('success') }}</p>

                            </div>
                        @endif



                        <form role="form" action="{{ route('stripe.post', $price) }}" method="post"
                            class="require-validation" data-cc-on-file="false"
                            data-stripe-publishable-key="{{ env('STRIPE_KEY') }}" id="payment-form">

                            @csrf



                            <div class='form-row row'>

                                <div class='col-xs-12 form-group required'>

                                    <label class='control-label'>Address</label> <input class='form-control'
                                        size='4' type='text' name="receiver_address" required>

                                </div>

                            </div>

                            <div class='form-row row'>

                                <div class='col-xs-12 form-group required'>

                                    <label class='control-label'>Phone Number</label> <input class='form-control'
                                        size='4' type='text' name="receiver_phone" required>

                                </div>

                            </div>

                            <div class='form-row row'>

                                <div class='col-xs-12 form-group required'>

                                    <label class='control-label'>Name on Card</label> <input class='form-control'
                                        size='4' type='text'>

                                </div>

                            </div>



                            <div class='form-row row'>

                                <div class='col-xs-12 form-group card required'>

                                    <label class='control-label'>Card Number</label> <input autocomplete='off'
                                        class='form-control card-number' size='20' type='text'>

                                </div>

                            </div>



                            <div class='form-row row'>

                                <div class='col-xs-12 col-md-4 form-group cvc required'>

                                    <label class='control-label'>CVC</label> <input autocomplete='off'
                                        class='form-control card-cvc' placeholder='ex. 311' size='4'
                                        type='text'>

                                </div>

                                <div class='col-xs-12 col-md-4 form-group expiration required'>

                                    <label class='control-label'>Expiration Month</label> <input
                                        class='form-control card-expiry-month' placeholder='MM' size='2'
                                        type='text'>

                                </div>

                                <div class='col-xs-12 col-md-4 form-group expiration required'>

                                    <label class='control-label'>Expiration Year</label> <input
                                        class='form-control card-expiry-year' placeholder='YYYY' size='4'
                                        type='text'>

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-xs-12">

                                    <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now
                                        ${{$price}}</button>

                                </div>

                            </div>



                        </form>

                    </div>

                </div>

            </div>

            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading display-table">
                        <h3 class="panel-title">Coupon Code</h3>
                    </div>

                    <div class="panel-body">
                        @if (Session::has('coupon_message'))
                            <div class="alert alert-info text-center">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
                                <p>{{ Session::get('coupon_message') }}</p>
                            </div>
                        @endif
                        <form action=" {{ route('coupon.post', $price) }}" method="POST">
                            @csrf
                            <div class='form-row row'>
                                <div class='col-xs-12 form-group required'>
                                    <label class='control-label'>Coupon Code</label> <input class='form-control'
                                        size='4' type='text' name="coupon">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-primary btn-lg btn-block" style="color: aliceblue" type="submit">Apply</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

