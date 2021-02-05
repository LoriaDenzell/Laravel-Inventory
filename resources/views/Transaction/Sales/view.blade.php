@extends('layouts.backend.app')
<title>View Sales Order</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0 text-dark"><i class="fas fa-file"></i> View Sales Order</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item">Transaction</li>
              <li class="breadcrumb-item active">Sales</li>
            </ol>
          </div>
      </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="invoice p-3 mb-3">
          <div class="row">
            <div class="col-12">
              <h4>
                {{ $content->org_name }}
                <small class="float-right">Date: {{ date('d F y', strtotime($data->date)) }}</small>
              </h4>
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
              To
              <address>
                <strong>{{ $data->customer }}</strong>
              </address>
            </div>
            <div class="col-sm-4 invoice-col">
              <b>Invoice #: {{$data->invoice_no}}</b><br>
            </div>
          </div>
          
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

          <div class="row no-print">
            <div class = "col-12">
                <a href = "{{ route('sales/print/{id}', $data->id) }}" target = "_blank" class = "btn btn-default"><i class = "fas fa-print"></i>Print</a>
                </div>
            </div>
          </div>

      </div>
    </div>
  </div>
</section>
@endsection