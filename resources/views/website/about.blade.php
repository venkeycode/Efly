@extends('layouts.website')
@section('content')
<!--==============================
    Breadcumb
============================== -->
    <div class="breadcumb-wrapper " data-bg-src="{{asset('website') }}/assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">About Agamana</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>About Agamana</li>
                </ul>
            </div>
        </div>
    </div><!--==============================
About Area
==============================-->
    <div class="about-area position-relative overflow-hidden overflow-hidden space" id="about-sec">
        <div class="container">
            <div class="row">
                <div class="col-xl-7">
                    <div class="img-box3">
                        <div class="img1">
                            <img src="{{asset('website') }}/assets/img/normal/about-3-1.jpg" alt="About">
                        </div>
                        <div class="img2">
                            <img src="{{asset('website') }}/assets/img/normal/about-3-2.jpg" alt="About">
                        </div>
                        <div class="img3 movingX">
                            <img src="{{asset('website') }}/assets/img/normal/about-3-3.jpg" alt="About">
                        </div>
                    </div>
                </div>
                <div class="col-xl-5">
                    <div class="ps-xl-4">
                        <div class="title-area mb-20">
                            <span class="sub-title style1 ">About Agamana Stays</span>
                            <h2 class="sec-title mb-20 pe-xl-5 me-xl-5 heading">Welcome to Agamana Stays

                            </h2>
                        </div>
                        <p class="pe-xl-5">Tucked away in the breathtaking beauty of the Western Ghats, Agamana Stays offers more than just a place to stay — it’s an experience of comfort, luxury, and connection with nature. Whether you seek peace, thrill, or renewal, Agamana provides the perfect setting for an unforgettable getaway.

                        </p>
                        <!-- <p class="mb-30 pe-xl-5"> Leiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt.</p> -->
                        <div class="about-item-wrap">
                            <div class="about-item style2">
                                <div class="about-item_img"><img src="{{asset('website') }}/assets/img/icon/about_1_1.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Get ready for a getaway that blends comfort, adventure, and nature at Agamana Stays.</h5>
                                    <p class="about-item_text">Whether you’re dreaming of peaceful mornings, thrilling outdoor activities, or cozy evenings by the campfire,
                                         planning your trip to Agamana is simple and exciting.</p>
                                </div>
                            </div>
                            <div class="about-item style2">
                                <div class="about-item_img"><img src="{{asset('website') }}/assets/img/icon/about_1_2.svg" alt=""></div>
                                <div class="about-item_centent">
                                <h6 class="box-title">Here's how to get started:</h6>
                                    <h5 class="box-title">Choose Your Dates</h5>
                                    <p class="about-item_text">Pick the dates that best suit your schedule. Agamana Stays is beautiful year-round, with every season offering its own unique charm.</p>
                                </div>
                            </div>
                            <div class="about-item style2">
                                <div class="about-item_img"><img src="{{asset('website') }}/assets/img/icon/about_1_3.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Select Your Stay</h5>
                                    <p class="about-item_text">Our deluxe rooms offer cozy sophistication with private jacuzzis and modern comforts — perfect for solo travelers, couples, and families..</p>
                                </div>
                            </div>
                            <div class="about-item style2">
                                <div class="about-item_img"><img src="{{asset('website') }}/assets/img/icon/about_1_3.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Plan Your Activitie</h5>
                                    <p class="about-item_text">Dive into our swimming pool, ride dirt bikes*, explore scenic cycling and walking trails, or relax by a crackling campfire. Don't forget to explore nearby attractions like Bisle Ghat View Point,
                                        Manjarabad Fort, and the Shettihalli Church.</p>
                                </div>
                            </div>
                            <div class="about-item style2">
                                <div class="about-item_img"><img src="{{asset('website') }}/assets/img/icon/about_1_3.svg" alt=""></div>
                                <div class="about-item_centent">
                                    <h5 class="box-title">Customize Your Experience</h5>
                                    <p class="about-item_text">Celebrating something special? We can help organize birthday celebrations, group events, and more. Let us know your needs when you book!</p>
                                </div>
                            </div>
                        </div>
                        <div class="mt-35"><a href="contact.html" class="th-btn style3 th-icon">Contact With Us</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="shape-mockup movingX d-none d-xxl-block" data-top="4%" data-left="2%">
            <img src="{{asset('website') }}/assets/img/shape/shape_2_1.png" alt="shape">
        </div>
        <div class="shape-mockup jump d-none d-xxl-block" data-top="28%" data-right="5%">
            <img src="{{asset('website') }}/assets/img/shape/shape_2_2.png" alt="shape">
        </div>
        <div class="shape-mockup spin d-none d-xxl-block" data-bottom="18%" data-left="2%">
            <img src="{{asset('website') }}/assets/img/shape/shape_2_3.png" alt="shape">
        </div>
        <div class="shape-mockup movixgX d-none d-xxl-block" data-bottom="18%" data-right="2%">
            <img src="{{asset('website') }}/assets/img/shape/shape_2_4.png" alt="shape">
        </div>
    </div> <!--==============================
Destination Area
==============================-->
{{--
    <section class="position-relative overflow-hidden space-bottom" id="destination-sec">
        <div class="container">
            <div class="title-area text-center">
                <span class="sub-title">Services We Offer</span>
                <h2 class="sec-title">Our Amazing services</h2>
            </div>
            <div class="row gy-4 gx-4">
                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_1.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Photo Shoot</a></h3>
                            <p class="destination-text">20 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_2.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Tour Guide</a></h3>
                            <p class="destination-text">22 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_3.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Cozy Event</a></h3>
                            <p class="destination-text">23 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_4.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Interesting Rest</a></h3>
                            <p class="destination-text">24 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_5.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Kayaking</a></h3>
                            <p class="destination-text">25 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_6.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Safe Flight</a></h3>
                            <p class="destination-text">26 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_7.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Entertainment</a></h3>
                            <p class="destination-text">27 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-4 col-md-6">
                    <div class="destination-item th-ani">
                        <div class="destination-item_img global-img">
                            <img src="{{asset('website') }}/assets/img/destination/destination_4_8.jpg" alt="image">
                        </div>
                        <div class="destination-content">
                            <h3 class="box-title"><a href="contact.html">Delicisious Food</a></h3>
                            <p class="destination-text">28 Listing</p>
                            <a href="contact.html" class="th-btn style4 th-icon">Book Now</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>  --}}
    <!--==============================
elements Area
==============================-->
    <!-- <div class="elements-sec bg-white overflow-hidden">
        <div class="container-fluid">
            <div class="tags-container relative"></div>
        </div>
    </div>   -->
    <!--==============================
Team Area
==============================-->
    {{-- <section class="team-area3 position-relative bg-top-center space" data-bg-src="{{asset('website') }}/assets/img/bg/team_bg_2.jpg">
        <div class="container z-index-common">
            <div class="title-area text-center">
                <span class="sub-title">Meet with Guide</span>
                <h2 class="sec-title">Meet with Tour Guide</h2>
            </div>
            <div class="slider-area">
                <div class="swiper th-slider teamSlider3 has-shadow" id="teamSlider3" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"1"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"3"}}}'>
                    <div class="swiper-wrapper">
                        <!-- Single Item
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_1.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_1.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Michel Smith</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_2.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_2.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Janny Willson</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_3.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_3.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Jacob Jones</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_1.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_4.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Maria Prova</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_2.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_5.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Rebeka Maliha</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_3.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_6.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Alif Mahmud</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_1.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_3.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Guy Hawkins</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Single Item -->
                        <div class="swiper-slide">
                            <div class="th-team team-grid">
                                <div class="team-img">
                                    <img src="{{asset('website') }}/assets/img/team/team_img_2.jpg" alt="Team">
                                </div>
                                <div class="team-img2">
                                    <img src="{{asset('website') }}/assets/img/team/team_1_4.jpg" alt="Team">
                                </div>
                                <div class="team-content">
                                    <div class="media-body">
                                        <h3 class="box-title"><a href="tour-guider-details.html">Jenny Wilson</a></h3>
                                        <span class="team-desig">Tourist Guide</span>


                                        <div class="th-social">
                                            <a target="_blank" href="https://facebook.com/"><i class="fab fa-facebook-f"></i></a>
                                            <a target="_blank" href="https://twitter.com/"><i class="fab fa-twitter"></i></a>
                                            <a target="_blank" href="https://linkedin.com/"><i class="fab fa-linkedin-in"></i></a>
                                            <a target="_blank" href="https://youtube.com/"><i class="fab fa-youtube"></i></a>
                                            <a target="_blank" href="https://instagram.com/"><i class="fab fa-instagram"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="slider-pagination"></div>

                </div>
                <button data-slider-prev="#teamSlider3" class="slider-arrow slider-prev"><img src="{{asset('website') }}/assets/img/icon/right-arrow2.svg" alt=""></button>
                <button data-slider-next="#teamSlider3" class="slider-arrow slider-next"><img src="{{asset('website') }}/assets/img/icon/left-arrow2.svg" alt=""></button>
            </div>
        </div>
    </section> --}}
    <!--============================== -->
         <!-- Testimonial Area
   ==============================-->
    {{-- <section class="testi-area overflow-hidden space-bottom" id="testi-sec">
        <div class="container-fluid p-0">
            <div class="title-area mb-20 text-center">
                <span class="sub-title">Testimonial</span>
                <h2 class="sec-title">What Client Say About us</h2>
            </div>
            <div class="slider-area">
                <div class="swiper th-slider testiSlider1 has-shadow" id="testiSlider1" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"767":{"slidesPerView":"2","centeredSlides":"true"},"992":{"slidesPerView":"2","centeredSlides":"true"},"1200":{"slidesPerView":"2","centeredSlides":"true"},"1400":{"slidesPerView":"3","centeredSlides":"true"}}}'>
                    <div class="swiper-wrapper">

                        <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_2.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">"A Hidden Gem in the Western Ghats!"</h3>
                                            <span class="testi-card_desig">"Agamana Stays was everything we dreamed of and more.
                                                The deluxe rooms with private jacuzzis were pure luxury, and the peaceful surroundings made it a truly rejuvenating trip.
                                                 Highly recommend!"</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">— Ananya R.</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_1.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">"Perfect Mix of Adventure and Relaxation"</h3>
                                            <span class="testi-card_desig">"From dirt biking to cozy campfire nights, every moment at Agamana was unforgettable.
                                                The staff were warm, the amenities were excellent, and the natural beauty was breathtaking."</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">— Rahul K.</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_2.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">"An Unforgettable Family Vacation"</h3>
                                            <span class="testi-card_desig">"Our kids loved the swimming pool and cycling trails while we enjoyed quiet walks and evening bonfires.
                                                Agamana Stays is a fantastic spot for families looking to reconnect with nature."</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">— Pooja & Arvind S.</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_1.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">"Celebrated My Birthday Here — Magical!"</h3>
                                            <span class="testi-card_desig">"The team helped organize a beautiful birthday evening by the campfire. It was personal, thoughtful, and magical.
                                                 I couldn’t have asked for a better place to celebrate!"</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">— Sneha P.</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div>
                                 <!-- <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_1.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">Maria Doe</h3>
                                            <span class="testi-card_desig">Traveller</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">“A home that perfectly blends sustainability with luxury until I discovered Ecoland
                                    Residence. From the moment I stepped into this community, I knew it was where I wanted to live.
                                    The commitment to eco-friendly living”</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_1.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">Maria Doe</h3>
                                            <span class="testi-card_desig">Traveller</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">“A home that perfectly blends sustainability with luxury until I discovered Ecoland Residence. From the moment I stepped into this community, I knew it was where I wanted to live. The commitment to eco-friendly living”</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_2.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">Andrew Simon</h3>
                                            <span class="testi-card_desig">Traveller</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">A sophisticated rainwater harvesting system collects and filters rainwater for irrigation and non-potable uses, reducing reliance on municipal water sources. Greywater systems</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div> -->
                        <!-- <div class="swiper-slide">
                            <div class="testi-card">
                                <div class="testi-card_wrapper">
                                    <div class="testi-card_profile">
                                        <div class="testi-card_avater">
                                            <img src="{{asset('website') }}/assets/img/testimonial/testi_1_1.jpg" alt="testimonial">
                                        </div>
                                        <div class="media-body">
                                            <h3 class="box-title">Alex Jordan</h3>
                                            <span class="testi-card_desig">Traveller</span>
                                        </div>
                                    </div>
                                    <div class="testi-card_review">
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                        <i class="fa-solid fa-star"></i>
                                    </div>
                                </div>

                                <p class="testi-card_text">Throughout the interior, eco-friendly materials like reclaimed wood, bamboo flooring, and recycled glass countertops create a luxurious yet sustainable ambiance.</p>
                                <div class="testi-card-quote">
                                    <img src="{{asset('website') }}/assets/img/icon/testi-quote.svg" alt="img">
                                </div>
                            </div>
                        </div> -->

                    </div>
                    <div class="slider-pagination"></div>
                </div>
            </div>
        </div>
        <div class="shape-mockup d-none d-xl-block" data-bottom="-2%" data-right="0%">
            <img src="{{asset('website') }}/assets/img/shape/line2.png" alt="shape">
        </div>
        <div class="shape-mockup movingX d-none d-xl-block" data-top="30%" data-left="5%">
            <img src="{{asset('website') }}/assets/img/shape/shape_7.png" alt="shape">
        </div>
    </section> --}}
    <!--==============================
