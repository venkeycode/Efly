<!doctype html>
<html class="no-js" lang="zxx">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>{{$site->site_name}}</title>
    <meta name="author" content="Agamana">
    <meta name="description" content="Agamana - Resort">
    <meta name="keywords" content="Agamana - Resort">
    <meta name="robots" content="INDEX,FOLLOW">

    <!-- Mobile Specific Metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Favicons - Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="57x57" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="60x60" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="72x72" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="76x76" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="114x114" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="120x120" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="144x144" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="152x152" href="{{asset($site->favicon) }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{asset($site->favicon) }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{asset($site->favicon) }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{asset($site->favicon) }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{asset($site->favicon) }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{asset($site->favicon) }}">
    <link rel="manifest" href="{{asset('website') }}/assets/img/favicons/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="{{asset($site->favicon) }}">
    <meta name="theme-color" content="#ffffff">

    <!--==============================
	  Google Fonts
	============================== -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&family=Manrope:wght@200..800&family=Montez&display=swap" rel="stylesheet">

    <!--==============================
	    All CSS File
	============================== -->
    <!-- Bootstrap -->
    <link rel="stylesheet" href="{{asset('website') }}/assets/css/bootstrap.min.css">
    <!-- Fontawesome Icon -->
    <link rel="stylesheet" href="{{asset('website') }}/assets/css/fontawesome.min.css">
    <!-- Magnific Popup -->
    <link rel="stylesheet" href="{{asset('website') }}/assets/css/magnific-popup.min.css">

    <!-- Swiper css -->
    <link rel="stylesheet" href="{{asset('website') }}/assets/css/swiper-bundle.min.css">
    <!-- Theme Custom CSS -->
    <link rel="stylesheet" href="{{asset('website') }}/assets/css/style.css">

</head>
<style>
    .breadcumb-wrapper{
        padding-bottom: 60px;
    }
    </style>
<body>

    <!--[if lte IE 9]>
    	<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="https://browsehappy.com/">upgrade your browser</a> to improve your experience and security.</p>
  	<![endif]-->


    <!--********************************
   		Code Start From Here
	******************************** -->

    <div class="magic-cursor relative z-10">
        <div class="cursor"></div>
        <div class="cursor-follower"></div>
    </div>


    <!--==============================
     Preloader
  ==============================-->
    <div id="preloader" class="preloader ">
        {{-- <button class="th-btn preloaderCls">Cancel Preloader </button> --}}
        <div class="preloader-inner">
            <img src="{{$site->logo}}" alt="">
        </div>

        <div id="loader" class="th-preloader">
            <div class="animation-preloader">
                <div class="txt-loading">
                    <span preloader-text="A" class="characters">A </span>

                    <span preloader-text="G" class="characters">G </span>

                    <span preloader-text="A" class="characters">A </span>

                    <span preloader-text="M" class="characters">M </span>

                    <span preloader-text="A" class="characters">A </span>

                    <span preloader-text="N" class="characters">N </span>

                    <span preloader-text="A" class="characters">A </span>

                </div>
            </div>
        </div>

    </div> <!--==============================
    Sidemenu
