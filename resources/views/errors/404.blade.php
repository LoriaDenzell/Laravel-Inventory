<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ config('app.name', 'Laravel') }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href = "{{ asset('asset/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- Toastr -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/toastr/toastr.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('asset/dist/css/adminlte.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/daterangepicker/daterangepicker.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/summernote/summernote-bs4.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
@auth
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        @include('layouts.backend.partials.topbar')
        @include('layouts.backend.partials.sidebar')
        <div class="content-wrapper">
            <main class="py-4">
                <section class="content">
                    <div class="container-fluid">
                        <div class="row">
                            <!-- left column -->
                            <div class="col-md-6" style="float:none;margin:auto;">
                                <div class="error-page">
                                    <h1 class="headline text-warning"> 401</h1>

                                    <div class="error-content">
                                        <h2>
                                            <i class="fas fa-exclamation-triangle text-warning"></i>  Oops! Page not found.
                                        </h2>
                                        <p>
                                            <strong>{{\Auth::user()->first_name}} {{\Auth::user()->last_name}} </strong> You seem lost! We could not find the page you were looking for.
                                            Meanwhile, you may <a href="{{url('/home')}}">return to dashboard</a>
                                            <br><br>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
             </main>
        </div>
    </div>
    @include('layouts.backend.partials.footer')
</body>
@endauth
@guest 
<body>
    <div class="app">
        @include('layouts.app')
        <main class="py-4">
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <!-- left column -->
                        <div class="col-md-6" style="float:none;margin:auto;">
                            <div class="error-page">
                                <h1 class="headline text-warning"> 404</h1>

                                <div class="error-content">
                                    <h2>
                                        <i class="fas fa-exclamation-triangle text-warning"></i>  Oops! Page not found.
                                    </h2>
                                    <p>
                                        We could not find the page you were looking for.
                                        Meanwhile, you may <a href="{{url('/')}}">return to home</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
</body>
@endguest