Brand Area
==============================-->
    <!-- <div class="brand-area overflow-hidden ">
        <div class="container th-container">
            <div class="swiper th-slider brandSlider1" id="brandSlider1" data-slider-options='{"breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"2"},"768":{"slidesPerView":"3"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"6"},"1400":{"slidesPerView":"8"}}}'>
                <div class="swiper-wrapper">

                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_1.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_1.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_2.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_2.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_3.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_3.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_4.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_4.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_5.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_5.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_6.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_6.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_7.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_7.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_8.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_8.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_4.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_4.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_3.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_3.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_2.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_2.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="brand-box">
                            <a href="">
                                <img class="original" src="{{asset('website') }}/assets/img/brand/brand_1_1.svg" alt="Brand Logo">
                                <img class="gray" src="{{asset('website') }}/assets/img/brand/brand_1_1.svg" alt="Brand Logo">
                            </a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div> <!--============================== -->
<!-- gallery-thumb Area
==============================--> -->
    {{-- <div class="sidebar-gallery-area space">
        <div class="container-fluid">
            <div class="slider-area">
                <div class="swiper th-slider has-shadow" data-slider-options='{"centeredSlides":"true","breakpoints":{"0":{"slidesPerView":1},"576":{"slidesPerView":"2"},"768":{"slidesPerView":"2"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"3"},"1300":{"slidesPerView":"4"}}}'>
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_1.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_2.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_3.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_4.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_5.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_1.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_2.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_3.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_4.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_5.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="gallery-thumb style2 global-img">
                                <img src="{{asset('website') }}/assets/img/gallery/gallery_4_2.jpg" alt="Gallery Image">
                                <a target="_blank" href="https://www.instagram.com/" class="gallery-btn"><i class="fab fa-instagram"></i></a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div> --}}

@endsection
