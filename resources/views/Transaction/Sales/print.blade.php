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
            <small class="float-right">Date: {{ date('d F y', strtotime($data[0]->date)) }}</small>
          </h2>
        </div>
      </div>
      <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
          From
          <address>
            <strong>{{$data[0]->user_modify->first_name ?? ''}} {{$data[0]->user_modify->last_name ?? ''}}</strong><br>
              {{ $content->org_address }} <br>
              Phone: {{ $content->org_contact  }} <br>
              Email: {{ $content->org_email  }}
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          To
          <address>
              <strong>{{ $data[0]->customer }}</strong>
          </address>
        </div>
        <div class="col-sm-4 invoice-col">
          <b>Invoice #: {{$data[0]->invoice_no}}</b><br>
        </div>
      </div>

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
              $overAllTotal = 0;
              foreach($detail as $detail):
                $overAllTotal += $detail->price * $detail->total;
            ?>
            <tr>
              <td>{{$detail->product->product_name}}</td>
              <td>{{number_format($detail->total, 0, '.', ',') }}</td>
              <td><span>&#8369;</span>{{number_format($detail->price, 0, '.', ',') }}</td>
              <td><span>&#8369;</span>{{number_format($detail->price * $detail->total, 0, '.', ',') }}</td>
            </tr>
            <?php
              endforeach;
              
              if($addons != null){
            ?>
              <tr>
                <th>Product Addon Name</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Subtotal</th>
              </tr>
            <?php
              foreach($addons as $add){
                $overAllTotal += $add->price;
            ?>
              <tr>
                <td>{{$add->addon->addon_name}}</td>
                <td>{{number_format($add->total_addon, 0, '.', ',') }}</td>
                <td><span>&#8369;</span>{{number_format($add->addon->addon_cost, 0, '.', ',') }}</td>
                <td><span>&#8369;</span>{{number_format($add->price, 0, '.', ',') }}</td>
              </tr>
            <?php 
                }
              }
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
                <th>Subtotal:</th>
                <td><span>&#8369;</span>{{number_format($overAllTotal, 0, '.', ',') }}</td>
              </tr>
              <tr>
                <th>Tax ({{ $content->tax_pct }}%)</th>
                @php 
                    $taxable = abs(($overAllTotal) * ($content->tax_pct / 100));
                    $total = abs($overAllTotal - $taxable);
                @endphp
                <td><span>&#8369;</span>{{number_format($taxable, 0, '.', ',') }}</td>
              </tr>
              <tr>
                <th>Total:</th>
                <td><span>&#8369;</span>{{number_format($total, 0, '.', ',') }}</td>
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
