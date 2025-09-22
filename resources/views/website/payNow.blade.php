@extends('layouts.website')
@section('content')
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg" style="padding-bottom: 60px;">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Pay Now</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>Pay Now</li>
                </ul>
            </div>
        </div>
</div>
<!-- Bootstrap Container -->
<div class="d-flex align-items-center justify-content-center  bg-light p-3 w-100">
    <div class="bg-white p-4 rounded shadow text-center w-100" style="max-width: 600px; margin-top: 70px; margin-bottom: 30px;">
        <h2 class="h4 fw-semibold text-dark">Order Summary</h2>
        <p class="text-muted mt-2">Please review your order details before proceeding with the payment.</p>

        <div class="mt-4 p-3 bg-light border rounded text-start">
            <h5 class="fw-semibold text-secondary">Order Details</h5>
            <p class="text-muted mb-1">Order ID: <span class="fw-semibold">{{ $order->id }}</span></p>
            <p class="text-muted mb-1">Customer: <span class="fw-semibold">{{ $customer->name }}</span></p>
            {{-- Uncomment if needed
            <p class="text-muted mb-1">Shipping Address:
                <span class="fw-semibold">
                    {{ $shipping_address->address }}, {{ $shipping_address->city }},
                    {{ $shipping_address->state }}, {{ $shipping_address->pincode }},
                    {{ $shipping_address->landmark }}
                </span>
            </p>
            --}}
            <div class="section">
                <div class="label">Booking Period:</div>
                <div class="value blue">{{\Carbon\Carbon::parse($order->start_date)->format('d-F-Y')}} To {{\Carbon\Carbon::parse($order->end_date)->format('d-F-Y')}}</div>
              </div>
              <div class="section">
                <div class="label">Travelers:</div>
                <div class="value orange"  style="color: #ff9800;">{{$order->people}} People</div>
              </div>
              <div class="section">
                <div class="label">Total Days:</div>
                <div class="value green" style="color: #4caf50;">{{$order->days}} Days</div>
              </div>
            <hr>
            <p class="text-dark fw-bold fs-5">Total: ₹{{ $order->order_total }}</p>
        </div>

        {{-- <a href="{{ route('ccavenue.payment', $payment->id) }}"> --}}
            <button id="payButton" type="button" class="th-btn style1 mt-4 px-4 py-2">
                Proceed to Pay
            </button>
        {{-- </a> --}}
    </div>
</div>

<!-- Razorpay Script -->
<div class="card-body text-center">
    <form action="{{ route('response') }}" method="GET">
        @csrf
        <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    </form>
</div>

<script>
    document.getElementById('payButton')?.addEventListener('click', function () {
        var options = {
            "key": "rzp_test_CpOLrwddTSURV3",
            "amount": "{{ $payment->amount * 100 }}",
            "currency": "INR",
            "name": "Agamana Resort",
            "description": "Razerpay",
            "image": "{{ $site->site_logo }}",
            "handler": function (response) {
                alert('Payment Successful! Payment ID: ' + response.razorpay_payment_id);
                window.location.href = "{{ route('response') }}"; // redirect URL
            },
            "prefill": {
                "name": "{{ $customer->name }}",
                "email": "{{ $customer->email }}",
                "contact": "{{ $customer->mobile }}"
            },
            "theme": {
                "color": "#ff7529"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.open();
    });

    // Optional: Auto open payment on load
    // window.onload = function () {
    //     const button = document.getElementById('payButton');
    //     if (button) {
    //         button.click();
    //     }
    // };
</script>

 @endsection