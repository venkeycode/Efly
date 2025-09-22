@extends('layouts.website')
@section('content')
  <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Our Gallery</h1>
                <ul class="breadcumb-menu">
                    <li><a href="/">Home</a></li>
                    <li>Our Gallery</li>
                </ul>
            </div>
        </div>
    </div><!--==============================
Gallery Area
==============================-->
    <div class="overflow-hidden space" id="gallery-sec">
        <div class="container">
            <div class="filter-menu filter-menu-active">
                <button data-filter="*" class="tab-btn active" type="button">All</button>
                <div class="dropdown">
                  <!-- <button data-filter=".cat1"class="tab-btn" type="button">Resort</button>
                   -->
                   <div class="dropdown-content">
                    <button class="tab-btn" type="button" onclick="showIndoor()">Indoor</button>
                    <button class="tab-btn" type="button" onclick="showOutdoor()">Outdoor</button>
                  </div>
                </div>
                <button data-filter=".cat3" class="tab-btn" type="button">Rooms</button>
                <button data-filter=".cat4" class="tab-btn" type="button">Games</button>
                <!-- <button data-filter=".cat4" class="tab-btn" type="button">Outdoor</button> -->
                <!-- <button data-filter=".cat5" class="tab-btn" type="button">Sydney</button>
                <button data-filter=".cat6" class="tab-btn" type="button">Dubai, UAE</button>
                <button data-filter=".cat7" class="tab-btn" type="button">Japan</button>
                <button data-filter=".cat8" class="tab-btn" type="button">Brazil</button> -->
            </div>
            <div class="row gy-4 gallery-row filter-active">
                <div class="col-md-6 col-xl-auto filter-item cat1  ">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat1">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-2.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-2.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat1">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-3.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-3.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-4.png" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-4.png" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat1  ">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-5.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-5.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat1">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-6.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-6.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-7.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-7.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item cat10 cat5">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-8.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-8.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item <br />
                <b>Warning</b>:  Undefined array key 8 in <b>E:\web-dev\angfuzsoft\html\Agamana-html\build\inc\sections\gallery-v4.php</b> on line <b>50</b><br /> ">

                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/gallery-7-9.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/gallery-7-9.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3 ">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/badminton-1-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/badminton-1-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/carrom-1-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/carrom-1-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Chess-1-1.png" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Chess-1-1.png" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/cricket-1-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/cricket-1-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Games-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Games-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Ludo-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Ludo-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat3">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/TT.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/TT.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Room-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Room-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Pool-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Pool-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Outdoor-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Outdoor-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/jucuzzi-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/jucuzzi-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Bathroom-1.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Bathroom-1.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-auto filter-item  cat2">
                    <div class="gallery-box style4">
                        <div class="gallery-img global-img">
                            <img src="{{asset('website') }}/assets/img/gallery/Bathroom-2.jpg" alt="gallery image">
                            <a href="{{asset('website') }}/assets/img/gallery/Bathroom-2.jpg" class="icon-btn popup-image"><i class="fal fa-magnifying-glass-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
