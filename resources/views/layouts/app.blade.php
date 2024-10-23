<!DOCTYPE html>
<html lang="en">
   <!-- Mirrored from live.themewild.com/fameo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Oct 2024 21:36:47 GMT -->
   <head>
      <meta charset="UTF-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">

      <meta name="keywords" content>
      <title>Fameo - Furniture Store HTML5 Template</title>
      <meta name="google-site-verification" content="KfzyleiRDQ_CWIXC6T7dNfUqcbUxdX2DbHz657-VAOo" />
      <meta name="description" content="@yield('description')">
        <!-- CSRF Token -->
      <meta name="csrf-token" content="{{ csrf_token() }}">
      <meta name="author" content="CreativeLayers">
        <!-- Mobile Specific Metas -->
      <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
      <link rel="icon" type="image/x-icon" href="{{ asset('front_assets/img/logo/favicon.png') }}">
      <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/all-fontawesome.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/magnific-popup.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/nice-select.min.css') }}">
      <link rel="stylesheet" href="{{ asset('css/style-new.css') }}">
      <link href="{{ captcha_layout_stylesheet_url() }}" type="text/css" rel="stylesheet">

      <script>
        var base_url = '{{ url("") }}';
        var admin_url = '{{ url("admin") }}';
      </script>
   </head>

  
   <body>
      <div class="preloader">
         <div class="loader-ripple">
            <div></div>
            <div></div>
         </div>
      </div>
      @include('sections.header')

      @yield('content')

      @include('sections.footer')


      <!-- <script data-cfasync="false" src="../cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> -->
      <!-- <script src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script> -->
      <script src="{{ asset('js/email-decode.min.js') }}"></script>
      <script src="{{ asset('js/jquery-3.7.1.min.js') }}"></script>
      <script src="{{ asset('js/modernizr.min.js') }}"></script>
      <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
      <script src="{{ asset('js/imagesloaded.pkgd.min.js') }}"></script>
      <script src="{{ asset('js/jquery.magnific-popup.min.js') }}"></script>
      <script src="{{ asset('js/isotope.pkgd.min.js') }}"></script>
      <script src="{{ asset('js/jquery.appear.min.js') }}"></script>
      <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
      <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
      <script src="{{ asset('js/counter-up.js') }}"></script>
      <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
      <script src="{{ asset('js/jquery.nice-select.min.js') }}"></script>
      <script src="{{ asset('js/countdown.min.js') }}"></script>
      <script src="{{ asset('js/wow.min.js') }}"></script>
      <script src="{{ asset('js/main.js') }}"></script>
      <script src="{{ asset('front_assets/javascript/tether.min.js')}}"></script>
      <script src="{{ asset('front_assets/javascript/waypoints.min.js')}}"></script>
      <script src="{{asset('front_assets/javascript/easing.js')}}"></script>
      <script src="{{asset('front_assets/javascript/jquery.zoom.min.js')}}"></script>
      <script src="{{asset('front_assets/javascript/jquery.flexslider-min.js')}}"></script>
      <script src="{{asset('front_assets/javascript/jquery-ui.js')}}"></script>
      <script src="{{asset('front_assets/javascript/jquery.mCustomScrollbar.js')}}"></script>
      <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDHH2WyrHbuChuvGc1zkbY3LwiODEF8zGI&libraries=places"></script>
      <script src="{{asset('front_assets/javascript/gmap3.min.js')}}"></script>
      <script src="{{asset('front_assets/javascript/waves.min.js')}}"></script>
      <script src="{{ asset('js/validator.min.js') }}"></script>
      <script src="{{ asset('front_assets/javascript/jquery.countdown.js')}}"></script>

      <script src="{{asset('front_assets/js/scripts.js')}}"></script>

      <script src="{{asset('js/loadingoverlay.min.js')}}"></script>

      <script>
            $(document).ready(function () {
                $("a[title ~= 'BotDetect']").css('visibility', 'hidden');
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            })

        </script>

        @include('admin.sections.notification')
        @yield('scripts')

   </body>
   <!-- Mirrored from live.themewild.com/fameo/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 07 Oct 2024 21:36:52 GMT -->
</html>