@extends('layouts.backend.app')
<title>Purchase Order Management</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Purchase Order Management</h1>
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

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header bg-secondary">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Purchase Order List</h3>
            <a class = "btn btn-info btn-sm float-right" href = "{{ route('purchase-order.create')}}" title = "Create"><i class="fas fa-file-alt"></i> Create Purchase Order</a>
          </div>
          <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
            <li class = "nav-item">
                <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
            </li>
            <li class = "nav-item">
                <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
            </li>
          </ul>
          <div class="card-body">
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="purchase_main_tbl" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
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

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="purchase_trash" class="table table-bordered table-striped" data-toggle = "table2_" style = "width: 100%;">
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
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
      <!-- /.col -->
    </div>
    <!-- /.row -->
  </div>
  <!-- /.container-fluid -->
</section>
<!-- /.content -->
@endsection

@push('js')
<script>

  $(function () {
    $.ajaxSetup({
      header:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
      }
    });

    $("#purchase_main_tbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{!! url('purchase-order/datatable') !!}",
      order:[0, 'desc'],
      columns:[
        {data:'no_invoice', name: 'no_invoice'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'status',
          render:function(data){
            if(data == 'order'){
              return '<span class = "badge badge-success">Order</span>';
            }
            if(data == 'received'){
              return '<span class = "badge badge-warning">Received</span>';
            }
          },
        },
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    var table2 = $("#purchase_trash").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('purchase-order/datatableTrash')}}",
      order:[0, 'desc'],
      columns:[
        {data:'no_invoice', name: 'no_invoice'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'status',
          render:function(data){
            if(data == 'order'){
              return '<span class = "badge badge-success">Order</span>';
            }
            if(data == 'received'){
              return '<span class = "badge badge-warning">Received</span>';
            }
          },
        },
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });
  });

  //If tab is clicked
  $(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });
  });
  
  function received(dt){
    if(confirm("Are you sure you want to received the product?")){
      $.ajax({
        type: 'POST',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function (response) {
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
  }
  
  function deletePurchaseOrder(dt){
    if(confirm("Are you sure you want to delete this data?") == true){
      $.ajax({
        type: 'DELETE',
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
    }else{
      return false;
    }
    return false;
  }

  function undoTrash(dt){
    if(confirm("Are you sure you want to activate this purchase order?")){
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
            alert("Error activating purchase order");
          }
      });
    }
    return false;
  }
</script>
@endpush