@extends('layouts.website')
@section('content')
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg" style="padding-bottom: 60px;">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Booking Successful</h1>
            <ul class="breadcumb-menu">
                <li><a href="home-travel.html">Home</a></li>
                <li>Booking Confimed</li>
            </ul>
        </div>
    </div>
</div>
<div class="d-flex align-items-center justify-content-center min-vh-100 w-100 p-3">
    <div class="bg-white p-4 rounded shadow text-center w-100" style="max-width: 600px; margin-top: 70px; margin-bottom: 30px;">
        <img src="{{ asset('media/payment_success.png') }}" alt="Payment Success" class="mx-auto d-block mb-3" style="max-width: 150px;">

        <h2 class="h4 fw-semibold text-dark">
            @if($mode == 'cod') Order Placed @else Payment Successful @endif
        </h2>

        @php
            $shipping_address = json_decode($order->delivery_address);
        @endphp

        <div class="mt-4 p-3 bg-light border rounded text-start">
            <h5 class="fw-semibold text-secondary">Order Details</h5>
            <p class="text-muted mb-1">Order ID: <span class="fw-semibold">{{ $order->id }}</span></p>
            <p class="text-muted mb-3">Customer: <span class="fw-semibold">{{ $customer->name }}</span></p>

            <hr>
            <div class="section">
                <div class="label">Booking Period:</div>
                <div class="value blue">{{\Carbon\Carbon::parse($order->start_date)->format('d-F-Y')}} To {{\Carbon\Carbon::parse($order->end_date)->format('d-F-Y')}}</div>
              </div>
              <hr>
              <div class="section">
                <div class="label">Travelers:</div>
                <div class="value orange"  style="color: #ff9800;">{{$order->people}} People</div>
              </div>
              <hr>
              <div class="section">
                <div class="label">Total Days:</div>
                <div class="value green" style="color: #4caf50;">{{$order->days}} Days</div>
              </div>

            <hr>
            {{-- <p class="text-dark fw-semibold mt-2">Shipping Charge: ₹{{ $order->delivery_charge }}</p> --}}

            <p class="text-dark fw-bold fs-5">Total: ₹{{ $order->order_total }}</p>
        </div>
    </div>
</div>


@endsection
