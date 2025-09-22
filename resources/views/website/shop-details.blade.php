@extends('layouts.website')
@section('content')
   <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Shop Details</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>Shop Details</li>
                </ul>
            </div>
        </div>
    </div><!--==============================
    Product Details
    ==============================-->
    <section class="product-details space-top space-extra-bottom">
        <div class="container">
            <div class="row gx-60">
                <div class="col-lg-6">
                    <div class="product-big-img">
                        <div class="img"><img src="assets/img/product/product_details_1_1.png" alt="Product Image"></div>
                    </div>
                </div>
                <div class="col-lg-6 align-self-center">
                    <div class="product-about">
                        <p class="price">$120.85<del>$150.99</del></p>
                        <h2 class="product-title">Bluetooth Headphone</h2>
                        <div class="product-rating">
                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5"><span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span></div>
                            <a href="shop-details.html" class="woocommerce-review-link">(<span class="count">4</span>
                                customer reviews)</a>
                        </div>
                        <p class="text">Bluetooth headphones are a popular choice for many people due to their convenience
                            and wireless capabilities. They allow users to listen to music, podcasts, or make calls without
                            being tethered to their device</p>
                        <div class="mt-2 link-inherit">
                            <p>
                                <strong class="text-title me-3">Availability:</strong>
                                <span class="stock in-stock"><i class="far fa-check-square me-2 ms-1"></i>In Stock</span>
                            </p>
                        </div>
                        <div class="actions">
                            <div class="quantity">
                                <input type="number" class="qty-input" step="1" min="1" max="100" name="quantity" value="1" title="Qty">
                                <button class="quantity-plus qty-btn"><i class="far fa-chevron-up"></i></button>
                                <button class="quantity-minus qty-btn"><i class="far fa-chevron-down"></i></button>
                            </div>
                            <button class="th-btn">Add to Cart</button>
                            <a href="wishlist.html" class="icon-btn"><i class="far fa-heart"></i></a>
                        </div>
                        <div class="product_meta">
                            <span class="sku_wrapper">SKU: <span class="sku">BH-1001-BT</span></span>
                            <span class="posted_in">Category: <a href="shop.html"> sports, gaming, music
                                    listening</a></span>
                            <span>Tags: <a href="shop.html">Wireless</a><a href="shop.html">Sports</a></span>
                        </div>
                    </div>
                </div>
            </div>
            <ul class="nav product-tab-style2" id="productTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="description-tab" data-bs-toggle="tab" href="#description" role="tab" aria-controls="description" aria-selected="false">Description</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="additional-tab" data-bs-toggle="tab" href="#additional" role="tab" aria-controls="additional" aria-selected="false">Additional Information</a>
                </li>

                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="reviews-tab" data-bs-toggle="tab" href="#reviews" role="tab" aria-controls="reviews" aria-selected="true">Customer Reviews</a>
                </li>
            </ul>
            <div class="tab-content" id="productTabContent">
                <div class="tab-pane fade" id="description" role="tabpanel" aria-labelledby="description-tab">
                    <p>Bluetooth headphones are a wireless audio accessory that connects to your devices, like smartphones,
                        tablets, or computers, via Bluetooth technology. Here's a typical description
                        Introducing our [Brand Name] Bluetooth Headphones, the ultimate companion for your on-the-go audio
                        experience. Immerse yourself in high-fidelity sound without the hassle of wires, thanks to the
                        latest Bluetooth technology
                        Featuring [insert standout features such as noise cancellation, long battery life, etc.], our
                        headphones deliver crystal-clear audio while providing unmatched comfort during extended listening
                        sessions.

                        Designed for convenience and portability, our sleek and lightweight headphones fold easily for
                        storage and travel. The adjustable headband ensures a secure and comfortable fit for all head shapes
                        and sizes.</p>
                </div>
                <div class="tab-pane fade" id="additional" role="tabpanel" aria-labelledby="additional-tab">
                    <p>Advanced Bluetooth Connectivity: Our headphones utilize the latest Bluetooth technology for seamless
                        pairing with your devices and reliable wireless connectivity up to 30 feet away.

                        Long-lasting Battery Life: Enjoy hours of uninterrupted music playback with our long-lasting
                        rechargeable battery. Whether you're on a long flight or a marathon study session, our headphones
                        have you covered.

                        Immersive Sound Experience: Experience rich, immersive sound with powerful bass and crisp highs. Our
                        headphones are engineered to deliver audio that's true to the artist's intention, whether you're
                        listening to music, podcasts, or watching movies.

                        Comfortable Ergonomic Design: Designed for extended wear, our headphones feature plush ear cushions
                        and an adjustable headband for a comfortable fit. Say goodbye to discomfort, even during marathon
                        listening sessions.

                        Foldable and Portable: Our headphones are designed to go wherever you do. Easily fold them up and
                        toss them in your bag for on-the-go convenience. Whether you're traveling, commuting, or hitting the
                        gym, take your music with you everywhere.

                        Intuitive Controls: Control your music, adjust volume, and take calls with ease using the intuitive
                        onboard controls. No need to fumble with your device—everything you need is right at your
                        fingertips.</p>
                </div>
                <div class="tab-pane fade show active" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                    <div class="woocommerce-Reviews">
                        <div class="th-comments-wrap ">
                            <ul class="comment-list">
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-1.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Adam Jhon</h4>
                                            <span class="commented-on"><i class="far fa-calendar"></i>22 April, 2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">This product is very much qualityful and I love this working system and speed.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-2.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Jusctin Dacon</h4>
                                            <span class="commented-on"><i class="far fa-calendar"></i>26 April, 2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">They delivered the product in a few time. Product quality is also very good.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-3.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Jacklin July</h4>
                                            <span class="commented-on"><i class="far fa-calendar"></i>26 April, 2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">Their product and service is very satisfying. I highly recommend their services.</p>
                                        </div>
                                    </div>
                                </li>
                                <li class="review th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="assets/img/blog/comment-author-4.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h4 class="name">Adison Smith</h4>
                                            <span class="commented-on"><i class="far fa-calendar"></i>26 April, 2023</span>
                                            <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                                <span style="width:100%">Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                            </div>
                                            <p class="text">I am just in love with this product. Their service is also very good you can also try.</p>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> <!-- Comment Form -->
                        <div class="th-comment-form ">
                            <div class="form-title">
                                <h3 class="blog-inner-title ">Add a review</h3>
                            </div>
                            <div class="row">
                                <div class="form-group rating-select d-flex align-items-center">
                                    <label>Your Rating</label>
                                    <p class="stars">
                                        <span>
                                            <a class="star-1" href="#">1</a>
                                            <a class="star-2" href="#">2</a>
                                            <a class="star-3" href="#">3</a>
                                            <a class="star-4" href="#">4</a>
                                            <a class="star-5" href="#">5</a>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-12 form-group">
                                    <textarea placeholder="Write a Message" class="form-control"></textarea>
                                    <i class="text-title far fa-pencil-alt"></i>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Name" class="form-control">
                                    <i class="text-title far fa-user"></i>
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="text" placeholder="Your Email" class="form-control">
                                    <i class="text-title far fa-envelope"></i>
                                </div>
                                <div class="col-12 form-group">
                                    <input id="reviewcheck" name="reviewcheck" type="checkbox">
                                    <label for="reviewcheck">Save my name, email, and website in this browser for the next time I comment.<span class="checkmark"></span></label>
                                </div>
                                <div class="col-12 form-group mb-0">
                                    <button class="th-btn">Post Review</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--==============================
		Related Product  
		==============================-->
            <div class="space-extra-top mb-30">
                <div class="row">
                    <div class="title-area mb-25 text-center">
                        <span class="sub-title">Similar Products</span>
                        <h2 class="sec-title">Recently Viewed Items</h2>
                    </div>
                </div>
                <div class="swiper th-slider has-shadow" id="productSlider1" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"2"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"4"}}}'>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$250.00<del>$550.00</del></span>
                                    <h3 class="product-title"><a href="shop-details.html">Flooring-Vinyl</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$220.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Cement Metal Roof</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$240.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Metal Roof Steel</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$265.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Roof Tiles Braas</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$235.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Ceramic Roof</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$225.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Wall Formwork</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$235.00</span>
                                    <h3 class="product-title"><a href="shop-details.html">Flashing Roof</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="swiper-slide style2">
                            <div class="th-product">
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
                                    <span class="price">$250.00<del>$550.00</del></span>
                                    <h3 class="product-title"><a href="shop-details.html">Roof Topwet</a></h3>
                                    <div class="woocommerce-product-rating">
                                        <div class="star-rating" role="img" aria-label="Rated 5.00 out of 5">
                                            <span>Rated <strong class="rating">5.00</strong> out of 5 based on <span class="rating">1</span> customer rating</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="d-block d-md-none mt-40 text-center">
                    <div class="icon-box">
                        <button data-slider-prev="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-left"></i></button>
                        <button data-slider-next="#productSlider1" class="slider-arrow default"><i class="far fa-arrow-right"></i></button>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection    