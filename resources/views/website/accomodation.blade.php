@extends('layouts.website')
@section('content')
   <!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="{{asset('website') }}/assets/img/bg/breadcumb-bg.jpg" style="padding-bottom: 60px;">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">Accomodation</h1>
                <ul class="breadcumb-menu">
                    <li><a href="/">Home</a></li>
                    <li>Accomodation</li>
                </ul>
            </div>
        </div>
    </div>
    <!--==============================
tour Area
==============================-->
    <section class="space">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-lg-7">
                    <div class="tour-page-single">
                        <div class="slider-area tour-slider1">
                            <div class="swiper th-slider mb-4" id="tourSlider4" data-slider-options='{"effect":"fade","loop":true,"thumbs":{"swiper":".tour-thumb-slider"},"autoplayDisableOnInteraction":"true"}'>
                                <div class="swiper-wrapper">
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-2.jpg" alt="img">
                                        </div>
                                    </div> -->
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-3.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-4.jpg" alt="img">
                                        </div>
                                    </div> -->
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-5.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-6.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Room-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/jucuzzi-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Outdoor-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-8.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-9.jpg" alt="img">
                                        </div>
                                    </div> -->
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-10.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-11.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-12.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-13.jpg" alt="img">
                                        </div>
                                    </div> -->

                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-3.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-4.jpg" alt="img">
                                        </div>
                                    </div> -->
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-5.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-6.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Room-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/jucuzzi-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Outdoor-1.jpg" alt="img">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="swiper th-slider tour-thumb-slider" data-slider-options='{"effect":"slide","loop":true,"breakpoints":{"0":{"slidesPerView":2},"576":{"slidesPerView":"2"},"768":{"slidesPerView":"3"},"992":{"slidesPerView":"3"},"1200":{"slidesPerView":"3"}},"autoplayDisableOnInteraction":"true"}'>
                                <div class="swiper-wrapper">
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-1.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-2.jpg" alt="Image">
                                        </div>
                                    </div> -->
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-3.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-4.jpg" alt="Image">
                                        </div>

                                    </div> -->

                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-5.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-6.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Room-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/jucuzzi-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Outdoor-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <!-- <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-12.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-13.jpg" alt="img">
                                        </div>
                                    </div> -->

