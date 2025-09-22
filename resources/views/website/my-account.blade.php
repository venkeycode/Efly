@extends('layouts.website')
@section('content')
 <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">My account</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>My account</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
Team Area  
==============================-->
    <section class="space">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="th-account-form">
                        <form action="mail.php" method="POST" class="account-form ajax-contact">
                            <h3 class="text-center mt-n2 mb-30">Reset password</h3>
                            <div class="row">
                                <div class="form-group col-12">
                                    <label>Your email</label>
                                    <input type="email" class="form-control" name="email" id="email4" placeholder="">
                                </div>

                                <div class="form-btn col-12">
                                    <button class="th-btn btn-fw th-radius2">Get new password</button>
                                </div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection