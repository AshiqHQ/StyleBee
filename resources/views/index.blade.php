@extends('layouts.app')
@section('content')

<style>
  :root {
    --primary-gradient: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --secondary-gradient: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    --accent-color: #ff385c;
    --gold-accent: #ffd700;
    --neon-pink: #ff00ff;
    --electric-blue: #00ffff;
    --glass-effect: rgba(255, 255, 255, 0.95);
    --card-shadow: 0 20px 60px -15px rgba(0, 0, 0, 0.3);
    --card-shadow-hover: 0 30px 90px -20px rgba(255, 56, 92, 0.4);
    --transition-hq: all 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
    --transition-bounce: all 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55);
  }

  /* 1. Enhanced Slideshow & Reveal Animations */
  .animate {
    transition-duration: 1.2s !important;
    animation-timing-function: cubic-bezier(0.34, 1.56, 0.64, 1);
  }

  @keyframes float {
    0%, 100% { transform: translateY(0px) scale(1); }
    50% { transform: translateY(-20px) scale(1.02); }
  }

  @keyframes glow {
    0%, 100% { filter: drop-shadow(0 0 5px rgba(255, 56, 92, 0.3)); }
    50% { filter: drop-shadow(0 0 20px rgba(255, 56, 92, 0.7)); }
  }

  @keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
  }

  .slideshow .swiper-slide-active .slideshow-character__img {
    filter: drop-shadow(0 30px 60px rgba(0, 0, 0, 0.25));
    transform: scale(1.05);
    animation: float 6s ease-in-out infinite, glow 4s ease-in-out infinite;
    transition: var(--transition-hq);
  }

  .slideshow .swiper-slide {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 25%, #f093fb 50%, #f5576c 100%);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
  }

  @keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
  }

  /* 2. Category Carousel - Premium Circle Items */
  .category-carousel .swiper-slide {
    transition: var(--transition-bounce);
    padding: 12px;
  }

  .category-carousel .swiper-slide img {
    border-radius: 50%;
    background: linear-gradient(135deg, #ffffff 0%, #f5f7fa 100%);
    padding: 10px;
    box-shadow: var(--card-shadow);
    border: 3px solid transparent;
    transition: var(--transition-bounce);
    position: relative;
    overflow: hidden;
  }

  .category-carousel .swiper-slide img::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, transparent 30%, rgba(255, 255, 255, 0.4) 50%, transparent 70%);
    animation: shimmer 1s infinite linear;
  }

  .category-carousel .swiper-slide:hover img {
    transform: scale(1.1) rotate(8deg);
    border-color: var(--accent-color);
    box-shadow: var(--card-shadow-hover);
  }

  .category-carousel .menu-link {
    background: linear-gradient(45deg, #333, #555, #777);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-weight: 700;
    transition: var(--transition-hq);
    position: relative;
  }

  .category-carousel .menu-link:hover {
    background: linear-gradient(45deg, var(--accent-color), var(--neon-pink));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    transform: scale(1.1);
  }

  .js-countdown .countdown-unit {
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    padding: 5px;
    min-width: 50px;
    box-shadow: var(--card-shadow);
    transition: var(--transition-hq);
  }

  .js-countdown .countdown-unit::after,
  .js-countdown .countdown-unit::before {
      content: none !important;
      display: none !important;
  }

  .js-countdown .countdown-unit {
      margin: 0 5px; /* Adjust spacing as needed */
  }

  .countdown-num {
    font-size: 1.5rem;
    font-weight: 500;
    background: linear-gradient(45deg, var(--accent-color), var(--gold-accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.1);
  }

  /* 4. Product Card Premium Styling */
  .product-card_style3 {
    border: none;
    background: transparent;
    transition: var(--transition-bounce);
    position: relative;
  }

  .product-card_style3::before {
    content: '';
    position: absolute;
    top: -2px;
    left: -2px;
    right: -2px;
    bottom: -2px;
    border-radius: 15px;
    z-index: -1;
    opacity: 0;
    transition: var(--transition-hq);
  }

  .pc__img-wrapper {
      overflow: hidden;
      position: relative;
  }

  .pc__img {
      transition: transform 0.5s ease;
  }

  .pc__img-wrapper:hover .pc__img {
      transform: scale(1.1); /* 1.1 = 10% zoom. Increase to 1.2 for more zoom. */
  }

  /* Enhanced Action Buttons */
  .anim_appear-bottom {
    background: linear-gradient(135deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.85)) !important;
    backdrop-filter: blur(15px) saturate(180%);
    width: 100%;
    padding: 20px;
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    transition: var(--transition-bounce) !important;
    transform: translateY(100%);
    opacity: 0;
    border-radius: 0 0 15px 15px;
  }

  .product-card_style3:hover .anim_appear-bottom {
    transform: translateY(0);
    opacity: 1;
  }

  .btn-link_lg {
    position: relative;
    overflow: hidden;
    transition: var(--transition-hq);
  }

  .btn-link_lg::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 2px;
    background: linear-gradient(90deg, var(--accent-color), var(--electric-blue));
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.5s ease;
  }

  .btn-link_lg:hover::after {
    transform: scaleX(1);
  }

  .btn-link_lg:hover {
    color: var(--accent-color) !important;
    letter-spacing: 1.5px;
    transform: translateY(-2px);
  }

  /* 5. Premium Swiper Navigation Buttons */
  .products-carousel__prev,
  .products-carousel__next {
    width: 50px;
    height: 50px;
    background: var(--glass-effect);
    backdrop-filter: blur(10px);
    border-radius: 50%;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    color: #000;
    transition: var(--transition-bounce);
    z-index: 10;
    border: 1px solid rgba(255, 255, 255, 0.2);
  }

  /* 6. Banner Overlay Effects */
  .category-banner__item {
    border-radius: 20px;
    box-shadow: var(--card-shadow);
    overflow: hidden;
    position: relative;
    transition: var(--transition-hq);
  }

  .category-banner__item:hover {
    transform: translateY(-10px);
    box-shadow: var(--card-shadow-hover);
  }

  .category-banner__item-mark {
    background: linear-gradient(135deg, var(--accent-color), var(--neon-pink));
    top: 25px;
    left: 25px;
    border-radius: 35px;
    padding: 10px 25px;
    font-weight: 800;
    letter-spacing: 0.8px;
    box-shadow: 0 10px 20px rgba(255, 56, 92, 0.3);
    animation: pulse 1s infinite;
  }

  @keyframes pulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(0.8); }
  }

  .category-banner__item-content {
    background: linear-gradient(transparent, rgba(0, 0, 0, 0.5));
    padding: 50px ;
    border-radius: 0 0 20px 20px;
    color: #ffffff;
  }

  /* 7. Load More Button Enhancement */
  .btn-link_lg.default-underline::after {
    background: linear-gradient(90deg, var(--accent-color), var(--electric-blue), var(--neon-pink));
    height: 3px;
    width: 100%;
    transition: var(--transition-hq);
    background-size: 200% 100%;
    animation: shimmer 2s infinite linear;
  }

  /* Section Title Enhancement */
  .section-title {
    background: linear-gradient(45deg, #333, #555);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    position: relative;
    display: inline-block;
  }

  .section-title::after {
    content: '';
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, var(--accent-color), var(--electric-blue));
    border-radius: 2px;
  }

  /* Price Tag Enhancement */
  .money.price {
    background: linear-gradient(30deg, var(--accent-color), var(--gold-accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
  }

  /* Product Label Styling */
  .product-label {
    backdrop-filter: blur(10px);
    font-weight: 800;
    letter-spacing: 1px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    transition: var(--transition-hq);
  }

  .product-label:hover {
    transform: scale(1.1) rotate(5deg);
  }

  /* Background Enhancement for Main Container */
  .container.mw-1620 {
    background: linear-gradient(135deg, #ffffff 0%, #fafafa 100%);
    box-shadow: 0 30px 100px -20px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
  }

  .container.mw-1620::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 5px;
    background: linear-gradient(90deg, var(--accent-color), var(--electric-blue), var(--neon-pink));
    animation: shimmer 3s infinite linear;
  }
</style>

<script>
    (function (window, document) {
        var loader = function () {
            var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
            script.src = "https://sandbox.sslcommerz.com/embed.min.js?" + Math.random().toString(36).substring(7);
            tag.parentNode.insertBefore(script, tag);
        };

        window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
    })(window, document);
</script>

<main>
  <section class="swiper-container js-swiper-slider swiper-number-pagination slideshow" data-settings='{
      "autoplay": {
        "delay": 5000
      },
      "slidesPerView": 1,
      "effect": "fade",
      "loop": true
    }'>
    <div class="swiper-wrapper">
      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_right-center">
            <img loading="lazy" src="{{ asset('assets/images/home/slideshow-character1.png') }}" width="542" height="733"
              alt="Woman Fashion 1"
              class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
            <div class="character_markup type2">
              <p
                class="text-uppercase font-sofia mark-white-color animate animate_fade animate_btt animate_delay-10 mb-0">
                Dresses</p>
            </div>
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              New Arrivals</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Night Spring</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Dresses</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
              Now</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_right-center">
            <img loading="lazy" src="{{ asset('assets/images/home/slideshow-character2.png') }}" width="400" height="733"
              alt="Woman Fashion 1"
              class="slideshow-character__img animate animate_fade animate_btt animate_delay-9 w-auto h-auto" />
            <div class="character_markup">
              <p class="text-uppercase font-sofia fw-bold animate animate_fade animate_rtl animate_delay-10">Winter
              </p>
            </div>
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              New Arrivals</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Winter</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Dresses</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
              Now</a>
          </div>
        </div>
      </div>

      <div class="swiper-slide">
        <div class="overflow-hidden position-relative h-100">
          <div class="slideshow-character position-absolute bottom-0 pos_right-center">
            <img loading="lazy" src="{{ asset('assets/images/home/slideshow-character3.png') }}" width="400" height="690"
              alt="Woman Fashion 2"
              class="slideshow-character__img animate animate_fade animate_rtl animate_delay-10 w-auto h-auto" />
          </div>
          <div class="slideshow-text container position-absolute start-50 top-50 translate-middle">
            <h6 class="text_dash text-uppercase fs-base fw-medium animate animate_fade animate_btt animate_delay-3">
              New Arrivals</h6>
            <h2 class="h1 fw-normal mb-0 animate animate_fade animate_btt animate_delay-5">Special</h2>
            <h2 class="h1 fw-bold animate animate_fade animate_btt animate_delay-5">Discounts!</h2>
            <a href="#"
              class="btn-link btn-link_lg default-underline fw-medium animate animate_fade animate_btt animate_delay-7">Shop
              Now</a>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div
        class="slideshow-pagination slideshow-number-pagination d-flex align-items-center position-absolute bottom-0 mb-5">
      </div>
    </div>
  </section>
  <div class="container mw-1620 bg-white border-radius-10">
    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>
    <section class="category-carousel container">
      <h2 class="section-title text-center mb-3 pb-xl-2 mb-xl-4">You Might Like</h2>

      <div class="position-relative">
        <div class="swiper-container js-swiper-slider" data-settings='{
            "autoplay": {
              "delay": 5000
            },
            "slidesPerView": 8,
            "slidesPerGroup": 1,
            "effect": "none",
            "loop": true,
            "navigation": {
              "nextEl": ".products-carousel__next-1",
              "prevEl": ".products-carousel__prev-1"
            },
            "breakpoints": {
              "320": {
                "slidesPerView": 2,
                "slidesPerGroup": 2,
                "spaceBetween": 15
              },
              "768": {
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "spaceBetween": 30
              },
              "992": {
                "slidesPerView": 6,
                "slidesPerGroup": 1,
                "spaceBetween": 45,
                "pagination": false
              },
              "1200": {
                "slidesPerView": 8,
                "slidesPerGroup": 1,
                "spaceBetween": 60,
                "pagination": false
              }
            }
          }'>
          <div class="swiper-wrapper">
            @foreach ($categories as $category)
              <div class="swiper-slide">
                <img loading="lazy" class="w-100 h-auto mb-3" src="{{ asset('uploads/categories') }}/{{ $category->image }}" width="124"
                  height="124" alt="" />
              <div class="text-center">
                <a href="{{ route('shop.index', ['categories' => $category->id]) }}" class="menu-link fw-medium">{{ $category->name }}</a>
              </div>
            </div>
            @endforeach
            
          </div><!-- /.swiper-wrapper -->
        </div><!-- /.swiper-container js-swiper-slider -->
        <div
          class="products-carousel__prev products-carousel__prev-1 position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_prev_md" />
          </svg>
        </div><!-- /.products-carousel__prev -->
        <div
          class="products-carousel__next products-carousel__next-1 position-absolute top-50 d-flex align-items-center justify-content-center">
          <svg width="25" height="25" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg">
            <use href="#icon_next_md" />
          </svg>
        </div><!-- /.products-carousel__next -->
      </div><!-- /.position-relative -->
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="hot-deals container">
      <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Hot Deals</h2>
      <div class="row">
        <div
          class="col-md-6 col-lg-4 col-xl-20per d-flex align-items-center flex-column justify-content-center py-4 align-items-md-start">
          <h2>Top Sale</h2>
          <h2 class="fw-bold">Up to 60% Off</h2>

          <div class="position-relative d-flex align-items-center text-center pt-xxl-4 js-countdown mb-3"
            data-date="1-7-2026" data-time="06:50">
            <div class="day countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">Days</span>
            </div>

            <div class="hour countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">Hours</span>
            </div>

            <div class="min countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">Mins</span>
            </div>

            <div class="sec countdown-unit">
              <span class="countdown-num d-block"></span>
              <span class="countdown-word text-uppercase text-secondary">Sec</span>
            </div>
          </div>

          <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium mt-3">View All</a>
        </div>
        <div class="col-md-6 col-lg-8 col-xl-80per">
          <div class="position-relative">
            <div class="swiper-container js-swiper-slider" data-settings='{
                "autoplay": {
                  "delay": 5000
                },
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "effect": "none",
                "loop": false,
                "breakpoints": {
                  "320": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 2,
                    "spaceBetween": 14
                  },
                  "768": {
                    "slidesPerView": 2,
                    "slidesPerGroup": 3,
                    "spaceBetween": 24
                  },
                  "992": {
                    "slidesPerView": 3,
                    "slidesPerGroup": 1,
                    "spaceBetween": 30,
                    "pagination": false
                  },
                  "1200": {
                    "slidesPerView": 4,
                    "slidesPerGroup": 1,
                    "spaceBetween": 30,
                    "pagination": false
                  }
                }
              }'>
              <div class="swiper-wrapper">

                @foreach ($sproducts as $sproduct)
                  
                <div class="swiper-slide product-card product-card_style3">
                  <div class="pc__img-wrapper">
                    <a href="{{ route('shop.product.details', ['product_slug'=>$sproduct->slug]) }}">
                      <img loading="lazy" src="{{ asset('uploads/products/' . $sproduct->image) }}" width="258" height="313"
                        alt="{{ $sproduct->name }}" class="pc__img">
                    </a>
                  </div>

                  <div class="pc__info position-relative">
                    <h6 class="pc__title"><a href="{{ route('shop.product.details', ['product_slug'=>$sproduct->slug]) }}">{{ $sproduct->name }}</a></h6>
                    <div class="product-card__price d-flex">
                      <span class="money price text-secondary">
                        @if ($sproduct->sale_price)
                            <s>BDT: {{ $sproduct->regular_price }}</s> BDT: {{ $sproduct->sale_price }}
                        @else
                            BDT: {{ $sproduct->regular_price }}
                        @endif
                      </span>
                    </div>
                  </div>
                </div>
                @endforeach

              </div><!-- /.swiper-wrapper -->
            </div><!-- /.swiper-container js-swiper-slider -->
          </div><!-- /.position-relative -->
        </div>
      </div>
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="category-banner container">
      <div class="row">
        <div class="col-md-6">
          <div class="category-banner__item border-radius-10 mb-5">
            <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product1.jpg') }}" width="690" height="665"
              alt="" />
            <div class="category-banner__item-mark">
              Starting at BDT 2999
            </div>
            <div class="category-banner__item-content">
              <h3 class="mb-0">Khaadi Punjabi</h3>
              <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="category-banner__item border-radius-10 mb-5">
            <img loading="lazy" class="h-auto" src="{{ asset('assets/images/products/product2.jpg') }}" width="690" height="665"
              alt="" />
            <div class="category-banner__item-mark">
              Starting at BDT 2999
            </div>
            <div class="category-banner__item-content">
              <h3 class="mb-0">Nakshi Saree</h3>
              <a href="{{ route('shop.index') }}" class="btn-link default-underline text-uppercase fw-medium">Shop Now</a>
            </div>
          </div>
        </div>
      </div>
    </section>

    <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

    <section class="products-grid container">
      <h2 class="section-title text-center mb-3 pb-xl-3 mb-xl-4">Featured Products</h2>

      <div class="row">
        @foreach ($fproducts as $fproduct)

        <div class="col-6 col-md-4 col-lg-3">
          <div class="product-card product-card_style3 mb-3 mb-md-4 mb-xxl-5">
            <div class="pc__img-wrapper">
              <a href="{{ route('shop.product.details', ['product_slug'=>$fproduct->slug]) }}">
                <img loading="lazy" src="{{ asset('uploads/products/' . $fproduct->image) }}" width="330" height="400"
                  alt="{{ $fproduct->name }}" class="pc__img">
              </a>
            </div>

            <div class="pc__info position-relative">
              <h6 class="pc__title"><a href="{{ route('shop.product.details', ['product_slug'=>$fproduct->slug]) }}">{{ $fproduct->name }}</a></h6>
              <div class="product-card__price d-flex align-items-center">
                <span class="money price text-secondary">
                  @if ($fproduct->sale_price)
                      <s>BDT: {{ $fproduct->regular_price }}</s> BDT: {{ $fproduct->sale_price }}
                  @else
                      BDT: {{ $fproduct->regular_price }}
                  @endif
                </span>
              </div>
            </div>
          </div>
        </div>

        @endforeach
      </div><!-- /.row -->

      <div class="text-center mt-2">
        <a class="btn-link btn-link_lg default-underline text-uppercase fw-medium" href="{{ route('shop.index') }}">Load More</a>
      </div>
    </section>
  </div>

  <div class="mb-3 mb-xl-5 pt-1 pb-4"></div>

</main>
@endsection