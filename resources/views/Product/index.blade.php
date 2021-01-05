@extends('layouts.backend.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush
<title>Products Management</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Product</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Product</li>
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
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Product List</h3>
                <a class = "btn btn-info btn-sm float-right" href = "{{ route('product.create')}}" title = "Create"><i class="fab fa-dropbox"></i> Create Product</a>
            </div>
            <!-- /.card-header -->
            <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
                <li class = "nav-item">
                    <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
                </li>
                <li class = "nav-item">
                    <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
                </li>
            </ul>
            <div class="card-header">
              <div class="alert alert-info">
                <h5><i class="icon fas fa-info"></i> Info!</h5>
                Enter a quantity of stock to highlight all <strong>Avaiable stocks</strong> that are below entered quantity
              </div>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-boxes"></i></span>
                </div>
                <input class = "form-control minStock" type = "number" id = "minStock" name = "minStock" placeholder = "Minimum Stock Warning" oninput="this.value = Math.abs(this.value)" min = 0/>
              </div>
            </div>
            <div class="card-body">
              <div class = "tab-content">
                <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                  <table id="productTable" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                    <thead>
                        <tr>
                            <th class="all">Code</th>
                            <th class="all">Name</th>
                            <th class="all">Category</th>
                            <th class="all">Stock Available</th>
                            <th class="all">Selling Price</th>
                            <th class="all">Status</th>
                            <th class="all">Action</th>
                        </tr>
                    </thead>
                 
                    <tfoot>
                        <tr>
                          <th class="all">Code</th>
                          <th class="all">Name</th>
                          <th class="all">Category</th>
                          <th class="all">Stock Available</th>
                          <th class="all">Selling Price</th>
                          <th class="all">Status</th>
                          <th class="all">Action</th>
                        </tr>
                    </tfoot>
                  </table>
                </div>

                <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                  <table id="productTrashBin" class="table table-bordered table-striped" data-toggle = "table2_"style = "width: 100%;">
                    <thead>
                      <tr>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Category</th>
                          <th>Stock Available</th>
                          <th>Selling Price</th>
                          <th>Status</th>
                          <th>Action</th>
                      </tr>
                    </thead>
                  
                    <tfoot>
                      <tr>
                          <th>Code</th>
                          <th>Name</th>
                          <th>Category</th>
                          <th>Stock Available</th>
                          <th>Selling Price</th>
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
{{--    Modal --}} 
<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Default Modal</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->
@endsection

@push('js')
<script>
  $(function () {
    var table1 = $("#productTable").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('product/datatable')}}",
      order:[0, 'desc'],
      columns:[
        {data:'product_code', name: 'Product Code'},
        {data:'product_name', name: 'Product Name'},
        {data:'product_type', name: 'Category'},
        {data:'stock_available', name: 'Available Stock'},
        {data: 'product_selling_price', name: 'Selling Price'},
        {data:'product_active',
          render:function(data){
            if(data == '1'){
              return '<span class = "badge badge-success">Active</span>';
            }
            if(data == '2' || data == '0'){
              return '<span class = "badge badge-warning">Inactive</span>';
            }
          },
        }, 
        {data:'action', name: 'Action', searchable: true, sortable:true},
      ]
    });
    
    var table2 = $("#productTrashBin").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('product/dataTableTrash')}}",
      order:[0, 'desc'],
      columns:[
        {data:'product_code', name: 'Product Code'},
        {data:'product_name', name: 'Product Name'},
        {data:'product_type', name: 'Category'},
        {data:'stock_available', name: 'Available Stock'},
        {data:'product_selling_price', name: 'Selling Price'},
        {data:'product_active',
          render:function(data){
            if(data == '2'){
              return '<span class = "badge badge-danger">Deleted</span>';
            }
          },
        },
        {data:'action', name: 'Action', searchable: true, sortable:true},
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

  $('#modal-default').bind('show.bs.modal', function(e){
    var link = $(e.relatedTarget);
    $(this).find(".modal-body").load(link.attr("href"));
  });

</script>
<script>

  function deleteData(dt){
    if(confirm("Are you sure you want to delete this data?")){
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
            console.log(response);
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
<script>
  $(function (){
    
    var minStockElement = $('.minStock');

    $(minStockElement).bind('input', function() { 
      var inputtedVal = minStockElement.val();

      $('#productTable tr td:nth-child(4)').each(function() {
        var currentStockVal = parseInt($(this).text().replace(/\D/g,''));
        
        var tableRow = $(this).closest("tr");

        if(currentStockVal <= inputtedVal){
          tableRow.addClass('table-warning');
        }else{
          tableRow.removeClass('table-warning');
        }
      });

      if($('#productTrashBin').is(":visible")){
        $('#productTrashBin tr td:nth-child(4)').each(function() {
          var currentStockVal = parseInt($(this).text().replace(/\D/g,''));
          var tableRow = $(this).closest("tr");

          if(currentStockVal <= inputtedVal){
            tableRow.addClass('table-warning');
          }else{
            tableRow.removeClass('table-warning');
          }
        });
      }
    });
  });
</script>
@endpush