============================== -->
    <div class="sidemenu-wrapper sidemenu-info ">
        <div class="sidemenu-content">
            <button class="closeButton sideMenuCls"><i class="far fa-times"></i></button>
            <div class="widget  ">
                <div class="th-widget-about">
                    <div class="about-logo">
                        <a href="home-travel.html"><img src="{{asset($site->site_logo) }}" alt="Agamana"></a>
                    </div>
                    <p class="about-text">Rapidiously myocardinate cross-platform intellectual capital model. Appropriately create interactive infrastructures</p>
                    <div class="th-social">
                        <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                        <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                        <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                        <a href="https://www.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h3 class="widget_title">Recent Posts</h3>
                <div class="recent-post-wrap">
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="{{asset('website') }}/assets/img/blog/recent-post-1-1.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="far fa-calendar"></i>24 Jun , 2024</a>
                            </div>
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Where Vision Meets Concrete
                                    Reality</a></h4>
                        </div>
                    </div>
                    <div class="recent-post">
                        <div class="media-img">
                            <a href="blog-details.html"><img src="{{asset('website') }}/assets/img/blog/recent-post-1-2.jpg" alt="Blog Image"></a>
                        </div>
                        <div class="media-body">
                            <div class="recent-post-meta">
                                <a href="blog.html"><i class="far fa-calendar"></i>22 Jun , 2024</a>
                            </div>
                            <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Raising the Bar in Construction.</a></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="widget">
                <h3 class="widget_title">Get In Touch</h3>
                <div class="th-widget-contact">
                    <div class="info-box_text">
                        <div class="icon">
                            <img src="{{asset('website') }}/assets/img/icon/phone.svg" alt="img">
                        </div>
                        <div class="details">
                            <p><a href="tel:+01234567890" class="info-box_link">{{$site->phone}}</a></p>

                        </div>
                    </div>
                    <div class="info-box_text">
                        <div class="icon">
                            <img src="{{asset('website') }}/assets/img/icon/envelope.svg" alt="img">
                        </div>
                        <div class="details">
                            <p><a href="mailto:mailinfo00@Agamana.com" class="info-box_link">{{$site->email}}</a></p>

                        </div>
                    </div>
                    <div class="info-box_text">
                        <div class="icon"><img src="{{asset('website') }}/assets/img/icon/location-dot.svg" alt="img"></div>
                        <div class="details">
                            <p>{{$site->address}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="popup-search-box">
        <button class="searchClose"><i class="fal fa-times"></i></button>
        <form action="#">
            <input type="text" placeholder="What are you looking for?">
            <button type="submit"><i class="fal fa-search"></i></button>
        </form>
    </div><!--==============================
    Mobile Menu
  ============================== -->
    <div class="th-menu-wrapper onepage-nav">
        <div class="th-menu-area text-center">
            <button class="th-menu-toggle"><i class="fal fa-times"></i></button>
            <div class="mobile-logo">
                <a href="home-travel.html"><img src="" alt="Agamana"></a>
            </div>
            <div class="th-mobile-menu">
                <ul>
                    <li>
                        <a class="active" href="/">Home</a>
                        <!-- {{-- <ul class="sub-menu">
                            <li><a href="home-travel.html">Home Travel</a></li>
                            <li><a href="home-tour.html">Home Tour</a></li>
                            <li><a href="home-agency.html">Home Agency</a></li>
                        </ul> --}} -->
                    </li>
                    <li><a href="{{route('about')}}">About Us</a></li>
                    <li><a href="{{route('accomodation')}}">Accomodation</a></li>
                    <!-- <li class="menu-item-has-children">
                        <a href="{{route('destination')}}">Destination</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('destination')}}">Destination</a></li>
                            <li><a href="{{route('destination-details')}}">Destination Details</a></li>
                        </ul>
                    </li> -->
                    <!-- <li class="menu-item-has-children">
                        <a href="{{route('service')}}">Service</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('service')}}">Services</a></li>
                            <li><a href="{{route('service-details')}}">Service Details</a></li>
                        </ul>
                    </li> -->
                    <!-- <li class="menu-item-has-children">
                        <a href="{{route('activities')}}">Activities</a>
                        <ul class="sub-menu">
                            <li><a href="{{route('activities')}}">activities</a></li>
                            <li><a href="{{route('activities-details')}}">activities Details</a></li>
                        </ul>
                    </li> -->
                    <li class="menu-item-has-children">
                        <a href="{{route('gallery')}}">gallery</a>
                        <ul class="sub-menu">
                            <!-- <li class="menu-item-has-children">
                                <a href="{{route('shop')}}">Shop</a>
                                <ul class="sub-menu">
                                    <li><a href="{{route('shop')}}">Shop</a></li>
                                    <li><a href="{{route('shop-details')}}">Shop Details</a></li>
                                    <li><a href="{{route('cart')}}">Cart Page</a></li>
                                    <li><a href="{{route('checkout')}}">Checkout</a></li>
                                    <li><a href="{{route('wishlist')}}">Wishlist</a></li>
                                </ul>
                            </li> -->

                            <!-- <li><a href="{{route('gallery')}}">Gallery</a></li>
                            <li><a href="{{route('tour')}}">Our Tour</a></li>
                            <li><a href="{{route('tour-details')}}">Tour Details</a></li>
                            <li><a href="{{route('tour-guide')}}">Tour Guider</a></li>
                            <li><a href="{{route('tour-guider-details')}}">Tour Guider Details</a></li>
                            <li><a href="{{route('tour-details')}}">Faq Page</a></li>
                            <li><a href="{{route('price')}}">Price Package</a></li>
                            <li><a href="{{route('error')}}">Error Page</a></li> -->
                        </ul>

                    </li>
                    <li class="menu-item-has-children">
                        <a href="{{route('blog')}}">Blog</a>
                        <!-- <ul class="sub-menu">
                            <li><a href="{{route('blog')}}">Blog</a></li>
                            <li><a href="{{route('blog-details')}}">Blog Details</a></li>
                        </ul> -->
                    </li>
                    <li>
                        <a href="{{route('contact')}}">Contact us</a>
                    </li>
                </ul>

            </div>
        </div>
    </div><!--==============================
	Header Area
