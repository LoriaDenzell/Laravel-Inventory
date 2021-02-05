@extends('layouts.backend.app')
<title>Products Management</title>
@section('content')

 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active">Product</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12"> 
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Product List</h3>
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCreate"><i class="fab fa-dropbox"></i> Create New Product </button>
            @role('A')
              <a class ="btn btn-warning btn-sm float-right mr-1" href="{{ route('product-export') }}" title = "Export"><i class="fas fa-file-export"></i> Export Data</a>
            @endrole
          </div>
            
          <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
            <li class = "nav-item">
              <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
            </li>
            <li class = "nav-item">
              <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
            </li>
          </ul>

          <div class="card-header">
              
          </div>
          <div class="card-body">
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="productTable" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th><button type="button" name="bulk_deactivate" id="bulk_deactivate" class="btn btn-danger btn-xs"><i class = 'nav-icon fas fa-trash-alt'></i></button></th>
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
                      <th class="all"></th>
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
                      <th><button type="button" name="bulk_reactivate" id="bulk_reactivate" class="btn btn-success btn-xs"><i class="fas fa-trash-restore-alt"></i></button></th>
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
                      <th></th>
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
        </div>
      </div>
    </div>
  </div>
</section>

{{-- Create Modal Start --}}  
<div class="modal fade" id="modalCreate" tabIndex="-1" aria-labelledby="create-modal-title" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ route('product.store') }}" enctype="multipart/form-data">
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
          <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i> Create Product</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
          <strong><font color="red">*</font> Indicates required fields.</strong>
          <div class="form-group row">
            <div class="col-md-4">
              <label for="product_code" class="col-sm-6 col-form-label">Product Code <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                </div>
                <input type="text" 
                        class="form-control" 
                        id="product_code" 
                        name = "product_code" 
                        maxlength = "15"
                        placeholder="Product Code" 
                        value= "{{$latestProductCode}}"
                        readonly="true"
                        required>
              </div>
            </div>

            <div class="col-md-4">
              <label for="product_name" class="col-sm-6 col-form-label">Product Name <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><strong>N</strong></span>
                </div>
                <input type="text" 
                        class="form-control" 
                        id="product_name" 
                        name = "product_name" 
                        maxlength = "150"
                        placeholder="Product Name" 
                        required>
              </div>
            </div>
            <div class="col-md-4">
              <label for="product_type" class="col-sm-6 col-form-label">Product Category <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                </div>
                <select name="category_status" id="category_status" class="custom-select" required>
                  @foreach($categories as $category)
                    <option value="{{ $category->category_id }}">{{ $category->category_name }}</option>
                  @endforeach
                </select> 
              </div>
            </div>
            <div class="col-md-4">
              <label for="product_brand" class="col-sm-6 col-form-label">Product Brand </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                </div>
                <input type="text" 
                        class="form-control" 
                        id="product_brand" 
                        name = "product_brand" 
                        maxlength = "150"
                        placeholder="Product Brand">
              </div>
            </div>
            <div class="col-md-4">
              <label for="stock_total" class="col-sm-6 col-form-label">Stock Total <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                <input type="number" 
                        class="form-control" 
                        id="stock_total" 
                        name = "stock_total" 
                        placeholder="Stock Total"
                        min="0" 
                        max="999999999" 
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                        required>
              </div>
            </div>
            <div class="col-md-4">
              <label for="stock_available" class="col-sm-6 col-form-label">Stock Available <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                </div>
                <input type="number" 
                        class="form-control" 
                        id="stock_available" 
                        name = "stock_available" 
                        placeholder="Stock Available" 
                        min="0" 
                        max="999999999" 
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
              </div>
            </div>
            <div class="col-md-4">
              <label for="purchase_price" class="col-sm-6 col-form-label">Purchase Price <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><strong>₱</strong></span>
                </div>
                <input type="number" 
                        class="form-control" 
                        id="purchase_price" 
                        name = "purchase_price" 
                        step="0.01" 
                        min="0" 
                        max="999999999" 
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                        placeholder="1.00" 
                        required>
              </div>
            </div>
            <div class="col-md-4">
              <label for="product_selling_price" class="col-sm-6 col-form-label">Selling Price <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-tags"></i></span>
                </div>
                <input type="number" 
                        class="form-control" 
                        id="product_selling_price" 
                        name = "product_selling_price" 
                        step="0.01" 
                        min="0" 
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                        max="999999999" 
                        placeholder="1.00" 
                        required>
              </div>
            </div>
            <div class="col-md-4">
              <label for="status" class="col-sm-6 col-form-label">Product Status <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                </div>
                <select class = "form-control" id = "status" name = "status" required>
                  <option value = "1">Active</option>
                  <option value = "0">Inactive</option>
                </select>
              </div>
            </div>
            <div class="col-md-4">
              <label for="status" class="col-sm-6 col-form-label">Product Image </label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-image"></i></span>
                </div>
                <input class="form-control" 
                        type = "file" 
                        name = "image"/>
              </div>
            </div>
          </div>
          <div class = "form-group row">
            <div class="col-md-12">
              <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-info-circle"></i></strong> Product Description</label>
              <div class = "col-sm-12">
                  <textarea name = "product_information" class = "form-control" rows = "4"></textarea>
              </div>
            </div>
          </div>
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger float-right">Cancel</button>
          <button type="submit" class="btn btn-success float-right">Submit</button>
        </div>

      </form>
    </div> 
  </div>
</div>
{{-- Create Modal End --}}  
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
      order:[2, 'asc'],
      columns:[
        {data:"checkbox", sortable:false},
        {data:'product_code', name: 'Product Code'},
        {data:'product_name', name: 'Product Name'},
        {data:'product_type', name: 'Category'},
        {data:'stock_available', name: 'Available Stock'},
        {data: 'product_selling_price', name: 'Selling Price'},
        {data:'product_active',
          render:function(data){
              return '<span class = "badge badge-success">Active</span>';
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
      order:[2, 'desc'],
      columns:[
        {data:"checkbox_t", sortable:false},
        {data:'product_code', name: 'Product Code'},
        {data:'product_name', name: 'Product Name'},
        {data:'product_type', name: 'Category'},
        {data:'stock_available', name: 'Available Stock'},
        {data:'product_selling_price', name: 'Selling Price'},
        {data:'product_active',
          render:function(data){
              return '<span class = "badge badge-danger">Deactivated</span>';
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

  $(document).on('click', '#bulk_deactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Deactivate this Products/s?"))
    {
      $('.products_checkbox:checked').each(function(){
        id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{ url('product/deactivateProduct')}}",
          method:"get",
          data:{id:id},
          success:function(data)
          {
            alert(data);
            location.reload();
          }
        });
      }
      else
      {
        alert("Please select atleast one checkbox");
      }
    }
  });

  $(document).on('click', '#bulk_reactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Reactivate this Products/s?"))
    {
      $('.products_trash_checkbox:checked').each(function(){
          id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{ url('product/reactivateProduct')}}",
          method:"get",
          data:{id:id},
          success:function(data)
          {
            alert(data);
            location.reload();
          }
        });
      }
      else
      {
        alert("Please select atleast one checkbox");
      }
    }
  });

</script>
@endpush