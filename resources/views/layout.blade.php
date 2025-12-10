<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <title>@yield('title') | {{config('app.name')}}</title>
  {{--
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="assets/img/favicon.png" rel="icon">
  <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon"> --}}

  @yield('customcss')
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link
    href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Montserrat:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="{{asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/swiper/swiper-bundle.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/aos/aos.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/glightbox/css/glightbox.min.css')}}" rel="stylesheet">
  <link href="{{asset('assets/vendor/drift-zoom/drift-basic.css')}}" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="{{asset('assets/css/main.css')}}" rel="stylesheet">
</head>

<body class="index-page">

  <header id="header" class="header sticky-top">
    <!-- Top Bar -->
    <div class="top-bar py-2">
      <div class="container-fluid container-xl">
        <div class="row align-items-center">
          <div class="col-lg-4 d-none d-lg-flex">
            <div class="top-bar-item">
              <i class="bi bi-telephone-fill me-2"></i>
              <span>Need help? Call us: </span>
              <a href="tel:+1234567890">+261 34 00 000 00</a>
            </div>
          </div>

          <div class="col-lg-4 col-md-12 text-center">
            <div class="announcement-slider swiper init-swiper">
              <script type="application/json" class="swiper-config">
                {
                  "loop": true,
                  "speed": 600,
                  "autoplay": {
                    "delay": 5000
                  },
                  "slidesPerView": 1,
                  "direction": "vertical",
                  "effect": "slide"
                }
              </script>
              <div class="swiper-wrapper">
                <div class="swiper-slide">🚚 Free shipping on orders over $50</div>
                <div class="swiper-slide">💰 30 days money back guarantee.</div>
                <div class="swiper-slide">🎁 20% off on your first order</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Header -->
      <div class="main-header">
        <div class="container-fluid container-xl">
          <div class="d-flex py-3 align-items-center justify-content-between">

            <!-- Logo -->
            <a href="{{route('public.home')}}" class="logo d-flex align-items-center">
              <!-- Uncomment the line below if you also wish to use an image logo -->
              <!-- <img src="assets/img/logo.webp" alt=""> -->
              <h1 class="sitename">{{config('app.name')}}</h1>
            </a>

            <!-- Actions -->
            <div class="header-actions d-flex align-items-center justify-content-end">

              <!-- Mobile Search Toggle -->
              <button class="header-action-btn mobile-search-toggle d-xl-none" type="button" data-bs-toggle="collapse"
                data-bs-target="#mobileSearch" aria-expanded="false" aria-controls="mobileSearch">
                <i class="bi bi-search"></i>
              </button>

              <!-- Account -->
              <div class="dropdown account-dropdown">
                <button class="header-action-btn" data-bs-toggle="dropdown">
                  <i class="bi bi-person"></i>
                </button>
                <div class="dropdown-menu">
                  <div class="dropdown-header">
                    <h6>Welcome to <span class="sitename">Shopzone</span></h6>
                    <p class="mb-0">Access account &amp; manage orders</p>
                  </div>

                  {{-- Si connecté --}}
                  @auth
                    <div class="dropdown-body">
                      <a class="dropdown-item d-flex align-items-center" href="">
                        <i class="bi bi-person-circle me-2"></i>
                        <span>My Profile</span>
                      </a>
                      <a class="dropdown-item d-flex align-items-center" href="">
                        <i class="bi bi-bag-check me-2"></i>
                        <span>My Orders</span>
                      </a>
                      <a class="dropdown-item d-flex align-items-center" href="">
                        <i class="bi bi-heart me-2"></i>
                        <span>My Wishlist</span>
                      </a>
                      <a class="dropdown-item d-flex align-items-center" href="">
                        <i class="bi bi-gear me-2"></i>
                        <span>Settings</span>
                      </a>
                      <hr>
                      <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item d-flex align-items-center text-danger">
                          <i class="bi bi-box-arrow-right me-2"></i>
                          <span>Logout</span>
                        </button>
                      </form>
                    </div>
                  @endauth

                  {{-- Si NON connecté --}}
                  @guest
                    <div class="dropdown-footer">
                      <a href="{{ route('login') }}" class="btn btn-primary w-100 mb-2">Sign In</a>
                      {{-- <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">Register</a> --}}
                    </div>
                  @endguest
                </div>
              </div>

              <!-- Wishlist -->
              <a href="#" class="header-action-btn d-none d-md-block">
                <i class="bi bi-heart"></i>
                <span class="badge">0</span>
              </a>

              <!-- Cart -->
              <a href="#" class="header-action-btn">
                <i class="bi bi-cart3"></i>
                <span class="badge">3</span>
              </a>

              <!-- Mobile Navigation Toggle -->
              <i class="mobile-nav-toggle d-xl-none bi bi-list me-0"></i>

            </div>
          </div>
        </div>
      </div>

      <!-- Navigation -->
      <div class="header-nav">
        <div class="container-fluid container-xl position-relative">
          <nav id="navmenu" class="navmenu">
            <ul>
              <li>
                <a href="{{ route('public.home') }}"
                  class="{{ Route::currentRouteName() == 'public.home' ? 'active' : '' }}">
                  Nos produits
                </a>
              </li>

              <li><a href="#">Category</a></li>

              <li>
                <a href="{{ route('public.about') }}"
                  class="{{ Route::currentRouteName() == 'public.about' ? 'active' : '' }}">
                  About
                </a>
              </li>

              <li><a href="#">Cart</a></li>

              <li>
                <a href="{{ route('public.contact') }}"
                  class="{{ Route::currentRouteName() == 'public.contact' ? 'active' : '' }}">
                  Contact
                </a>
              </li>
            </ul>
          </nav>
        </div>
      </div>


      <!-- Mobile Search Form -->
      <div class="collapse" id="mobileSearch">
        <div class="container">
          <form class="search-form">
            <div class="input-group">
              <input type="text" class="form-control" placeholder="Search for products">
              <button class="btn" type="submit">
                <i class="bi bi-search"></i>
              </button>
            </div>
          </form>
        </div>
      </div>

  </header>

  <main class="main">
    {{-- Messages Flash --}}
    <div class="container mt-3">

      {{-- Toasts Success & Error --}}
      <div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 2000;">

        @if(session('success'))
          <div class="toast align-items-center text-bg-success border-0 show" role="alert">
            <div class="d-flex">
              <div class="toast-body">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
          </div>
        @endif

        @if(session('error'))
          <div class="toast align-items-center text-bg-danger border-0 show" role="alert">
            <div class="d-flex">
              <div class="toast-body">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
          </div>
        @endif

      </div>


      {{-- Validation errors --}}
      @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          <i class="bi bi-x-circle me-2"></i>
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

    </div>
    @yield('content')
  </main>

  {{-- Footer --}}
  <footer id="footer" class="footer dark-background">
    <div class="footer-main">
      <div class="container">
        <div class="row gy-4">
          <div class="col-lg-4 col-md-6">
            <div class="footer-widget footer-about">
              <a href="index.html" class="logo">
                <span class="sitename">{{config('app.name')}}</span>
              </a>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam in nibh vehicula, facilisis magna ut,
                consectetur lorem. Proin eget tortor risus.</p>

              <div class="social-links mt-4">
                <h5>Connect With Us</h5>
                <div class="social-icons">
                  <a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a>
                  <a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a>
                  <a href="#" aria-label="Twitter"><i class="bi bi-twitter-x"></i></a>
                  <a href="#" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>
                  <a href="#" aria-label="Pinterest"><i class="bi bi-pinterest"></i></a>
                  <a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a>
                </div>
              </div>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Shop</h4>
              <ul class="footer-links">
                <li><a href="category.html">New Arrivals</a></li>
                <li><a href="category.html">Bestsellers</a></li>
                <li><a href="category.html">Women's Clothing</a></li>
                <li><a href="category.html">Men's Clothing</a></li>
                <li><a href="category.html">Accessories</a></li>
                <li><a href="category.html">Sale</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-2 col-md-6 col-sm-6">
            <div class="footer-widget">
              <h4>Support</h4>
              <ul class="footer-links">
                <li><a href="support.html">Help Center</a></li>
                <li><a href="account.html">Order Status</a></li>
                <li><a href="shiping-info.html">Shipping Info</a></li>
                <li><a href="return-policy.html">Returns &amp; Exchanges</a></li>
                <li><a href="#">Size Guide</a></li>
                <li><a href="{{route('public.contact')}}">Contact Us</a></li>
              </ul>
            </div>
          </div>

          <div class="col-lg-4 col-md-6">
            <div class="footer-widget">
              <h4>Contact Information</h4>
              <div class="footer-contact">
                <div class="contact-item">
                  <i class="bi bi-geo-alt"></i>
                  <span>123 Fashion Street, New York, NY 10001</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-telephone"></i>
                  <span>+1 (555) 123-4567</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-envelope"></i>
                  <span>hello@example.com</span>
                </div>
                <div class="contact-item">
                  <i class="bi bi-clock"></i>
                  <span>Monday-Friday: 9am-6pm<br>Saturday: 10am-4pm<br>Sunday: Closed</span>
                </div>
              </div>

              <div class="app-buttons mt-4">
                <a href="#" class="app-btn">
                  <i class="bi bi-apple"></i>
                  <span>App Store</span>
                </a>
                <a href="#" class="app-btn">
                  <i class="bi bi-google-play"></i>
                  <span>Google Play</span>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <div class="container">
        <div class="row gy-3 align-items-center">
          <div class="col-lg-6 col-md-12">
            <div class="copyright">
              <p>© <span>Copyright</span> <strong class="sitename">ShopZone</strong>. All Rights Reserved.</p>
            </div>
            <div class="credits mt-1">
              <!-- All the links in the footer should remain intact. -->
              <!-- You can delete the links only if you've purchased the pro version. -->
              <!-- Licensing information: https://bootstrapmade.com/license/ -->
              <!-- Purchase the pro version with working PHP/AJAX contact form: [buy-url] -->
              Designed by <a href="#">NERKALY</a>
            </div>
          </div>

          <div class="col-lg-6 col-md-12">
            <div class="d-flex flex-wrap justify-content-lg-end justify-content-center align-items-center gap-4">
              <div class="payment-methods">
                <div class="payment-icons">
                  <i class="bi bi-credit-card" aria-label="Credit Card"></i>
                  <i class="bi bi-paypal" aria-label="PayPal"></i>
                  <i class="bi bi-apple" aria-label="Apple Pay"></i>
                  <i class="bi bi-google" aria-label="Google Pay"></i>
                  <i class="bi bi-shop" aria-label="Shop Pay"></i>
                  <i class="bi bi-cash" aria-label="Cash on Delivery"></i>
                </div>
              </div>

              <div class="legal-links">
                <a href="tos.html">Terms</a>
                <a href="privacy.html">Privacy</a>
                <a href="tos.html">Cookies</a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center"><i
      class="bi bi-arrow-up-short"></i></a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="{{asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/php-email-form/validate.js')}}"></script>
  <script src="{{asset('assets/vendor/swiper/swiper-bundle.min.js')}}"></script>
  <script src="{{asset('assets/vendor/aos/aos.js')}}"></script>
  <script src="{{asset('assets/vendor/glightbox/js/glightbox.min.js')}}"></script>
  <script src="{{asset('assets/vendor/drift-zoom/Drift.min.js')}}"></script>
  <script src="{{asset('assets/vendor/purecounter/purecounter_vanilla.js')}}"></script>

  <!-- Main JS File -->
  <script src="{{asset('assets/js/main.js')}}"></script>

  @yield('customjs')
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const toastElList = [].slice.call(document.querySelectorAll('.toast'))
      const toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, { delay: 4000 });
      });
      toastList.forEach(toast => toast.show());
    });
  </script>
</body>

</html>