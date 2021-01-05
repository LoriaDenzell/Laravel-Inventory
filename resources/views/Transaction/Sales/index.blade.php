@extends('layouts.backend.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush
<title>Sales Order Management</title>
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
      <div class="row mb-2">
          <div class="col-sm-6">
              <h1 class="m-0 text-dark">Sales Order Management</h1>
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
        <div class="card">
          <div class="card-header bg-success">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Sales Order List</h3>
            <a class = "btn btn-info btn-sm float-right" href = "{{ route('sales.create')}}" title = "Create"><i class="fas fa-file-alt"></i> Create Sales Order</a>
          </div>
          <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
            <li class = "nav-item">
                <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
            </li>
            <li class = "nav-item">
                <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
            </li>
          </ul>
          <!-- /.card-header -->
          <div class="card-body"> 
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="sales_tbl" class="table table-bordered table-striped" style = "width: 100%;">
                  <thead>
                  <tr> 
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Revenue</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  
                  <tfoot>
                  <tr>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Revenue</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="sales_trash" class="table table-bordered table-striped" data-toggle = "table2_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead> 
                    
                    <tfoot>
                    <tr>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

@push('js')

<script>

  $(document).ready(function() {
    $("#sales_tbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('sales/datatable')}}",
      order:[0, 'desc'],
      columns:[
        {data:'invoice_no', name: 'invoice_no'},
        {data:'shop_name', name: 'shop_name'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'action', name: 'action', searchable: true, sortable: true}
      ]
    });

    $("#sales_trash").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('sales/datatableTrash')}}",
      order:[0, 'desc'],
      columns:[
        {data:'invoice_no', name: 'invoice_no'},
        {data:'shop_name', name: 'shop_name'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });

    $('#sales_trash').on('click', '.btn-danger[data-remote]', function (e) 
    { 
      e.preventDefault(); 
      console.log('ok');
    });

    /*$('.deleteForm').submit(function(event) {
      event.preventDefault();
      console.log('LOL I am working!');
      $(this).append('{{ csrf_field() }}');
      $(this).append('{{ method_field('DELETE') }}');
      if (confirm('Are you sure you want to PERMANENTLY delete this data?')) {
      event.currentTarget.submit();
    });*/

  });

  /*$('.delete-user').click(function(e){
      e.preventDefault() // Don't post the form, unless confirmed
      if (confirm('Are you sure?')) {
          // Post the form
          $(e.target).closest('form').submit() // Post the surrounding form
      }
  });*/

  function deleteSalesData(dt){
    if(confirm("Are you sure you want to PERMANENTLY delete this data?")){
      $.ajax({
        type: 'GET',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function(response){
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
    return false;
  }
  
  function deactivateSalesData(dt){
    if(confirm("Are you sure you want to deactivate this data?")){
      $.ajax({
        type: 'GET',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function(response){
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
    return false;
  }

  function undoTrash(dt){
    if(confirm("Are you sure you want to activate this data?")){
      $.ajax({
        type: 'POST',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function(response){
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
    return false;
  }
</script>
@endpush