<!DOCTYPE html>

<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--Core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/jquery-ui-1.10.1.custom.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/bootstrap-reset.css') }}" rel="stylesheet">
    <link href="{{ asset('font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/bootstrap-switch.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/bootstrap-fileupload.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/datepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/timepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/daterangepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/datetimepicker.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/multi-select.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jquery.tagsinput.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/select2.css') }}" />


    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="js/ie8-responsive-file-warning.js"></script><![endif]-->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <!-- DataTables -->
    <!-- <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">   -->

    <!-- Pace style -->
    <link rel="stylesheet" href="{{ asset('css/pace.min.css') }}">
    <!-- Bootstrap datatables -->
    <link href="{{ asset('css/dataTables.bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('css/jquery-confirm.min.css') }}" rel="stylesheet">

    <!-- Custom styles for this template -->

    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('css/style-responsive.css') }}" rel="stylesheet"/>
    @yield('style')

    <title>{{ settingValue('site_title') }}</title>

    <script>
        var base_url = '{{ url("") }}';
        var admin_url = '{{ url("admin") }}';
    </script>

</head>
<body>

    <section id="container">

        
        @yield('content')

      
    </section>

    <!-- Scripts -->

     <!--Core js-->

    <!-- <script src="{{ asset('js/jquery.js') }}"></script> -->

    <script src="{{ asset('js/jquery-3.4.1.js') }}"></script>
    <script src="{{ asset('js/jquery-ui-1.10.1.custom.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.dcjqaccordion.2.7.js') }}"></script>
    <script src="{{ asset('js/jquery.scrollTo.min.js') }}"></script>
    <script src="{{ asset('js/jquery.slimscroll.js') }}"></script>
    <script src="{{ asset('js/jquery.nicescroll.js') }}"></script>
    <script src="{{ asset('js/bootstrap-switch.js') }}"></script>
    <script src="{{ asset('js/jquery-confirm.min.js') }}"></script>

    <!--common script init for all pages-->
    <script src="{{ asset('js/scripts.js') }}"></script>


    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/flot-chart/excanvas.min.js"></script><![endif]-->

    <script src="{{ asset('js/jquery.scrollTo.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('js/app.min.js') }}"></script>

    <!-- datetimepicker-->
    <script src="{{ asset('js/moment.min.js') }}"></script>

    <!-- PACE -->
    <script src="{{ asset('js/pace.min.js') }}"></script>

    <!-- Common js-->
    <script src="{{ asset('js/common.js') }}"></script>

    <!-- Bootstrap Validator-->
    <script src="{{ asset('js/validator.min.js') }}"></script>


    <script type="text/javascript" src="{{ asset('js/bootstrap-fileupload.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/wysihtml5-0.3.0.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-wysihtml5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-datepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js//bootstrap-datetimepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/daterangepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/bootstrap-timepicker.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.multi-select.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jquery.quicksearch.js') }}"></script>
    <script src="{{ asset('js/jquery.tagsinput.js') }}"></script>
    <!-- Select 2-->
    <script type="text/javascript" src="{{ asset('js/select2.min.js') }}"></script>

    <!-- overlay-->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/src/loadingoverlay.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@1.5.4/extras/loadingoverlay_progress/loadingoverlay_progress.min.js"></script>

    @include('admin.sections.notification')

    @yield('scripts')

</body>

</html>

