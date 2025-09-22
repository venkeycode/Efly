@extends('layouts.website')
@section('content')
<!--==============================
    Breadcumb
============================== -->
    <!-- <div class="breadcumb-wrapper " data-bg-src="{{asset('website') }}/assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Register</h1>
                <ul class="breadcumb-menu">
                    <li><a href="/">Home</a></li>
                    <li>Register</li>
                </ul>
            </div>
        </div>
    </div> -->
    <!--==============================
Contact Area
==============================-->
    <!-- <div class="space">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">Get In Touch</span>
                <h2 class="sec-title">Our Contact Information</h2>
            </div>
            <div class="row gy-4 justify-content-center">
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid style2">
                        <div class="about-contact-icon">
                            <img src="{{asset('website') }}/assets/img/icon/location-dot2.svg" alt="">
                        </div>
                        <div class="about-contact-details">
                            <h6 class="box-title">Our Address</h6>
                            <p class="about-contact-details-text">Sree Abhi Prani nature stay, RWRM+H3, Neragalale, </p>
                            <p class="about-contact-details-text">Magge, Alur (Tq), Hassan, Karnataka 573129</p>
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid">
                        <div class="about-contact-icon">
                            <img src="{{asset('website') }}/assets/img/icon/call.svg" alt="">
                        </div>
                        <div class="about-contact-details">
                            <h6 class="box-title">Phone Number</h6>
                            <p class="about-contact-details-text"><a href="tel:01234567890">+91 8660991527</a></p>
                           
                        </div>
                    </div>
                </div>
                <div class="col-xl-4 col-lg-6">
                    <div class="about-contact-grid">
                        <div class="about-contact-icon">
                            <img src="{{asset('website') }}/assets/img/icon/mail.svg" alt="">
                        </div>
                        <div class="about-contact-details">
                            <h6 class="box-title">Email Address</h6>
                            <p class="about-contact-details-text"><a href="mailto:mailinfo00@tourm.com">info@agamana.com</a></p>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> -->
    <!--==============================
Video Area
==============================-->
    <div class="space-extra2-top space-extra2-bottom" data-bg-src="">
        <div class="container">
            <div class="row flex-row-reverse justify-content-center align-items-center">
                <!-- <div class="col-lg-6">
                    <div class="video-box1">
                        <a href="https://www.youtube.com/watch?v=_sI_Ps7JSEk" class="play-btn style2 popup-video"><i class="fa-sharp fa-solid fa-play"></i></a>
                    </div>

                </div> -->
                <div class="col-lg-6">
                    <div>
                        <form action="{{route('register.submit')}}" method="POST" class=" ">
                        @csrf    
                        <h3 class="sec-title mb-30 text-capitalize">Register</h3>
                            <div class="row">
                                <div class="col-12 form-group">
                                    <input type="text" class="form-control" name="name"  placeholder="NAME">
                                    <img src="{{asset('website') }}/assets/img/icon/user.svg" alt="">
                                </div>
                                <div class="col-12 form-group">
                                    <input type="email" class="form-control" name="email" placeholder="EMAIL">
                                    <img src="{{asset('website') }}/assets/img/icon/mail.svg" alt="">
                                </div>
                                <div class="col-12 form-group">
                                    <input type="mobile" class="form-control" name="mobile" placeholder="MOBILE">
                                    <img src="{{asset('website') }}/assets/img/icon/mail.svg" alt="">
                                </div>
                                <!-- <div class="col-12 form-group">
                                    <input type="text" class="form-control" name="password"  placeholder="PASSWORD">
                                    <img src="{{asset('website') }}/assets/img/icon/mail.svg" alt="">
                                </div>
                                <div class="col-12 form-group">
                                    <input type="text" class="form-control" name="confirmpassword"  placeholder="CONFIRM PASSWORD">
                                    <img src="{{asset('website') }}/assets/img/icon/mail.svg" alt="">
                                </div> -->
                                <!-- <div class="form-group col-12">
                                    <textarea name="message"  cols="30" rows="3" class="form-control" placeholder="TEXT AREA"></textarea>
                                    <img src="{{asset('website') }}/assets/img/icon/chat.svg" alt="">
                                </div> -->
                                <div class="form-btn col-12 mt-24"><button type="submit" class="th-btn style3">Register now
                                        <img src="{{asset('website') }}/assets/img/icon/plane.svg" alt=""></button>
                                </div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div><!--==============================
Map Area
==============================-->
    <!-- <div class="">
        <div class="container-fluid">
            <div class="contact-map style2">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sAngfuztheme!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd" allowfullscreen="" loading="lazy"></iframe>
                <div class="contact-icon">
                    <img src="{{asset('website') }}/assets/img/icon/location-dot3.svg" alt="">
                </div>
            </div>
        </div>
    </div> -->
@endsection