@extends('layouts.website')
@section('content')
 <!--==============================
    Breadcumb
============================== -->
    <div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">{{$blog->title}}</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>{{$blog->title}}</li>
                </ul>
            </div>
        </div>
    </div><!--==============================
        Blog Area
    ==============================-->
    <section class="th-blog-wrapper blog-details space-top space-extra-bottom">
        <div class="container">
            <div class="row">
                <div class="col-xxl-12 col-lg-12">
                    <div class="th-blog blog-single">
                        <div class="blog-img">
                            <img src="{{asset($blog->image)}}" alt="Blog Image">
                        </div>
                        <div class="blog-content">
                            <div class="blog-meta">
                                <a class="author" href="blog.html"><i class="fa-light fa-user"></i>by Agamana</a>
                                <a href="blog.html"><i class="fa-regular fa-calendar"></i>05 May,
                                    2024</a>
                                {{-- <a href="blog-details.html"><img src="assets/img/icon/map.svg" alt="">Sea Beach</a> --}}
                            </div>
                            {!! $blog->description !!}


                            <div class="share-links clearfix ">
                                <div class="row justify-content-between">
                                    <div class="col-md-auto">
                                        {{-- <span class="share-links-title">Tags:</span> --}}
                                        <div class="tagcloud">
                                            {{-- {{$blog->tags}} --}}
                                        </div>
                                    </div>
                                    <div class="col-md-auto text-xl-end">
                                        <div class="share-links_wrapp">
                                            <span class="share-links-title">Share:</span>
                                            <div class="social-links">
                                                <a href="https://www.facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                                <a href="https://www.twitter.com/"><i class="fab fa-twitter"></i></a>
                                                <a href="https://www.instagram.com/"><i class="fab fa-instagram"></i></a>
                                                <a href="https://www.linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            </div>
                                        </div>
                                    </div><!-- Share Links Area end -->
                                </div>
                            </div>
                        </div>


                    </div>
                    {{-- <div class="th-comments-wrap ">
                        <h2 class="blog-inner-title h4"> Comments (03)</h2>
                        <ul class="comment-list">
                            <li class="th-comment-item">
                                <div class="th-post-comment">
                                    <div class="comment-avater">
                                        <img src="assets/img/blog/comment-author-1.jpg" alt="Comment Author">
                                    </div>
                                    <div class="comment-content">
                                        <h3 class="name">Adam Jhon</h3>
                                        <span class="commented-on">20Jun, 2024 08:56pm</span>
                                        <p class="text">Credibly pontificate transparent quality vectors with quality mindshare. Efficiently
                                            architect worldwide strategic theme areas after user.</p>
                                        <div class="reply_and_edit">
                                            <a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                                        </div>
                                    </div>
                                </div>
                                <ul class="children">
                                    <li class="th-comment-item">
                                        <div class="th-post-comment">
                                            <div class="comment-avater">
                                                <img src="assets/img/blog/comment-author-2.jpg" alt="Comment Author">
                                            </div>
                                            <div class="comment-content">
                                                <div class="">
                                                    <h3 class="name">Jhon Abraham</h3>
                                                    <span class="commented-on">25Jun, 2024 08:56pm</span>
                                                </div>
                                                <p class="text">It is different from airport transfer or port transfer, which are services
                                                    that pick you up</p>
                                                <div class="reply_and_edit">
                                                    <a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li class="th-comment-item">
                                <div class="th-post-comment">
                                    <div class="comment-avater">
                                        <img src="assets/img/blog/comment-author-3.jpg" alt="Comment Author">
                                    </div>
                                    <div class="comment-content">
                                        <div class="">
                                            <h3 class="name">Anadi Juila</h3>
                                            <span class="commented-on">27Jun, 2024 08:56pm</span>
                                        </div>
                                        <p class="text">Credibly pontificate transparent quality vectors with quality mindshare. Efficiently
                                            architect worldwide strategic theme areas after user.</p>
                                        <div class="reply_and_edit">
                                            <a href="blog-details.html" class="reply-btn"><i class="fas fa-reply"></i>Reply</a>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                     <!-- Comment end --> <!-- Comment Form -->
                    <div class="th-comment-form ">
                        <div class="row">
                            <h3 class="blog-inner-title h4 mb-2">Leave a Reply</h3>
                            <p class="mb-25">Your email address will not be published. Required fields are marked</p>
                            <div class="col-md-6 form-group">
                                <input type="text" placeholder="Full Name*" class="form-control" required>
                                <i class="far fa-user"></i>
                            </div>
                            <div class="col-md-6 form-group">
                                <input type="text" placeholder="Your Email*" class="form-control" required>
                                <i class="far fa-envelope"></i>
                            </div>
                            <div class="col-12 form-group">
                                <input type="text" placeholder="Website" class="form-control" required>
                                <i class="far fa-globe"></i>
                            </div>
                            <div class="col-12 form-group">
                                <textarea placeholder="Comment*" class="form-control"></textarea>
                                <i class="far fa-pencil"></i>
                            </div>
                            <div class="col-12 form-group">
                                <input type="checkbox" id="html">
                                <label for="html">Save my name, email, and website in this browser for the next time I
                                    comment.</label>
                            </div>
                            <div class="col-12 form-group mb-0">
                                <button class="th-btn">Send Message<img src="assets/img/icon/plane2.svg" alt=""></button>
                            </div>
                        </div>
                    </div> --}}
                </div>
                {{-- <div class="col-xxl-4 col-lg-5">
                    <aside class="sidebar-area">
                        <div class="widget widget_search  ">
                            <form class="search-form">
                                <input type="text" placeholder="Search">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div>
                        <div class="widget widget_categories  ">
                            <h3 class="widget_title">Categories</h3>
                            <ul>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">City Tour</a>
                                    <span>(8)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">Beach Tours</a>
                                    <span>(6)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">Wildlife Tours</a>
                                    <span>(2)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">News & Tips</a>
                                    <span>(7)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">Adventure Tours</a>
                                    <span>(9)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="assets/img/theme-img/map.svg" alt="">Mountain Tours</a>
                                    <span>(10)</span>
                                </li>
                            </ul>
                        </div>
                        <div class="widget  ">
                            <h3 class="widget_title">Recent Posts</h3>
                            <div class="recent-post-wrap">
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.html"><img src="assets/img/blog/recent-post-1-1.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Exploring The Green Spaces Of the island maldives</a></h4>
                                        <div class="recent-post-meta">
                                            <a href="blog.html"><i class="fa-regular fa-calendar"></i>22/6/ 2024</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.html"><img src="assets/img/blog/recent-post-1-2.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Harmony With Nature Of Belgium Tour and travle</a></h4>
                                        <div class="recent-post-meta">
                                            <a href="blog.html"><i class="fa-regular fa-calendar"></i>25/6/ 2024</a>
                                        </div>

                                    </div>
                                </div>
                                <div class="recent-post">
                                    <div class="media-img">
                                        <a href="blog-details.html"><img src="assets/img/blog/recent-post-1-3.jpg" alt="Blog Image"></a>
                                    </div>
                                    <div class="media-body">
                                        <h4 class="post-title"><a class="text-inherit" href="blog-details.html">Exploring The Green Spaces Of Realar Residence</a></h4>
                                        <div class="recent-post-meta">
                                            <a href="blog.html"><i class="fa-regular fa-calendar"></i>27/6/ 2024</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="widget widget_tag_cloud  ">
                            <h3 class="widget_title">Popular Tags</h3>
                            <div class="tagcloud">
                                <a href="blog.html">Tour</a>
                                <a href="blog.html">Adventure</a>
                                <a href="blog.html">Rent</a>
                                <a href="blog.html">Innovate</a>
                                <a href="blog.html">Hotel</a>
                                <a href="blog.html">Modern</a>
                                <a href="blog.html">Luxury</a>
                                <a href="blog.html">Travel</a>
                            </div>
                        </div>
                        <div class="widget widget_offer  " data-bg-src="assets/img/bg/widget_bg_1.jpg">
                            <div class="offer-banner">
                                <div class="offer">
                                    <h6 class="box-title">Need Help? We Are Here To Help You</h6>
                                    <div class="banner-logo">
                                        <img src="assets/img/logo2.svg" alt="Agamana">
                                    </div>
                                    <div class="offer">
                                        <h6 class="offer-title">You Get Online support</h6>
                                        <a class="offter-num" href="+256214203215">+256 214 203 215</a>
                                    </div>
                                    <a href="contact.html" class="th-btn style2 th-icon">Read More</a>
                                </div>
                            </div>
                        </div>
                    </aside>
                </div> --}}
            </div>
        </div>
        <div class="shape-mockup shape1 d-none d-xxl-block" data-bottom="5%" data-right="8%">
            <img src="{{asset('website') }}/assets/img/shape/shape_1.png" alt="shape">
        </div>
        <div class="shape-mockup shape2 d-none d-xl-block" data-bottom="1%" data-right="7%">
            <img src="{{asset('website') }}/assets/img/shape/shape_2.png" alt="shape">
        </div>
        <div class="shape-mockup shape3 d-none d-xxl-block" data-bottom="2%" data-right="4%">
            <img src="{{asset('website') }}/assets/img/shape/shape_3.png" alt="shape">
        </div>
    </section>

@endsection
