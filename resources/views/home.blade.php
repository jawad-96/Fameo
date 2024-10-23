<?php
use Illuminate\Support\Str;
?>
@extends('layouts.app')



@section('content')

<main class="main">
         <div class="hero-section hs-1">
            <div class="container">
               <div class="hero-slider owl-carousel owl-theme">
                  <div class="hero-single">
                     <div class="container">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="hero-content">
                                 <h6 class="hero-sub-title" data-animation="fadeInUp" data-delay=".25s">Welcome to fameo!</h6>
                                 <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                    We offers modern <span>furniture</span> for you
                                 </h1>
                                 <p data-animation="fadeInLeft" data-delay=".75s">
                                    There are many variations of passages orem psum available but the majority
                                    have suffered alteration in some form by injected humour.
                                 </p>
                                 <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                                    <a href="shop-grid.html" class="theme-btn">Shop Now<i class="fas fa-arrow-right"></i></a>
                                    <a href="about.html" class="theme-btn theme-btn2">Learn More<i class="fas fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="hero-right" data-animation="fadeInRight" data-delay=".25s">
                                 <div class="hero-img">
                                    <div class="hero-img-item">
                                       <button type="button"><i class="far fa-plus"></i></button>
                                       <div class="hero-img-content">
                                          <img src="{{ asset('images/hero/01.png') }}">
                                          <div class="hero-img-info">
                                             <h6><a href="#">Modern Chair</a></h6>
                                             <p>Price: <span>$230</span></p>
                                             <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                                          </div>
                                       </div>
                                    </div>
                                    <img src="{{ asset('images/hero/01.png') }}">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="hero-single">
                     <div class="container">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="hero-content">
                                 <h6 class="hero-sub-title" data-animation="fadeInUp" data-delay=".25s">Welcome to fameo!</h6>
                                 <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                    We offers modern <span>furniture</span> for you
                                 </h1>
                                 <p data-animation="fadeInLeft" data-delay=".75s">
                                    There are many variations of passages orem psum available but the majority
                                    have suffered alteration in some form by injected humour.
                                 </p>
                                 <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                                    <a href="shop-grid.html" class="theme-btn">Shop Now<i class="fas fa-arrow-right"></i></a>
                                    <a href="about.html" class="theme-btn theme-btn2">Learn More<i class="fas fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="hero-right" data-animation="fadeInRight" data-delay=".25s">
                                 <div class="hero-img">
                                    <div class="hero-img-item">
                                       <button type="button"><i class="far fa-plus"></i></button>
                                       <div class="hero-img-content">
                                          <img src="{{ asset('images/hero/02.png') }}">
                                          <div class="hero-img-info">
                                             <h6><a href="#">Modern Chair</a></h6>
                                             <p>Price: <span>$230</span></p>
                                             <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                                          </div>
                                       </div>
                                    </div>
                                    <img src="{{ asset('images/hero/02.png') }}">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <div class="hero-single">
                     <div class="container">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="hero-content">
                                 <h6 class="hero-sub-title" data-animation="fadeInUp" data-delay=".25s">Welcome to fameo!</h6>
                                 <h1 class="hero-title" data-animation="fadeInRight" data-delay=".50s">
                                    We offers modern <span>furniture</span> for you
                                 </h1>
                                 <p data-animation="fadeInLeft" data-delay=".75s">
                                    There are many variations of passages orem psum available but the majority
                                    have suffered alteration in some form by injected humour.
                                 </p>
                                 <div class="hero-btn" data-animation="fadeInUp" data-delay="1s">
                                    <a href="shop-grid.html" class="theme-btn">Shop Now<i class="fas fa-arrow-right"></i></a>
                                    <a href="about.html" class="theme-btn theme-btn2">Learn More<i class="fas fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="hero-right" data-animation="fadeInRight" data-delay=".25s">
                                 <div class="hero-img">
                                    <div class="hero-img-item">
                                       <button type="button"><i class="far fa-plus"></i></button>
                                       <div class="hero-img-content">
                                          <img src="{{ asset('images/hero/03.png') }}">
                                          <div class="hero-img-info">
                                             <h6><a href="#">Modern Chair</a></h6>
                                             <p>Price: <span>$230</span></p>
                                             <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                                          </div>
                                       </div>
                                    </div>
                                    <img src="{{ asset('images/hero/03.png') }}">
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @include('categories.top', ['topCategories' => $topCategories])
         <div class="small-banner pb-100">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="row g-4">
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="banner-item">
                        <img src="{{ asset('images/banner/mini-banner-1.jpg') }}">
                        <div class="banner-content">
                           <p>Lounge Chair</p>
                           <h3>Eames Lounge Chair <br> Collectons</h3>
                           <a href="#">Shop Now</a>
                        </div>
                     </div>
                  </div>
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="banner-item">
                        <img src="{{ asset('images/banner/mini-banner-2.jpg') }}">
                        <div class="banner-content">
                           <p>Hot Sale</p>
                           <h3>Best Couch Sale <br> Collections</h3>
                           <a href="#">Discover Now</a>
                        </div>
                     </div>
                  </div>
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="banner-item">
                        <img src="{{ asset('images/banner/mini-banner-3.jpg') }}">
                        <div class="banner-content">
                           <p>Best Chair</p>
                           <h3>Best Chair <br> Up To <span>50%</span> Off</h3>
                           <a href="#">Discover Now</a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @include('products.trending', ['trendingItems' => $trendingItems])
         @include('products.featured', ['featuredItems' => $featuredItems])
         <div class="feature-area py-100">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="feature-wrap">
                  <div class="row g-0">
                     <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-item">
                           <div class="feature-icon">
                              <i class="fal fa-truck"></i>
                           </div>
                           <div class="feature-content">
                              <h4>Free Delivery</h4>
                              <p>Orders Over $120</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-item">
                           <div class="feature-icon">
                              <i class="fal fa-sync"></i>
                           </div>
                           <div class="feature-content">
                              <h4>Get Refund</h4>
                              <p>Within 30 Days Returns</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-item">
                           <div class="feature-icon">
                              <i class="fal fa-wallet"></i>
                           </div>
                           <div class="feature-content">
                              <h4>Safe Payment</h4>
                              <p>100% Secure Payment</p>
                           </div>
                        </div>
                     </div>
                     <div class="col-12 col-md-6 col-lg-3">
                        <div class="feature-item">
                           <div class="feature-icon">
                              <i class="fal fa-headset"></i>
                           </div>
                           <div class="feature-content">
                              <h4>24/7 Support</h4>
                              <p>Feel Free To Call Us</p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         @include('products.popular', ['featuredItems' => $featuredItems])
         <div class="choose-area pb-100">
            <div class="container">
               <div class="row g-4 align-items-center wow fadeInDown" data-wow-delay=".25s">
                  <div class="col-lg-4">
                     <span class="site-title-tagline">Why Choose Us</span>
                     <h2 class="site-title">We Provide Premium Quality Furniture For You</h2>
                  </div>
                  <div class="col-lg-4">
                     <p>There are many variations of passages available but the majority have suffered you are going
                        to use a passage you need to be sure alteration in some form by injected humour randomised
                        words even slightly believable.
                     </p>
                  </div>
                  <div class="col-lg-4">
                     <div class="choose-img">
                        <img src="{{ asset('images/choose/01.jpg') }}">
                     </div>
                  </div>
               </div>
               <div class="choose-content wow fadeInUp" data-wow-delay=".25s">
                  <div class="row g-4">
                     <div class="col-lg-4">
                        <div class="choose-item">
                           <div class="choose-icon">
                              <img src="{{ asset('images/icon/warranty.svg') }}">
                           </div>
                           <div class="choose-info">
                              <h4>3 Years Warranty</h4>
                              <p>It is a long established fact that a reader will be distracted by the readable
                                 content of a page when looking at its layout.
                              </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="choose-item">
                           <div class="choose-icon">
                              <img src="{{ asset('images/icon/price.svg') }}">
                           </div>
                           <div class="choose-info">
                              <h4>Affordable Price</h4>
                              <p>It is a long established fact that a reader will be distracted by the readable
                                 content of a page when looking at its layout.
                              </p>
                           </div>
                        </div>
                     </div>
                     <div class="col-lg-4">
                        <div class="choose-item">
                           <div class="choose-icon">
                              <img src="{{ asset('images/icon/delivery.svg') }}">
                           </div>
                           <div class="choose-info">
                              <h4>Free Shipping</h4>
                              <p>It is a long established fact that a reader will be distracted by the readable
                                 content of a page when looking at its layout.
                              </p>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="about-area pt-50 pb-120 mb-30">
            <div class="container">
               <div class="row align-items-center">
                  <div class="col-lg-6">
                     <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                        <div class="about-img">
                           <img class="img-1" src="{{ asset('images/about/01.jpg') }}">
                           <img class="img-2" src="{{ asset('images/about/02.jpg') }}">
                           <img class="img-3" src="{{ asset('images/about/03.jpg') }}">
                        </div>
                        <div class="about-experience">
                           <div class="about-experience-icon">
                              <img src="{{ asset('images/icon/experience.svg') }}">
                           </div>
                           <b>30 Years Of <br> Experience</b>
                        </div>
                        <div class="about-shape">
                           <img src="{{ asset('images/shape/01.png') }}">
                        </div>
                     </div>
                  </div>
                  <div class="col-lg-6">
                     <div class="about-right wow fadeInRight" data-wow-delay=".25s">
                        <div class="site-heading mb-3">
                           <span class="site-title-tagline justify-content-start">
                           <i class="flaticon-drive"></i> About Us
                           </span>
                           <h2 class="site-title">
                              We Provide Best And Quality <span>Furniture</span> Product For You
                           </h2>
                        </div>
                        <p>
                           We are standard text ever since the when an unknown printer took a galley of type and
                           scrambled it to make a type
                           specimen book. It has survived not only five but also the leap into electronic remaining
                           essentially by injected humour unchanged.
                        </p>
                        <div class="about-list">
                           <ul>
                              <li><i class="fas fa-check-double"></i> Streamlined Shipping Experience</li>
                              <li><i class="fas fa-check-double"></i> Affordable Modern Design</li>
                              <li><i class="fas fa-check-double"></i> Competitive Price & Easy To Shop</li>
                              <li><i class="fas fa-check-double"></i> We Made Awesome Products</li>
                           </ul>
                        </div>
                        <a href="contact.html" class="theme-btn mt-4">Discover More<i class="fas fa-arrow-right"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="deal-area bg pt-50 pb-50">
            <div class="deal-text-shape">Deal</div>
            <div class="container">
               <div class="deal-wrap wow fadeInUp" data-wow-delay=".25s">
                  <div class="deal-slider owl-carousel owl-theme">
                     <div class="deal-item">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="deal-content">
                                 <div class="deal-info">
                                    <span>This Week Deal</span>
                                    <h1>Best Sofa Furniture Deal</h1>
                                    <p>There are many variations of passages available but the majority have
                                       suffered alteration in some form
                                       by injected humour, or randomised words which don't look even slightly
                                       believable.
                                    </p>
                                 </div>
                                 <div class="deal-countdown">
                                    <div class="countdown" data-countdown="2025/12/30"></div>
                                 </div>
                                 <a href="#" class="theme-btn theme-btn2">Shop Now <i class="fas fa-arrow-right"></i></a>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="deal-img">
                                 <img src="{{ asset('images/product/b7.png') }}">
                                 <div class="deal-discount">
                                    <span>45%</span>
                                    <span>off</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="deal-item">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="deal-content">
                                 <div class="deal-info">
                                    <span>Get 45% Off</span>
                                    <h1>Best Sofa Furniture Deal</h1>
                                    <p>There are many variations of passages available but the majority have
                                       suffered alteration in some form
                                       by injected humour, or randomised words which don't look even slightly
                                       believable.
                                    </p>
                                 </div>
                                 <div class="deal-countdown">
                                    <div class="countdown" data-countdown="2025/12/30"></div>
                                 </div>
                                 <a href="#" class="theme-btn theme-btn2">Shop Now <i class="fas fa-arrow-right"></i></a>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="deal-img">
                                 <img src="{{ asset('images/product/b2.png') }}">
                                 <div class="deal-discount">
                                    <span>45%</span>
                                    <span>off</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                     <div class="deal-item">
                        <div class="row align-items-center">
                           <div class="col-lg-6">
                              <div class="deal-content">
                                 <div class="deal-info">
                                    <span>Get 20% Off</span>
                                    <h1>Best Sofa Furniture Deal</h1>
                                    <p>There are many variations of passages available but the majority have
                                       suffered alteration in some form
                                       by injected humour, or randomised words which don't look even slightly
                                       believable.
                                    </p>
                                 </div>
                                 <div class="deal-countdown">
                                    <div class="countdown" data-countdown="2025/12/30"></div>
                                 </div>
                                 <a href="#" class="theme-btn theme-btn2">Shop Now <i class="fas fa-arrow-right"></i></a>
                              </div>
                           </div>
                           <div class="col-lg-6">
                              <div class="deal-img">
                                 <img src="{{ asset('images/product/b10.png') }}">
                                 <div class="deal-discount">
                                    <span>45%</span>
                                    <span>off</span>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="product-list py-100">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="row g-4">
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="product-list-box">
                        <h2 class="product-list-title">On sale</h2>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/01.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/02.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/03.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="product-list-box">
                        <h2 class="product-list-title">Best Seller</h2>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/04.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/05.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/06.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-12 col-md-6 col-lg-4">
                     <div class="product-list-box">
                        <h2 class="product-list-title">Top Rated</h2>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/07.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/08.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                        <div class="product-list-item">
                           <div class="product-list-img">
                              <a href="shop-single.html"><img src="{{ asset('images/product/09.png') }}"></a>
                           </div>
                           <div class="product-list-content">
                              <h4><a href="shop-single.html">Simple Denim Chair</a></h4>
                              <div class="product-list-rate">
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="fas fa-star"></i>
                                 <i class="far fa-star"></i>
                              </div>
                              <div class="product-list-price">
                                 <del>60.00</del><span>$40.00</span>
                              </div>
                           </div>
                           <a href="#" class="product-list-btn" data-bs-placement="left" data-tooltip="tooltip" title="Add To Cart"><i class="far fa-shopping-bag"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="brand-area bg pt-50 pb-50">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="row">
                  <div class="col-12">
                     <div class="text-center">
                        <h2 class="site-title">Trusted by over <span>3.2k+</span> companies</h2>
                     </div>
                  </div>
               </div>
               <div class="brand-slider owl-carousel owl-theme">
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/01.png') }}">
                  </div>
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/02.png') }}">
                  </div>
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/03.png') }}">
                  </div>
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/04.png') }}">
                  </div>
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/05.png') }}">
                  </div>
                  <div class="brand-item">
                     <img src="{{ asset('images/brand/06.png') }}">
                  </div>
               </div>
               <div class="text-center">
                  <a href="#" class="theme-btn">View All Brands <i class="fas fa-arrow-right"></i></a>
               </div>
            </div>
         </div>
         <div class="gallery-area py-100">
            <div class="container">
               <div class="row">
                  <div class="col-lg-6 mx-auto">
                     <div class="site-heading text-center">
                        <span class="site-title-tagline">Our Gallery</span>
                        <h2 class="site-title">Let's Check Our Photo <span>Gallery</span></h2>
                     </div>
                  </div>
               </div>
               <div class="row g-4 popup-gallery">
                  <div class="col-md-12 col-lg-6">
                     <div class="gallery-item gallery-btn-active wow fadeInUp" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <div class="gallery-img-item gallery-item-1">
                              <button type="button"><i class="far fa-plus"></i></button>
                              <div class="gallery-img-content">
                                 <img src="{{ asset('images/product/16.png') }}">
                                 <div class="gallery-img-info">
                                    <h6><a href="#">Modern Sofa</a></h6>
                                    <p>Price: <span>$230</span></p>
                                    <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                           <div class="gallery-img-item gallery-item-2">
                              <button type="button"><i class="far fa-plus"></i></button>
                              <div class="gallery-img-content">
                                 <img src="{{ asset('images/product/16.png') }}">
                                 <div class="gallery-img-info">
                                    <h6><a href="#">Modern Sofa</a></h6>
                                    <p>Price: <span>$230</span></p>
                                    <a href="#" class="theme-btn">Buy Now<i class="fas fa-arrow-right"></i></a>
                                 </div>
                              </div>
                           </div>
                           <img class="main-img" src="{{ asset('images/gallery/01.jpg') }}">
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 col-lg-3">
                     <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <img src="{{ asset('images/gallery/02.jpg') }}">
                           <a class="popup-img gallery-link" href="{{ asset('images/gallery/02.jpg') }}"><i class="fal fa-plus"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 col-lg-3">
                     <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <img src="{{ asset('images/gallery/03.jpg') }}">
                           <a class="popup-img gallery-link" href="{{ asset('images/gallery/03.jpg') }}"><i class="fal fa-plus"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 col-lg-3">
                     <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <img src="{{ asset('images/gallery/04.jpg') }}">
                           <a class="popup-img gallery-link" href="{{ asset('images/gallery/04.jpg') }}"><i class="fal fa-plus"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-4 col-lg-3">
                     <div class="gallery-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <img src="{{ asset('images/gallery/05.jpg') }}">
                           <a class="popup-img gallery-link" href="assets/img/gallery/05.jpg"><i class="fal fa-plus"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-8 col-lg-6">
                     <div class="gallery-item wow fadeInDown" data-wow-delay=".25s">
                        <div class="gallery-img">
                           <img src="{{ asset('images/gallery/06.jpg') }}">
                           <a class="popup-img gallery-link" href="assets/img/gallery/06.jpg"><i class="fal fa-plus"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="testimonial-area bg ts-bg py-90">
            <div class="container">
               <div class="row">
                  <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-delay=".25s">
                     <div class="site-heading text-center">
                        <span class="site-title-tagline">Testimonials</span>
                        <h2 class="site-title">What Our Client <span>Say's</span></h2>
                     </div>
                  </div>
               </div>
               <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-delay=".25s">
                  <div class="testimonial-item">
                     <div class="testimonial-author">
                        <div class="testimonial-author-img">
                           <img src="{{ asset('images/testimonial/01.jpg') }}">
                        </div>
                        <div class="testimonial-author-info">
                           <h4>Sylvia H Green</h4>
                           <p>Customer</p>
                        </div>
                     </div>
                     <div class="testimonial-quote">
                        <p>
                           There are many variations of long passages available but the content majority have
                           suffered to the editor page when looking at its layout alteration in some injected.
                        </p>
                     </div>
                     <div class="testimonial-rate">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                     </div>
                     <div class="testimonial-quote-icon"><img src="{{ asset('images/icon/quote.svg') }}"></div>
                  </div>
                  <div class="testimonial-item">
                     <div class="testimonial-author">
                        <div class="testimonial-author-img">
                           <img src="{{ asset('images/testimonial/02.jpg') }}">
                        </div>
                        <div class="testimonial-author-info">
                           <h4>Gordo Novak</h4>
                           <p>Customer</p>
                        </div>
                     </div>
                     <div class="testimonial-quote">
                        <p>
                           There are many variations of long passages available but the content majority have
                           suffered to the editor page when looking at its layout alteration in some injected.
                        </p>
                     </div>
                     <div class="testimonial-rate">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                     </div>
                     <div class="testimonial-quote-icon"><img src="{{ asset('images/icon/quote.svg') }}"></div>
                  </div>
                  <div class="testimonial-item">
                     <div class="testimonial-author">
                        <div class="testimonial-author-img">
                           <img src="{{ asset('images/testimonial/03.jpg') }}">
                        </div>
                        <div class="testimonial-author-info">
                           <h4>Reid E Butt</h4>
                           <p>Customer</p>
                        </div>
                     </div>
                     <div class="testimonial-quote">
                        <p>
                           There are many variations of long passages available but the content majority have
                           suffered to the editor page when looking at its layout alteration in some injected.
                        </p>
                     </div>
                     <div class="testimonial-rate">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                     </div>
                     <div class="testimonial-quote-icon"><img src="{{ asset('images/icon/quote.svg') }}"></div>
                  </div>
                  <div class="testimonial-item">
                     <div class="testimonial-author">
                        <div class="testimonial-author-img">
                           <img src="{{ asset('images/testimonial/04.jpg') }}">
                        </div>
                        <div class="testimonial-author-info">
                           <h4>Parker Jimenez</h4>
                           <p>Customer</p>
                        </div>
                     </div>
                     <div class="testimonial-quote">
                        <p>
                           There are many variations of long passages available but the content majority have
                           suffered to the editor page when looking at its layout alteration in some injected.
                        </p>
                     </div>
                     <div class="testimonial-rate">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                     </div>
                     <div class="testimonial-quote-icon"><img src="{{ asset('images/icon/quote.svg') }}"></div>
                  </div>
                  <div class="testimonial-item">
                     <div class="testimonial-author">
                        <div class="testimonial-author-img">
                           <img src="{{ asset('images/testimonial/05.jpg') }}">
                        </div>
                        <div class="testimonial-author-info">
                           <h4>Heruli Nez</h4>
                           <p>Customer</p>
                        </div>
                     </div>
                     <div class="testimonial-quote">
                        <p>
                           There are many variations of long passages available but the content majority have
                           suffered to the editor page when looking at its layout alteration in some injected.
                        </p>
                     </div>
                     <div class="testimonial-rate">
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                        <i class="fas fa-star"></i>
                     </div>
                     <div class="testimonial-quote-icon"><img src="{{ asset('images/icon/quote.svg') }}"></div>
                  </div>
               </div>
            </div>
         </div>
         <div class="blog-area py-100">
            <div class="container">
               <div class="row">
                  <div class="col-lg-6 mx-auto">
                     <div class="site-heading text-center">
                        <span class="site-title-tagline">Our Blog</span>
                        <h2 class="site-title">Our Latest News & <span>Blog</span></h2>
                     </div>
                  </div>
               </div>
               <div class="row g-4">
                  <div class="col-md-6 col-lg-4">
                     <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="blog-item-img">
                           <img src="{{ asset('images/blog/01.jpg') }}">
                           <span class="blog-date"><i class="far fa-calendar-alt"></i> Aug 12, 2024</span>
                        </div>
                        <div class="blog-item-info">
                           <div class="blog-item-meta">
                              <ul>
                                 <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                 <li><a href="#"><i class="far fa-comments"></i> 2.5k Comments</a></li>
                              </ul>
                           </div>
                           <h4 class="blog-title">
                              <a href="#">There are many variations of passage available majority suffered.</a>
                           </h4>
                           <p>There are many variations available the majority have suffered alteration randomised
                              words.
                           </p>
                           <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                     <div class="blog-item wow fadeInDown" data-wow-delay=".25s">
                        <div class="blog-item-img">
                           <img src="{{ asset('images/blog/02.jpg') }}">
                           <span class="blog-date"><i class="far fa-calendar-alt"></i> Aug 15, 2024</span>
                        </div>
                        <div class="blog-item-info">
                           <div class="blog-item-meta">
                              <ul>
                                 <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                 <li><a href="#"><i class="far fa-comments"></i> 3.1k Comments</a></li>
                              </ul>
                           </div>
                           <h4 class="blog-title">
                              <a href="#">Contrary to popular belief making simply random text piece classical
                              latin.</a>
                           </h4>
                           <p>There are many variations available the majority have suffered alteration randomised
                              words.
                           </p>
                           <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right"></i></a>
                        </div>
                     </div>
                  </div>
                  <div class="col-md-6 col-lg-4">
                     <div class="blog-item wow fadeInUp" data-wow-delay=".25s">
                        <div class="blog-item-img">
                           <img src="{{ asset('images/blog/03.jpg') }}">
                           <span class="blog-date"><i class="far fa-calendar-alt"></i> Aug 18, 2024</span>
                        </div>
                        <div class="blog-item-info">
                           <div class="blog-item-meta">
                              <ul>
                                 <li><a href="#"><i class="far fa-user-circle"></i> By Alicia Davis</a></li>
                                 <li><a href="#"><i class="far fa-comments"></i> 1.6k Comments</a></li>
                              </ul>
                           </div>
                           <h4 class="blog-title">
                              <a href="#"> If you are going use passage you need sure there anything middle
                              text.</a>
                           </h4>
                           <p>There are many variations available the majority have suffered alteration randomised
                              words.
                           </p>
                           <a class="theme-btn" href="#">Read More<i class="fas fa-arrow-right"></i></a>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="newsletter-area pb-100">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="newsletter-wrap">
                  <div class="row">
                     <div class="col-lg-6 mx-auto">
                        <div class="newsletter-content">
                           <h3>Get <span>20%</span> Off Discount Coupon</h3>
                           <p>By Subscribe Our Newsletter</p>
                           <div class="subscribe-form">
                              <form action="#">
                                 <input type="email" class="form-control" placeholder="Your Email Address">
                                 <button class="theme-btn" type="submit">
                                 Subscribe <i class="far fa-paper-plane"></i>
                                 </button>
                              </form>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
         <div class="instagram-area pb-100">
            <div class="container wow fadeInUp" data-wow-delay=".25s">
               <div class="row">
                  <div class="col-lg-6 mx-auto">
                     <div class="site-heading text-center">
                        <h2 class="site-title">Instagram <span>@fameo</span></h2>
                     </div>
                  </div>
               </div>
               <div class="instagram-slider owl-carousel owl-theme">
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/01.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/02.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/03.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/04.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/05.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/06.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
                  <div class="instagram-item">
                     <div class="instagram-img">
                        <img src="{{ asset('images/instagram/07.jpg') }}">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </main>

@endsection

