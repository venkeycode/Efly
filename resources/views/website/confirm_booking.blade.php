@extends('layouts.website')
@section('content')
<div class="breadcumb-wrapper " data-bg-src="{{asset('website') }}/assets/img/bg/breadcumb-bg.jpg" style="padding-bottom: 60px;">
    <div class="container">
        <div class="breadcumb-content">
            <h1 class="breadcumb-title">Confirm & Pay</h1>
            <ul class="breadcumb-menu">
                <li><a href="/">Home</a></li>
                <li>Confirm & Pay</li>
            </ul>
        </div>
    </div>
</div>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Confirmation</title>
  <style>

    h2 {
      color: #00695c;
      margin-bottom: 20px;
    }
    .section {
      margin-bottom: 18px;
    }
    .label {
      color: #757575;
      font-weight: bold;
    }
    .value {
      margin-top: 4px;
      font-size: 16px;
    }
    .blue { color: #2196f3; }
    .orange { color: #ff9800; }
    .green { color: #4caf50; }
    .total-box {
      background-color: #e0f7fa;
      padding: 15px;
      border-radius: 5px;
    }
    .total-label {
      color: #00796b;
      font-weight: bold;
    }
    .total-amount {
      font-size: 1.8em;
      color: #00796b;
    }
  </style>
</head>
<section class="space">
    <div class="container">
        <h2>Confirm & Pay</h2>

        <!-- Booking Summary -->
        <div class="section">
          <div class="label">Date:</div>
          <div class="value blue">{{\Carbon\Carbon::parse($start_date)->format('d-F-Y')}} To {{\Carbon\Carbon::parse($end_date)->format('d-F-Y')}}</div>
        </div>
        <div class="section">
          <div class="label">Travelers:</div>
          <div class="value orange">{{$people}} People</div>
        </div>
        <div class="section">
          <div class="label">Total Days:</div>
          <div class="value green">{{$days}} Days</div>
        </div>
        @php
        $total_amount =$amount*$people*$days;
         @endphp
        <div class="total-box">
          <div class="total-label">Total Amount:</div>
          <div class="total-amount">₹{{$total_amount}}</div>
        </div>

        <!-- Payment Form -->
        <form action="{{route('book.now')}}" method="POST">
            @csrf
            <input type="hidden" name="people" value="{{$people}}" readonly>
            <input type="hidden" name="start_date" value="{{$start_date}}" readonly>
            <input type="hidden" name="end_date" value="{{$end_date}}" readonly>
        </br>
          <button type="submit" class="th-btn style1">Pay {{$total_amount}}</button>
        </form>
  </div>

</section>
@endsection