==============================-->
    <header class="th-header header-layout3 header-absolute">
        <div class="sticky-wrapper">
            <!-- Main Menu Area -->
            <div class="menu-area">
                <div class="container">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-3">
                            <div class="header-logo">
                                <a href="/">
                                    <img src="{{asset($site->site_logo) }}" alt="Agamana" style="max-width: 70%;">
                                </a>
                            </div>
                        </div>
                        <div class="col-9">
                            <nav class="main-menu d-none d-xl-block">
                                <ul>
                                    <li >
                                        <a class="active" href="/">Home</a>

                                    </li>
                                    <li><a href="{{route('about')}}">About Us</a></li>

                                    <li><a href="{{route('accomodation')}}">Accomodation </a></li>
                                    <!-- <li class="menu-item-has-children">
                                        <a href="{{route('destination')}}">Destination</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('destination')}}">Destination</a></li>
                                            <li><a href="{{route('destination-details')}}">Destination Details</a></li>
                                        </ul>
                                    </li> -->
                                    <!-- <li class="menu-item-has-children">
                                        <a href="{{route('service')}}">Service</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{route('service')}}">Services</a></li>
                                            <li><a href="{{route('service-details')}}">Service Details</a></li>
                                        </ul>
                                    </li> -->
                                <!-- </ul>
                            </nav>
                        </div> -->
                      
                        <!-- <div class="col-auto">
                            <nav class="main-menu d-none d-xl-block">
                                <ul> -->
                                    <li class="">
                                        <a href="{{route('gallery')}}">gallery</a>
                                        <!-- <ul class="sub-menu">
                                            <li><a href="{{route('activities')}}">activities</a></li>
                                            <li><a href="{{route('activities-details')}}">activities Details</a></li>
                                        </ul> -->
                                    </li>
                                    <li>
                                        <a href="{{route('blog')}}">Blog</a>

                                    </li>
                                    <li>
                                        <a href="{{route('contact')}}">Contact us</a>
                                    </li>
                                </ul>
                            </nav>
                                    <!-- <li class="menu-item-has-children">
                                        <a href="#">Pages</a>
                                        <ul class="sub-menu">
                                            <li class="menu-item-has-children">
                                                <a href="{{route('shop')}}">Shop</a>
                                                <ul class="sub-menu">
                                                    <li><a href="{{route('shop')}}">Shop</a></li>
                                                    <li><a href="{{route('shop-details')}}">Shop Details</a></li>
                                                    <li><a href="{{route('cart')}}">Cart Page</a></li>
                                                    <li><a href="{{route('checkout')}}">Checkout</a></li>
                                                    <li><a href="{{route('wishlist')}}">Wishlist</a></li>
                                                </ul>
                                            </li>

                                            <li><a href="{{route('gallery')}}">Gallery</a></li>
                                            <li><a href="{{route('tour')}}">Our Tour</a></li>
                                            <li><a href="{{route('tour-details')}}">Tour Details</a></li>
                                            <li><a href="{{route('tour-guide')}}">Tour Guider</a></li>
                                            <li><a href="{{route('tour-guider-details')}}">Tour Guider Details</a></li>
                                            <li><a href="{{route('faq')}}">Faq Page</a></li>
                                            <li><a href="{{route('price')}}">Price Package</a></li>
                                            <li><a href="{{route('error')}}">Error Page</a></li>
                                        </ul>
                                    </li> -->
                          

                            <button type="button" class="th-menu-toggle d-block d-xl-none"><i class="far fa-bars"></i></button>
                        </div>

                     <div class="header-right-button">
                     <a href="#" class="simple-btn sideMenuToggler"><img src="{{asset('website') }}/assets/img/icon/menu.svg" alt=""></a>
                     </div>
                    </div>
                </div>
            </div>
        </div>
    </header>
