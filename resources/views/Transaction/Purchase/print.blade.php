<!DOCTYPE html>
<html>
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
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('asset/dist/css/adminlte.min.css')}}">
    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  </head>
  <body>
    <div class="wrapper">
      <section class="invoice">
        <div class="row">
          <div class="col-12">
            <h2 class="page-header">
              <i class="fas fa-globe"></i> {{ $content->org_name }}
              <small class="float-right">Date: {{ date('d F y', strtotime($data->date)) }}</small>
            </h2>
          </div>
        </div>
        <div class="row invoice-info">
          <div class="col-sm-4 invoice-col">
            From
            <address>
                <strong>{{$data->user_modify->first_name ?? ''}} {{$data->user_modify->last_name ?? ''}}</strong><br>
                  {{ $content->org_address }} <br>
                  Phone: {{ $content->org_contact  }} <br>
                  Email: {{ $content->org_email  }}
            </address>
          </div>
          
          <div class="col-sm-4 invoice-col">
            <b>Invoice #{{$data->no_invoice}}</b><br>
          </div>
        </div>
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>Product</th>
                <th>Cost</th>
              </tr>
              </thead>
              <tbody>
                <?php
                    foreach($detail as $detail):
                ?>
                <tr>
                    <td>{{$detail->product_name}}</td>
                    <td><span>&#8369;</span>{{number_format($detail->price, 0, '.', ',') }}</td>
                </tr>
                <?php
                    endforeach;
                ?>
              </tbody>
            </table>
          </div>
        </div>

        <div class="row">
          <div class="col-6">
            
          </div>
          <div class="col-6">

            <div class="table-responsive">
              <table class="table">
              <tr>
                <tr>
                  <th>Total:</th>
                  <td><span>&#8369;</span>{{number_format($data->total * 1.13, 0, '.', ',') }}</td>
              </tr>
              </table>
            </div>
          </div>
        </div>
      </section>
    </div>
    <script type="text/javascript"> 
      window.addEventListener("load", window.print());
    </script>
  </body>
</html>
