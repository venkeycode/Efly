@extends('layouts.website')
@section('content')
 <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Popular Tours</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>Popular Tours</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
Team Area
==============================-->
    <!--==============================
Product Area
==============================-->
    <section class="space">
        <div class="container">
            <div class="th-sort-bar">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md-4">
                        <div class="search-form-area">
                            <form class="search-form">
                                <input type="text" placeholder="Search">
                                <button type="submit"><i class="fa-light fa-magnifying-glass"></i></button>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-auto">
                        <div class="sorting-filter-wrap">
                            <div class="nav" role="tablist">
                                <a class="active" href="#" id="tab-destination-grid" data-bs-toggle="tab" data-bs-target="#tab-grid" role="tab" aria-controls="tab-grid" aria-selected="true"><i class="fa-light fa-grid-2"></i></a>

                                <a href="#" id="tab-destination-list" data-bs-toggle="tab" data-bs-target="#tab-list" role="tab" aria-controls="tab-list" aria-selected="false" class=""><i class="fa-solid fa-list"></i></a>
                            </div>
                            <form class="woocommerce-ordering" method="get">
                                <select name="orderby" class="orderby" aria-label="destination order">
                                    <option value="menu_order" selected="selected">Default Sorting</option>
                                    <option value="popularity">Sort by popularity</option>
                                    <option value="rating">Sort by average rating</option>
                                    <option value="date">Sort by latest</option>
                                    <option value="price">Sort by price: low to high</option>
                                    <option value="price-desc">Sort by price: high to low</option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xxl-8 col-lg-7">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-tour-grid">
                            <div class="row gy-24 gx-24">
                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_1.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Greece Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_2.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Dubai Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_3.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Belgium Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_4.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Greece Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_5.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Maldives Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_6.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Paris Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_7.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Switzerland Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="tour-box th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_8.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Bali Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            </div>
                        </div>

                        <div class="tab-pane fade " id="tab-list" role="tabpanel" aria-labelledby="tab-tour-list">
                            <div class="row gy-30">
                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_1.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Greece Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_2.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Dubai Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_3.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Belgium Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_4.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Greece Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_5.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Maldives Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_6.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Paris Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_7.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Switzerland Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="tour-box style-flex th-ani">
                                        <div class="tour-box_img global-img">
                                            <img src="assets/img/tour/tour_4_8.jpg" alt="image">
                                        </div>
                                        <div class="tour-content">
                                            <h3 class="box-title"><a href="tour-guider-details.html">Bali Tour Package</a></h3>
                                            <div class="tour-rating">
                                                <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated
                                                        <strong class="rating">5.00</strong> out of 5 based on <span class="rating">4.8</span>(4.8
                                                        Rating)</span></div>
                                                <a href="tour-guider-details.html" class="woocommerce-review-link">(<span class="count">4.8</span>
                                                    Rating)</a>
                                            </div>
                                            <h4 class="tour-box_price"><span class="currency">$980.00</span>/Person</h4>
                                            <div class="tour-action">
                                                <span><i class="fa-light fa-clock"></i>7 Days</span>
                                                <a href="tour-guider-details.html" class="th-btn style4">Detail View</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="th-pagination text-center mt-60">
                            <ul>
                                <li><a class="active" href="blog.html">1</a></li>
                                <li><a href="blog.html">2</a></li>
                                <li><a href="blog.html">3</a></li>
                                <li><a href="blog.html">4</a></li>
                                <li><a class="next-page" href="blog.html">Next <img src="assets/img/icon/arrow-right4.svg" alt=""></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-4 col-lg-5">
                    <aside class="sidebar-area">
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
                </div>

            </div>

        </div>
        <div class="shape-mockup shape1 d-none d-xxl-block" data-bottom="7%" data-right="8%">
            <img src="assets/img/shape/shape_1.png" alt="shape">
        </div>
        <div class="shape-mockup shape2 d-none d-xl-block" data-bottom="1%" data-right="7%">
            <img src="assets/img/shape/shape_2.png" alt="shape">
        </div>
        <div class="shape-mockup shape3 d-none d-xxl-block" data-bottom="2%" data-right="4%">
            <img src="assets/img/shape/shape_3.png" alt="shape">
        </div>
    </section>
 @endsection