@yield('content')
 <!--==============================
	Footer Area
 ==============================-->
    <footer class="footer-wrapper bg-title footer-layout2">
        <div class="widget-area">
            <div class="container">
                <div class="newsletter-area">
                    <div class="newsletter-top">
                        <div class="row gy-4 align-items-center">
                            <div class="col-lg-5">
                                <h2 class="newsletter-title text-white text-capitalize mb-0">get updated the latest
                                    newsletter</h2>
                            </div>
                            <div class="col-lg-7">
                                <form class="newsletter-form style2" action="{{route('newsletter')}}" method="POST">
                                    <input class="form-control " type="email" placeholder="Enter Email" required="">
                                    <button type="submit" class="th-btn style1">Subscribe Now <img src="{{asset('website') }}/assets/img/icon/plane2.svg" alt=""></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-between">
                    <div class="col-md-6 col-xl-3">
                        <div class="widget footer-widget">
                            <div class="th-widget-about">
                                <div class="about-logo">
                                    <a href="/"><img src="{{($site->site_logo)}}" alt="Agamana"></a>
                                </div>
                                <p class="about-text">Welcome to Agamana Stays — Your Escape into Nature, Comfort, and Adventure.</p>
                                <div class="th-social">
                                    <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                    <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                                    <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                    <a href="https://www.whatsapp.com/"><i class="fab fa-whatsapp"></i></a>
                                    <a href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-auto">
                        <div class="widget widget_nav_menu footer-widget">
                            <h3 class="widget_title">Quick Links</h3>
                            <div class="menu-all-pages-container">
                                <ul class="menu">

                                    <li><a href="/">Home</a></li>
                                    <li><a href="{{route('about')}}">About us</a></li>

                                    <li><a href="{{route('gallery')}}">Gallery</a></li>
                                    <li><a href="{{route('contact')}}">Contact us</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-auto">
                        <div class="widget widget_nav_menu footer-widget">
                            <h3 class="widget_title">Quick Links</h3>
                            <div class="menu-all-pages-container">
                                <ul class="menu">

                                    <!-- <li><a href="/">Home</a></li> -->
                                    <li><a href="{{route('TermsandConditions')}}">Terms and Conditions</a></li>
                                    <li><a href="{{route('faq')}}">FAQS</a></li>
                                    <li><a href="{{route('PrivacyPolicy')}}">Privacy Policy</a></li>
                                    <li><a href="{{route('RefundPolicy')}}">Refund Policy</a></li>
                                    <!-- <li><a href="contact.html">Tour Booking Now</a></li> -->
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-auto">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">Get In Touch</h3>
                            <div class="th-widget-contact">
                                <div class="info-box_text">
                                    <div class="icon">
                                        <img src="{{asset('website') }}/assets/img/icon/phone.svg" alt="img">
                                    </div>
                                    <div class="details">
                                        <p><a href="tel:+01234567890" class="info-box_link">{{$site->phone}}</a></p>

                                    </div>
                                </div>
                                <div class="info-box_text">
                                    <div class="icon">
                                        <img src="{{asset('website') }}/assets/img/icon/envelope.svg" alt="img">
                                    </div>
                                    <div class="details">
                                        <p><a href="mailto:mailinfo00@Agamana.com" class="info-box_link">{{$site->email}}</a></p>

                                    </div>
                                </div>
                                <div class="info-box_text">
                                    <div class="icon"><img src="{{asset('website') }}/assets/img/icon/location-dot.svg" alt="img"></div>
                                    <div class="details">
                                        <p>Sree Abhi Prani nature stay, RWRM+H3, Neragalale, Magge, Alur (Tq), Hassan, Karnataka 573129</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- <div class="col-md-6 col-xl-auto">
                        <div class="widget footer-widget">
                            <h3 class="widget_title">Instagram Post</h3>
                            <div class="sidebar-gallery">
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_1.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_2.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_3.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_4.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_5.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                                <div class="gallery-thumb">
                                    <img src="{{asset('website') }}/assets/img/widget/gallery_1_6.jpg" alt="Gallery Image">
                                    <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
            </div>
        </div>
        <div class="copyright-wrap">
            <div class="container">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-6">
                        <p class="copyright-text">Copyright 2024 <a href="/">Agamana</a>. All Rights Reserved.</p>
                    </div>
                    <div class="col-md-6 text-end d-none d-md-block">
                        <div class="footer-card">
                            <span class="title">We Accept</span>
                            <img src="{{asset('website') }}/assets/img/shape/cards.png" alt="">
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="shape-mockup movingX d-none d-xxl-block" data-top="24%" data-left="5%">
            <img src="{{asset('website') }}/assets/img/shape/shape_8.png" alt="shape">
        </div>
    </footer>

    <!--********************************
			Code End  Here
	******************************** -->

    <!-- Scroll To Top -->
    <div class="scroll-top">
        <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
            <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 307.919;">
            </path>
        </svg>
    </div>
