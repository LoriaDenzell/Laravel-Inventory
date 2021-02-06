@extends('layouts.backend.app')
<title>View Purchase Order</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-file"></i> View Purchase Order</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">Transaction</li>
          <li class="breadcrumb-item active">Purchase</li>
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
                {{ $content->org_name ?? 'InvSys Admin'}}
                <small class="float-right">Date: {{ date('d F y', strtotime($data->date)) }}</small>
              </h4>
            </div>
          </div>
          
          <div class="row invoice-info">
            <div class="col-sm-6 invoice-col">
              <address>
                <strong>{{$data->user_modify->first_name ?? ''}} {{$data->user_modify->last_name ?? ''}}</strong><br>
                {{ $content->org_address ?? 'N/A' }} <br>
                Phone: {{ $content->org_contact ?? 'N/A' }} <br>
                Email: {{ $content->org_email ?? 'loriadenzell@gmail.com'}}
              </address>
            </div>
            <div class="col-sm-6 invoice-col">
              <b>Invoice #: {{$data->no_invoice}}</b><br>
            </div>
          </div>

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
          </div>
          <div class="row">
            <div class="col-6">
            </div>
            <div class="col-6">
              <div class="table-responsive">
                <table class="table">
                  <tr>
                    <th>Total Cost:</th>
                    <td><span>&#8369;</span>{{number_format($data->total, 0, '.', ',') }}</td>
                  </tr>
                </table>
              </div>
            </div>
          </div>

          <div class="row no-print">
            <div class = "col-12">
                <a href = "{{ route('purchase-order/print/{id}', $data->id)}}" target = "_blank" class = "btn btn-default"><i class = "fas fa-print"></i>Print</a>
                </div>
            </div>
          </div>
          
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
