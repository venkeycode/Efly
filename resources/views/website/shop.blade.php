@extends('layouts.website')
@section('content')
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Shops</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>Shops</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
Product Area
==============================-->
    <section class="space-top space-extra-bottom">
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
            <div class="row gy-40">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade active show" id="tab-grid" role="tabpanel" aria-labelledby="tab-shop-grid">
                        <div class="row gy-40">

                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_1.png" alt="Product Image">
                                        <span class="product-tag">Sale</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">beach casual-shoe</a></h3>
                                        <span class="price">$35.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_2.png" alt="Product Image">
                                        <span class="product-tag">Sale</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Beach football</a></h3>
                                        <span class="price">$45.00<del>$60.00</del></span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_3.png" alt="Product Image">
                                        <span class="product-tag">New</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Hamok</a></h3>
                                        <span class="price">$20.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_4.png" alt="Product Image">
                                        <span class="product-tag">Sale</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Beach Hat</a></h3>
                                        <span class="price">$40.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_5.png" alt="Product Image">
                                        <span class="product-tag">New</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Sony Black Headphone</a></h3>
                                        <span class="price">$65.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_6.png" alt="Product Image">
                                        <span class="product-tag">Sale</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Hoodie With Zipper</a></h3>
                                        <span class="price">$35.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_7.png" alt="Product Image">
                                        <span class="product-tag">New</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">Trendy Sunglass</a></h3>
                                        <span class="price">$25.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-xl-3 col-sm-6">
                                <div class="th-product product-grid">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_8.png" alt="Product Image">
                                        <span class="product-tag">Sale</span>
                                        <div class="actions">
                                            <a href="#QuickView" class="icon-btn popup-content"><i class="far fa-eye"></i></a>
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <h3 class="product-title"><a href="shop-details.html">V-Neck T-Shirt</a></h3>
                                        <span class="price">$35.00</span>
                                        <div class="woocommerce-product-rating">
                                            <span class="count">(5.00 Review)</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="tab-pane fade " id="tab-list" role="tabpanel" aria-labelledby="tab-shop-list">
                        <div class="row gy-30">

                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_1.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$250.00<del>$550.00</del></span>
                                        <h3 class="product-title"><a href="shop-details.html">beach casual-shoe</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_2.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$220.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Beach football</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_3.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$240.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Hamok</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_4.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$265.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Beach Hat</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_5.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$235.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Sony Black Headphone</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_6.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$225.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Hoodie With Zipper</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_7.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$235.00</span>
                                        <h3 class="product-title"><a href="shop-details.html">Trendy Sunglass</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="th-product list-view">
                                    <div class="product-img">
                                        <img src="assets/img/product/product_1_8.png" alt="Product Image">
                                        <div class="actions">
                                            <a href="cart.html" class="icon-btn"><i class="far fa-cart-plus"></i></a>
                                        </div>
                                    </div>
                                    <div class="product-content">
                                        <span class="price">$250.00<del>$550.00</del></span>
                                        <h3 class="product-title"><a href="shop-details.html">V-Neck T-Shirt</a></h3>
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>


            </div>
            <div class="th-pagination text-center pt-50">
                <ul>
                    <li><a class="active" href="blog.html">1</a></li>
                    <li><a href="blog.html">2</a></li>
                    <li><a href="blog.html">3</a></li>
                    <li><a href="blog.html">4</a></li>
                    <li><a class="next-page" href="blog.html">Next <img src="assets/img/icon/arrow-right4.svg" alt=""></a></li>
                </ul>
            </div>
        </div>
    </section>
@endsection    