<!--==============================
modal Area
==============================-->
    <div id="login-form" class="popup-login-register mfp-hide">
        <ul class="nav" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-menu" id="pills-home-tab" data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home" aria-selected="false">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-menu active" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile" type="button" role="tab" aria-controls="pills-profile" aria-selected="true">Register</button>
            </li>
        </ul>
        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <h3 class="box-title mb-30">Sign in to your account</h3>
                <div class="th-login-form">
                    <form action="mail.php" method="POST" class="login-form ">
                        <div class="row">
                            <div class="form-group col-12">
                                <label>Username or email</label>
                                <input type="text" class="form-control" name="email" id="email" required="required">
                            </div>
                            <div class="form-group col-12">
                                <label>Password</label>
                                <input type="password" class="form-control" name="pasword" id="pasword" required="required">
                            </div>

                            <div class="form-btn mb-20 col-12">
                                <button class="th-btn btn-fw th-radius2 ">Send Message</button>
                            </div>
                        </div>
                        <div id="forgot_url">
                            <a href="my-account.html">Forgot password?</a>
                        </div>
                        <p class="form-messages mb-0 mt-3"></p>
                    </form>
                </div>
            </div>
            <div class="tab-pane fade active show" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <h3 class="th-form-title mb-30">Sign in to your account</h3>
                <form action="mail.php" method="POST" class="login-form ajax-contact">
                    <div class="row">
                        <div class="form-group col-12">
                            <label>Username*</label>
                            <input type="text" class="form-control" name="usename" id="usename" required="required">
                        </div>
                        <div class="form-group col-12">
                            <label>First name*</label>
                            <input type="text" class="form-control" name="firstname" id="firstname" required="required">
                        </div>
                        <div class="form-group col-12">
                            <label>Last name*</label>
                            <input type="text" class="form-control" name="lastname" id="lastname" required="required">
                        </div>
                        <div class="form-group col-12">
                            <label for="new_email">Your email*</label>
                            <input type="text" class="form-control" name="new_email" id="new_email" required="required">
                        </div>
                        <div class="form-group col-12">
                            <label for="new_email_confirm">Confirm email*</label>
                            <input type="text" class="form-control" name="new_email_confirm" id="new_email_confirm" required="required">
                        </div>
                        <div class="statement">
                            <span class="register-notes">A password will be emailed to you.</span>
                        </div>

                        <div class="form-btn mt-20 col-12">
                            <button class="th-btn btn-fw th-radius2 ">Sign up</button>
                        </div>
                    </div>
                    <p class="form-messages mb-0 mt-3"></p>
                </form>
            </div>
        </div>
    </div>

    <!--==============================
    All Js File
============================== -->
    <!-- Jquery -->
    @include('sweetalert::alert')

    <!-- Jquery -->
    <script src="{{asset('website') }}/assets/js/vendor/jquery-3.6.0.min.js"></script>
    <!-- Swiper Js -->
    <script src="{{asset('website') }}/assets/js/swiper-bundle.min.js"></script>
    <!-- Bootstrap -->
    <script src="{{asset('website') }}/assets/js/bootstrap.min.js"></script>
    <!-- Magnific Popup -->
    <script src="{{asset('website') }}/assets/js/jquery.magnific-popup.min.js"></script>
    <!-- Counter Up -->
    <script src="{{asset('website') }}/assets/js/jquery.counterup.min.js"></script>
    <!-- Range Slider -->
    <script src="{{asset('website') }}/assets/js/jquery-ui.min.js"></script>
    <!-- imagesloaded -->
    <script src="{{asset('website') }}/assets/js/imagesloaded.pkgd.min.js"></script>
    <!-- isotope -->
    <script src="{{asset('website') }}/assets/js/isotope.pkgd.min.js"></script>
    <!-- gsap -->
    <script src="{{asset('website') }}/assets/js/gsap.min.js"></script>

    <!-- circle-progress -->
    <script src="{{asset('website') }}/assets/js/circle-progress.js"></script>

    <script src="{{asset('website') }}/assets/js/matter.min.js"></script>
    <script src="{{asset('website') }}/assets/js/matterjs-custom.js"></script>


    <!-- nice select -->
    <script src="{{asset('website') }}/assets/js/nice-select.min.js"></script>




    <!-- Main Js File -->
    <script src="{{asset('website') }}/assets/js/main.js"></script>
</body>

</html>
