@extends('layouts.app')

@push('css')
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('asset/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('asset/dist/css/adminlte.min.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
@endpush

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6" style="float:none;margin:auto;">
            <div class="error-page">
                <h1 class="headline text-warning"> 401</h1>

                <div class="error-content">
                    <h2>
                        <i class="fas fa-exclamation-triangle text-warning"></i> Oops! Access not authorized.
                    </h2>
                    <p>
                        The request was a legal request, but the server is refusing to respond to it. 
                        For use when authentication is possible but has failed or not yet been provided
                        <a href="{{url('login')}}">return to login page</a>.
                        <br><br>
                        To learn more about HTTP messages visit this 
                        <a href = "https://www.w3schools.com/tags/ref_httpmessages.asp">link</a>
                    </p>
                </div>
            </div>
        </div>
        <!-- /.error-content -->
    </div>
    <!-- /.error-page -->
</section>
<!-- /.content -->
</div>

@endsection

@push('js')

<!-- jQuery -->
<script src="{{ asset('asset/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{ asset('asset/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{ asset('asset/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ asset('asset/dist/js/demo.js')}}"></script>

@endpush