<!--
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-1.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-2.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-3.jpg" alt="Image">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-4.jpg" alt="Image">
                                        </div>
                                    </div> -->
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-5.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/gallery-inner-2-6.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Room-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Pool-1.jpg" alt="img">
                                        </div>
                                    </div>

                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Bathroom-2.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/jucuzzi-1.jpg" alt="img">
                                        </div>
                                    </div>
                                    <div class="swiper-slide">
                                        <div class="tour-slider-img">
                                            <img src="{{asset('website') }}/assets/img/tour/Outdoor-1.jpg" alt="img">
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <button data-slider-prev="#tourSlider4" class="slider-arrow style3 slider-prev"><img src="{{asset('website') }}/assets/img/icon/hero-arrow-left.svg" alt=""></button>
                            <button data-slider-next="#tourSlider4" class="slider-arrow style3 slider-next"><img src="{{asset('website') }}/assets/img/icon/hero-arrow-right.svg" alt=""></button>
                        </div>
                        <div class="page-content">
                            <div class="page-meta mb-45">
                                {{-- <a class="page-tag" href="tour.html">Featured</a>
                                <span class="ratting"><i class="fa-sharp fa-solid fa-star"></i><span>4.8</span></span> --}}
                            </div>
                            <!-- <h2 class="box-title">Explore the Beauty of Maldives Costal</h2> -->
                            <h2>🏡 Accommodation at Agamana Stays</h2>

                                <p>
                                 Surrounded by the lush greenery of the Western Ghats, Agamana Stays offers a variety of accommodation options for every kind of traveler. Whether you're seeking a luxurious forest retreat or an adventurous camping experience, our property is designed to help you disconnect from the noise and reconnect with what truly matters — nature, peace, and yourself.
                                </p>

                              <h4>🛏️ Deluxe Cottages</h4>
                                <p>
                                 Our deluxe cottages combine rustic charm with modern comfort. These independent units are perfect for couples, families, or solo travelers who prefer a serene and private space. Designed to bring the outdoors in, each cottage features:
                                </p>
                             <ul>
                               <li>🛁 Spacious bathrooms with hot water</li>
                               <li>🪟 Large windows offering stunning views of the forest</li>
                               <li>🪑 Private verandas with seating for quiet mornings or starry nights</li>
                               <li>🧼 Daily housekeeping for a clean and fresh environment</li>
                               <li>🌬️ Fans and ventilation to keep you comfortable</li>
                               <li>🔌 Power backup for an uninterrupted stay</li>
                             </ul>

                              <h4>⛺ Tent Stays</h4>
                                <p>
                                  For guests who want to truly embrace the outdoors, our tented accommodations offer a unique camping experience with basic comfort. Situated under tall trees and near nature trails, our tents are perfect for backpackers, nature enthusiasts, and groups. Tents include:
                                </p>
                             <ul>
                               <li>🛏️ Comfortable sleeping mats and pillows</li>
                               <li>🚿 Access to hygienic common washrooms</li>
                               <li>🪵 Cozy setups around the campfire</li>
                               <li>🎒 Ideal for group bonding and team retreats</li>
                               <li>🌌 A chance to sleep under the stars</li>
                             </ul>

                             <h4>🎁 What's Included in Every Stay</h4>
                              <p>
                                 All accommodations at Agamana Stays include a set of essential and experiential services, carefully curated to enhance your stay:
                              </p>
                            <ul>
                              <li>🍛 Home-style meals (breakfast, lunch, dinner) – veg & non-veg</li>
                              <li>🚗 Free on-site parking</li>
                              <li>🔥 Bonfire evenings (on request)</li>
                              <li>🚶‍♀️ Access to walking and cycling trails</li>
                              <li>🏞️ Proximity to waterfalls, viewpoints, and trekking routes</li>
                              <li>🎉 Spaces for private celebrations (birthdays, reunions, etc.)</li>
                            </ul>

                               <h4>💰 Pricing & Packages</h4>
                                <p>
                                  Our pricing is transparent and inclusive:
                               </p>
                            <ul>
                               <li>🏕️ Tent Stay: Starting from ₹1499/person</li>
                               <li>🏡 Deluxe Cottage: Starting from ₹2499/person</li>
                               <li>👨‍👩‍👧 Group and family discounts available</li>
                               <li>🍽️ Meal plans and activity add-ons can be customized</li>
                            </ul>
                               <p>
                                 *Note: Prices include accommodation, meals, and access to basic property amenities.*
                                </p>

                             <h4>📅 Book Your Nature Escape</h4>
                               <p>
                                 Booking your stay is easy! Reach out to us directly via phone or WhatsApp at <strong>+91 86609 91527</strong>, or use our <a href="/booking">online booking form</a> to check availability and confirm your reservation.
                                </p>
                                <p>
                                  Whether you’re planning a peaceful retreat, a birthday celebration, or a weekend adventure, Agamana Stays is your home in the hills. Come as you are — leave refreshed.
                                </p>

                            <!-- <p class="box-text mb-30">voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                                ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                Dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius
                                modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Quis autem vel
                                eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel
                                illum qui dolorem eum fugiat quo voluptas nulla pariatur</p>
                            <p class="box-text mb-50"> Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis
                                suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure
                                reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui
                                dolorem eum fugiat quo voluptas nulla pariatur</p>
                            <div class="tour-snapshot">
                                <h4 class="box-title">Tour Snapshot</h4>
                                <div class="tour-snap-wrapp">
                                    <div class="tour-snap">
                                        <div class="icon">
                                            <i class="fa-light fa-clock"></i>
                                        </div>
                                        <div class="content">
                                            <span class="title">Duration:</span>
                                            <span>8h</span>
                                        </div>
                                    </div>
                                    <div class="tour-snap">
                                        <div class="icon">
                                            <img src="{{asset('website') }}/assets/img/icon/guide2.svg" alt="">
                                        </div>
                                        <div class="content">
                                            <span class="title">Group Size:</span>
                                            <span>12</span>
                                        </div>
                                    </div>
                                    <div class="tour-snap">
                                        <div class="icon">
                                            <img src="{{asset('website') }}/assets/img/icon/ship.svg" alt="">
                                        </div>
                                        <div class="content">
                                            <span class="title">Near Public</span>
                                            <span>Transportation</span>
                                        </div>
                                    </div>
                                    <div class="tour-snap">
                                        <div class="icon">
                                            <img src="{{asset('website') }}/assets/img/icon/01.svg" alt="">
                                        </div>
                                        <div class="content">
                                            <span class="title">Free Cancellation</span>
                                            <a href="#" class="line-btn">Learn more</a>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                            <!-- <h2 class="box-title">Overview</h2>
                            <p class="box-text mb-50">voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                                ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                Dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius
                                modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Quis autem vel
                                eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel
                                illum qui dolorem eum fugiat quo voluptas nulla pariatur.</p>
                            <h2 class="box-title">Highlights</h2>
                            <p class="box-text mb-30">voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque
                                ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo.
                                Dolorem ipsum quia dolor sit amet, consectetur, adipisci.</p>
                            <div class="checklist mb-50">
                                <ul>
                                    <li>Visit most popular location of Maldives</li>
                                    <li>Buffet Breakfast for all traveler with good quality.</li>
                                    <li>Expert guide always guide you and give informations.</li>
                                    <li>Best Hotel for all also great food.</li>
                                    <li>Helping all traveler for Money Exchange.</li>
                                    <li>Buffet Breakfast for all traveler with good quality..</li>
                                    <li>Buffet Breakfast for all traveler with good quality.</li>
                                </ul>
                            </div>
                            <h2 class="box-title">Important Information</h2>
                            <p class="blog-text mb-35">voluptatem accusantium doloremque laudantium, totam rem aperiam,
                                eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt
                                explicabo. Dolorem ipsum quia dolor sit amet, consectetur, adipisci.</p>
                            <div class="activities-checklist mb-50">
                                <div class="checklist style2">
                                    <ul>
                                        <li>Expert guide</li>
                                        <li>Admission to Windsor Castle</li>
                                        <li>Wi-Fi and USB Charging On-board</li>
                                        <li>Admission to Stonehenge</li>
                                        <li>Departure Date</li>
                                        <li>Hotel pick-up and drop-off</li>
                                    </ul>
                                </div>
                                <div class="checklist style2">
                                    <ul>
                                        <li>Departures from 01st April 2024: Tour departs at 8 am</li>
                                        <li>(boarding at 7.30 am), Victoria Coach Station Gate 1-5</li>
                                        <li>Duration: 11h</li>
                                        <li>Mobile tickets accepted</li>
                                        <li>Instant confirmation</li>
                                    </ul>
                                </div>
                            </div>
                            <h2 class="box-title">Included and Excluded</h2>
                            <p class="blog-text mb-35">voluptatem accusantium doloremque laudantium, totam rem aperiam,
                                eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt
                                explicabo. Dolorem ipsum quia dolor sit amet, consectetur, adipisci.</p>
                            <div class="destination-checklist">
                                <div class="checklist style2 style4">
                                    <ul>
                                        <li>Hotel Fair</li>
                                        <li>Transportation</li>
                                        <li>Breakfast</li>
                                        <li>Sightseeing</li>
                                        <li>Travel Tax</li>
                                        <li>Seasonal Food</li>
                                    </ul>
                                </div>
                                <div class="checklist style5">
                                    <ul>
                                        <li>WIFI</li>
                                        <li>Swimming Pool</li>
                                        <li>GYM</li>
                                        <li>Travel Insurance</li>
                                        <li>Family Expenses</li>
                                        <li>Family Expenses</li>
                                    </ul>
                                </div>
                            </div>
                         </div> -->
                          <div class="location-map">
                            <h3 class="page-title mt-45 mb-30">Accomadation Location</h3>
                            <div class="contact-map">
                                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3644.7310056272386!2d89.2286059153658!3d24.00527418490799!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x39fe9b97badc6151%3A0x30b048c9fb2129bc!2sAngfuztheme!5e0!3m2!1sen!2sbd!4v1651028958211!5m2!1sen!2sbd" allowfullscreen="" loading="lazy"></iframe>
                                <div class="contact-icon">
                                    <img src="{{asset('website') }}/assets/img/icon/location-dot3.svg" alt="">
                                </div>
                            </div>
                         </div>
                          <!-- <div class="th-comments-wrap style2 ">
                            <h2 class="blog-inner-title h4">Reviews (3)</h2>
                            <ul class="comment-list">
                                <li class="th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="{{asset('website') }}/assets/img/blog/comment-author-1.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <h3 class="name">Adam Jhon</h3>
                                            <div class="commented-wrapp">
                                                <span class="commented-on">20 Jun, 2024</span>
                                                <span class="commented-time">08:56pm </span>
                                                <span class="comment-review">
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                    <i class="fa-solid fa-star"></i>
                                                </span>
                                            </div>
                                            <p class="text">Credibly pontificate transparent quality vectors with quality mindshare. Efficiently
                                                architect worldwide strategic theme areas after user.</p>
                                            <div class="reply_and_edit">
                                                <i class="fa-solid fa-thumbs-up"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <ul class="children">
                                        <li class="th-comment-item">
                                            <div class="th-post-comment">
                                                <div class="comment-avater">
                                                    <img src="{{asset('website') }}/assets/img/blog/comment-author-4.jpg" alt="Comment Author">
                                                </div>
                                                <div class="comment-content">
                                                    <div class="">
                                                        <h3 class="name">Maria Willson</h3>
                                                        <div class="commented-wrapp">
                                                            <span class="commented-on">23 Jun, 2024</span>
                                                            <span class="commented-time">08:56pm </span>
                                                            <span class="comment-review">
                                                                <i class="fa-solid fa-star"></i>
                                                                <i class="fa-solid fa-star"></i>
                                                                <i class="fa-solid fa-star"></i>
                                                                <i class="fa-solid fa-star"></i>
                                                                <i class="fa-solid fa-star"></i>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <p class="text">It is different from airport transfer or port transfer, which are services
                                                        that pick you up</p>
                                                    <div class="reply_and_edit">
                                                        <i class="fa-solid fa-thumbs-up"></i>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </li>
                                <li class="th-comment-item">
                                    <div class="th-post-comment">
                                        <div class="comment-avater">
                                            <img src="{{asset('website') }}/assets/img/blog/comment-author-5.jpg" alt="Comment Author">
                                        </div>
                                        <div class="comment-content">
                                            <div class="">
                                                <h3 class="name">Michel Edwards</h3>
                                                <div class="commented-wrapp">
                                                    <span class="commented-on">27 Jun, 2024</span>
                                                    <span class="commented-time">08:56pm </span>
                                                    <span class="comment-review">
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                        <i class="fa-solid fa-star"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <p class="text">Credibly pontificate transparent quality vectors with quality mindshare. Efficiently
                                                architect worldwide strategic theme areas after user.</p>
                                            <div class="reply_and_edit">
                                                <i class="fa-solid fa-thumbs-up"></i>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div> Comment end Comment Form -->
                        <!-- <div class="th-comment-form ">
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
                                    <button class="th-btn">Send Message<img src="{{asset('website') }}/assets/img/icon/plane2.svg" alt=""></button>
                                </div>
                            </div>
                        </div> -->

                    </div>
                </div>
                <div class="col-xxl-4 col-lg-5">
                    <aside class="sidebar-area style2">
                        <!-- <div class="widget widget_search  ">
                            <form class="search-form">
                                <input type="text" placeholder="Search">
                                <button type="submit"><i class="far fa-search"></i></button>
                            </form>
                        </div>
                        <div class="widget widget_categories  ">
                            <h3 class="widget_title">Categories</h3>
                            <ul>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">City Tour</a>
                                    <span>(8)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">Beach Tours</a>
                                    <span>(6)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">Wildlife Tours</a>
                                    <span>(2)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">News & Tips</a>
                                    <span>(7)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">Adventure Tours</a>
                                    <span>(9)</span>
                                </li>
                                <li>
                                    <a href="blog.html"><img src="{{asset('website') }}/assets/img/theme-img/map.svg" alt="">Mountain Tours</a>
                                    <span>(10)</span>
                                </li>
                            </ul>
                        </div> -->
                        <div class="widget tour-booking  ">
                            <form action="{{route('booking.confirm')}}" method="GET">
                            <p class="widget_subtitle"> <span class="widget_price">₹ {{$amount}} / Per Person</span></p>
                            <div class="info-list">
                                <ul>
                                    @if(isset($start_date) && isset($end_date))
                                    <li>
                                        <strong>Date </strong>
                                        <span>{{\Carbon\Carbon::parse($start_date)->format('d-F-Y')}} - {{\Carbon\Carbon::parse($end_date)->format('d-F-Y')}}</span>
                                    </li>
                                    <li>
                                        <strong>Number of travelers</strong>
                                        <span style="color: #ff9800;">{{$people}} People</span>
                                    </li>
                                    <li>
                                        <strong>Total Days</strong>
                                        <span style="color: #4caf50;">{{$days}} Days</span>
                                    </li>
                                    @else
                                </hr>
                                <li>
                                    <div class="row">
                                        <div class="col-12 form-group" >
                                            <input type="date" class="form-control" name="start_date" style="background: #ffffff;" required>
                                        </div>
                                        <div class="col-12 form-group">
                                            <input type="date" class="form-control" name="end_date" style="background: #ffffff;" required>
                                        </div>
                                        <div class="col-12 form-group">
                                            <input type="mobile" class="form-control" name="people" placeholder="No of people" style="background: #ffffff;" required>
                                        </div>

                                    </div>
                                </li>
                                    @endif
                                    @php
                                        $total_amount =$amount*$people*$days;
                                    @endphp
                                    @if($total_amount >0)
                                    <li>
                                        <strong>Total Amount</strong>
                                        <span>  ₹ {{$amount}} x  {{$people}} People x  {{$days}} Days = ₹ {{$total_amount}}</span> </br>
                                        <span style="font-size: 1.5em; color: #00796b;">₹{{$total_amount}}</span>
                                        <input type="hidden" name="people" value="{{$people}}" readonly>
                                        <input type="hidden" name="start_date" value="{{$start_date}}" readonly>
                                        <input type="hidden" name="end_date" value="{{$end_date}}" readonly>
                                        {{-- <span>₹ {{$total_amount}} </span> --}}
                                    </li>
                                    @endif
                                </ul>
                            </div>
                            <button type="submit" class="th-btn th-icon">Book Now</button>
                            </form>
                            {{-- <span class="review"><i class="fa-light fa-heart"></i> 88% of travelers recommend this experience</span> --}}
                        </div>
                        <!-- <div class="widget widget_tag_cloud  ">
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
                        </div> -->
                        <!-- <div class="widget widget_offer  " data-bg-src="{{asset('website') }}/assets/img/bg/widget_bg_1.jpg">
                            <div class="offer-banner">
                                <div class="offer">
                                    <h6 class="box-title">Need Help? We Are Here To Help You</h6>
                                    <div class="banner-logo">
                                        <img src="{{asset('website') }}/assets/img/logo2.svg" alt="Agamana">
                                    </div>
                                    <div class="offer">
                                        <h6 class="offer-title">You Get Online support</h6>
                                        <a class="offter-num" href="+256214203215">+256 214 203 215</a>
                                    </div>
                                    <a href="contact.html" class="th-btn style2 th-icon">Read More</a>
                                </div>
                            </div>
                        </div> -->
                    </aside>
                </div>

            </div>

        </div>
    </section>
@endsection
