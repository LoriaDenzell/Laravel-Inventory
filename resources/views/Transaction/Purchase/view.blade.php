@extends('layouts.backend.app')
<title>View Purchase Order</title>
@section('content')

 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0 text-dark"><i class="fas fa-file"></i> View Purchase Order</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item">Transaction</li>
              <li class="breadcrumb-item active">Purchase</li>
          </ol>
          </div><!-- /.col -->
      </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <!-- Main content -->
        <div class="invoice p-3 mb-3">
          <!-- title row -->
          <div class="row">
            <div class="col-12">
              <h4>
              The key to life is POSITIVI-Tea
                <!--<i class="fal fa-football-helmet"></i>-->
                <small class="float-right">Date: {{ date('d F y', strtotime($data[0]->date)) }}</small>
              </h4>
            </div>
            <!-- /.col -->
          </div>
          <!-- info row -->
          <div class="row invoice-info">
            <div class="col-sm-4 invoice-col">
              <address>
                <strong>{{$data[0]->user_modify->first_name ?? ''}} {{$data[0]->user_modify->last_name ?? ''}}</strong><br>
                M. Espiritu Street<br>
                Brgy. Ugong, Pasig City<br>
                Phone: (804) 123-5432<br>
                Email: thekeytolifeispositivitiea@gmail.com
              </address>
            </div>
                <?php 

                  $newAddress = '';
                  for ($i = 0; $i < strlen($data[0]->vendor->vendor_address ?? ''); $i++){
                    if($i % 35 == 0 && $i != 0)
                    {
                      $newAddress .= "<br>";
                    }else{
                      $newAddress .= $data[0]->vendor->vendor_address[$i]?? '';
                    }
                  }

                  echo $newAddress;
                ?>
              </address>
            </div>
            
            <div class="col-sm-4 invoice-col">
              <b>Invoice #: {{$data[0]->no_invoice}}</b><br>
              <?php

                if($data[0]->status == "order"){
                    $text = "Order";
                    $label = "info";
                }

                if($data[0]->status == "received"){
                    $text = "Received";
                    $label = "warning";
                }

              ?>
              <b>Status: </b> <span class='badge badge-{{ $label }}'><?php echo $text; ?></span><br>
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
                  <th>Name of Expense</th>
                  <th>Cost</th>
                </tr>
                </thead>
                <tbody>
                <?php

                    foreach($productDetail as $detail):

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
                    <th>Total Cost:</th>
                    <td><span>&#8369;</span>{{number_format($data[0]->total, 0, '.', ',') }}</td>
                  </tr>
                </table>
              </div>
            </div>
            <!-- /.col -->
          </div>
          <!-- /.row -->

          <!-- this row will not appear when printing -->
          <div class="row no-print">
            <div class = "col-12">
                <a href = "{{ route('transaction/purchase-order/print/{id}', $data[0]->id)}}" target = "_blank" class = "btn btn-default"><i class = "fas fa-print"></i>Print</a>
                </div>
            </div>
          </div>
        </div>
        <!-- /.invoice -->
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection
