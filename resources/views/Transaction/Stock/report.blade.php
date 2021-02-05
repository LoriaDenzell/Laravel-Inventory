@extends('layouts.backend.app')
<title>Stock Report</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Stock Report</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active">Stock</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Stock Report List</h3>
            @role('admin')
            <a class = "btn btn-info btn-sm float-right" href = "{{ route('transaction/stock')}}" title = "Create">Create</a>
            @endrole
          </div>
          <div class="card-header">
            <div class="alert alert-info">
              <h5><i class="icon fas fa-info"></i> Info!</h5>
            Enter a quantity of stock to highlight all <strong>total stocks</strong> that are below entered quantity
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
              </div>
              <input class = "form-control minStock" type = "number" id = "minStock" name = "minStock" placeholder = "Minimum Stock Warning" oninput="this.value = Math.abs(this.value)" min = 0/>
            </div>
            <div class="input-group mb-2">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
              </div>
              <input class = "form-control recordsFilter" type = "number" id = "recordsFilter" name = "recordsFilter" placeholder = "Records Found" value = "" disabled/>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table id="example1" class="table table-bordered table-striped" style = "width: 100%;">
              <thead>
              <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Stock Available</th>
                <th>Stock Total</th>
              </tr>
              </thead>
              
              <tfoot>
              <tr>
                <th>Code</th>
                <th>Name</th>
                <th>Stock Available</th>
                <th>Stock Total</th>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('js')
<script>
  $(function () {
    $("#example1").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{route('browse-product/datatable')}}",
      order:[1, 'desc'],
      columns:[
            {data:'product_code', name: 'product_code'},
            {data:'product_name', name: 'product_name'},
            {data:'stock_available', name: 'stock_available'},
            {data:'stock_total', name: 'stock_total'},
        ],
    });

    var minStockElement = $('.minStock');

    $(minStockElement).bind('input', function() { 
      var inputtedVal = minStockElement.val();
      var records;

      $('#example1 tr td:nth-child(4)').each(function() {
        var currentStockVal = parseInt($(this).text());
        
        var tableRow = $(this).closest("tr");

        if(currentStockVal <= inputtedVal){
          tableRow.addClass('table-warning');
          records = $('.table-warning').length;
        }else{
          tableRow.removeClass('table-warning');
        }
      });
      
      $('.recordsFilter').val(records);
    });
  });
</script>
@endpush