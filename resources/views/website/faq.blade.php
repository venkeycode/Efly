@extends('layouts.website')
@section('content')
<!--==============================
    Breadcumb
============================== -->
<div class="breadcumb-wrapper " data-bg-src="assets/img/bg/breadcumb-bg.jpg">
        <div class="container">
            <div class="breadcumb-content">
                <h1 class="breadcumb-title">FAQs</h1>
                <ul class="breadcumb-menu">
                    <li><a href="home-travel.html">Home</a></li>
                    <li>FAQs</li>
                </ul>
            </div>
        </div>
    </div><!--==============================
Faq Area
==============================-->
    <div class="space-top space-extra-bottom">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xl-7">
                    <div class="title-area text-center">
                        <span class="sub-title">FAQ</span>
                        <h2 class="sec-title">frequently Ask Questions</h2>
                        <p>Have questions you want answers to?</p>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-10 offset-lg-1">
                    <div class="accordion-area accordion mb-30" id="faqAccordion">


                        <div class="accordion-card style2 active">
                            <div class="accordion-header" id="collapse-item-1">
                                <button class="accordion-button " type="button" data-bs-toggle="collapse" data-bs-target="#collapse-1" aria-expanded="true" aria-controls="collapse-1">Q1. Where is Agamana Stays located?</button>
                            </div>
                            <div id="collapse-1" class="accordion-collapse collapse show" aria-labelledby="collapse-item-1" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Agamana Stays is nestled in the serene surroundings of the Western Ghats, near Sakleshpur, Karnataka — known for its natural beauty and peaceful ambiance.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-2">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-2" aria-expanded="false" aria-controls="collapse-2">Q2.How can I book a stay?</button>
                            </div>
                            <div id="collapse-2" class="accordion-collapse collapse " aria-labelledby="collapse-item-2" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">You can book your stay directly through our website for a smooth and quick reservation experience. Alternatively, feel free to call our reservation team at +91 86609 91527 for personalized assistance.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-3">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-3" aria-expanded="false" aria-controls="collapse-3">Q3. What types of rooms are available?</button>
                            </div>
                            <div id="collapse-3" class="accordion-collapse collapse " aria-labelledby="collapse-item-3" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">We offer Deluxe Rooms equipped with modern interiors, plush bedding, and private jacuzzis — perfect for couples, families, or solo travelers.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-4">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-4" aria-expanded="false" aria-controls="collapse-4">Q4. Are meals included in the stay?</button>
                            </div>
                            <div id="collapse-4" class="accordion-collapse collapse " aria-labelledby="collapse-item-4" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Meal plans may vary based on your booking package. Please check the inclusions during online booking or confirm with our team while making your reservation.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-5">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-5" aria-expanded="false" aria-controls="collapse-5">Q5. What activities are available on-site?</button>
                            </div>
                            <div id="collapse-5" class="accordion-collapse collapse " aria-labelledby="collapse-item-5" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Guests can enjoy a swimming pool, campfire nights, walking & cycling trails, dirt bike rides*, and special celebrations. (*Dirt bike rides are subject to availability.)</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-6">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-6" aria-expanded="false" aria-controls="collapse-6">Q6. Is Agamana suitable for children and families??</button>
                            </div>
                            <div id="collapse-6" class="accordion-collapse collapse " aria-labelledby="collapse-item-6" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Absolutely! We welcome families and have activities that suit all age groups — from nature walks to safe outdoor fun.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-7">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-7" aria-expanded="false" aria-controls="collapse-7">Q7. Are pets allowed?</button>
                            </div>
                            <div id="collapse-7" class="accordion-collapse collapse " aria-labelledby="collapse-item-7" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Please contact us in advance to check if we can accommodate pets during your preferred dates, as it may depend on room availability and resort occupancy.</p>
                                </div>
                            </div>
                        </div>


                        <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-8">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-8" aria-expanded="false" aria-controls="collapse-8">Q8. 8. What are some nearby attractions?</button>
                            </div>
                            <div id="collapse-8" class="accordion-collapse collapse " aria-labelledby="collapse-item-8" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Explore nearby gems like Bisle Ghat View Point, Manjarabad Fort, and the Shettihalli Church — all just a short drive from the resort.</p>
                                </div>
                            </div>
                        </div>


                        <!-- <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-9">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-9" aria-expanded="false" aria-controls="collapse-9">Q9. How can I negotiate the best price when buying a property?</button>
                            </div>
                            <div id="collapse-9" class="accordion-collapse collapse " aria-labelledby="collapse-item-9" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text">Make a competitive but reasonable initial offer based on your research and budget. Include any contingencies or conditions that are important to you, such as a home inspection or financing approval.</p>
                                </div>
                            </div>
                        </div> -->


                        <!-- <div class="accordion-card style2 ">
                            <div class="accordion-header" id="collapse-item-10">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-10" aria-expanded="false" aria-controls="collapse-10">Q10. How can I negotiate the best price when buying a property?</button>
                            </div>
                            <div id="collapse-10" class="accordion-collapse collapse " aria-labelledby="collapse-item-10" data-bs-parent="#faqAccordion">
                                <div class="accordion-body style2">
                                    <p class="faq-text"> If you're a qualified buyer with a strong financial position, emphasize this to the seller. Sellers are often more willing to negotiate with buyers who are well-prepared and can demonstrate their ability to close the deal quickly and smoothly.</p>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div><!--==============================
elements Area  
==============================-->
    <div class="elements-sec bg-white overflow-hidden">
        <div class="container-fluid">
            <div class="tags-container relative"></div>
        </div>
    </div><!--==============================
Contact Area  
==============================-->
    <div class="bg-top-center space overflow-hidden" data-bg-src="assets/img/bg/tour_bg_3.jpg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-11">
                    <div class="">
                        <div class="title-area text-center mb-30">
                            <span class="sub-title style1">Meet with Our Guide</span>
                            <h2 class="sec-title">Do You Have Any More Questions?</h2>
                        </div>
                        <form action="mail.php" method="POST" class="contact-form ajax-contact">
                            <h3 class="sec-title mb-30">Book a tour</h3>
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <input type="text" class="form-control" name="name" id="name3" placeholder="First Name">
                                    <img src="assets/img/icon/user.svg" alt="">
                                </div>
                                <div class="col-md-6 form-group">
                                    <input type="email" class="form-control" name="email3" id="email3" placeholder="Your Mail">
                                    <img src="assets/img/icon/mail.svg" alt="">
                                </div>
                                <div class="form-group col-12">
                                    <select name="subject" id="subject" class="form-select nice-select">
                                        <option value="Select Tour Destination" selected disabled>Select Tour Destination
                                        </option>
                                        <option value="Africa Adventure">Africa Adventure</option>
                                        <option value="Africa Wild">Africa Wild</option>
                                        <option value="Asia">Asia</option>
                                        <option value="Scandinavia">Scandinavia</option>
                                        <option value="Western Europe">Western Europe</option>
                                    </select>
                                </div>
                                <div class="form-group col-12">
                                    <textarea name="message" id="message" cols="30" rows="3" class="form-control" placeholder="Your Message"></textarea>
                                    <img src="assets/img/icon/chat.svg" alt="">
                                </div>
                                <div class="form-btn col-12 mt-24"><button type="submit" class="th-btn style3">Send message <img src="assets/img/icon/plane.svg" alt=""></button>
                                </div>
                            </div>
                            <p class="form-messages mb-0 mt-3"></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
 @endsection   