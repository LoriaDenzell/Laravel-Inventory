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
  <!-- Main content -->
  <section class="invoice">
    <!-- title row -->
    <div class="row">
      <div class="col-12">
        <h2 class="page-header">
          <i class="fas fa-globe"></i> The key to life is POSITIVI-Tea
          <small class="float-right">Date: {{ date('d F y', strtotime($data[0]->date)) }}</small>
        </h2>
      </div>
      <!-- /.col -->
    </div>
    <!-- info row -->
    <div class="row invoice-info">
      <div class="col-sm-4 invoice-col">
        From
        <address>
            <strong>{{$data[0]->user_modify->first_name ?? ''}} {{$data[0]->user_modify->last_name ?? ''}}</strong><br>
            101 Morayta Ave<br>
            Manila, Metro Manila<br>
            Phone: (804) 123-5432<br>
            Email: ddloria@fit.edu.ph
        </address>
      </div>
      <!-- /.col -->
      
      <div class="col-sm-4 invoice-col">
        <b>Invoice #{{$data[0]->no_invoice}}</b><br>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Product</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
            <?php
                foreach($detail as $detail):
            ?>
            <tr>
                <td>{{$detail->product_name}}</td>
                <td>{{number_format($detail->total, 0, '.', ',') }}</td>
                <td><span>&#8369;</span>{{number_format($detail->price, 0, '.', ',') }}</td>
                <td><span>&#8369;</span>{{number_format($detail->price * $detail->total, 0, '.', ',') }}</td>
            </tr>
            <?php
                endforeach;
            ?>
          </tbody>
        </table>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->

    <div class="row">
      <!-- accepted payments column -->
      <div class="col-6">
        
      </div>
      <!-- /.col -->
      <div class="col-6">

        <div class="table-responsive">
          <table class="table">
          <tr>
            <th>Subtotal:</th>
            <td><span>&#8369;</span>{{number_format($data[0]->total, 0, '.', ',') }}</td>
            </tr>
            <tr>
            <th>Tax (30%)</th>
            <td><span>&#8369;</span>{{number_format($data[0]->total * 0.13, 0, '.', ',') }}</td>
            </tr>
            <tr>
            <th>Total:</th>
            <td><span>&#8369;</span>{{number_format($data[0]->total * 1.13, 0, '.', ',') }}</td>
          </tr>
          </table>
        </div>
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </section>
  <!-- /.content -->
</div>
<!-- ./wrapper -->

<script type="text/javascript"> 
  window.addEventListener("load", window.print());
</script>
</body>
</html>
