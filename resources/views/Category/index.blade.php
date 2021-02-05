@extends('layouts.backend.app')
<title>Product Category Management</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Category</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active">Category</li>
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
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Product Category List</h3>
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-folder-plus"></i> Create New Category </button>
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
                <table id="categoryTbl" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all"><button type="button" name="bulk_deactivate" id="bulk_deactivate" class="btn btn-danger btn-xs"><i class = 'nav-icon fas fa-trash-alt'></i></button></th>
                      <th class="all">Product Category Name</th>
                      <th class="all">Added By</th>
                      <th class="all">Status</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>Product Category Name</th>
                      <th>Added By</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="categoryTrash" class="table table-bordered table-striped" data-toggle = "table2_"style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all"><button type="button" name="bulk_reactivate" id="bulk_reactivate" class="btn btn-success btn-xs"><i class="fas fa-trash-restore-alt"></i></button></th>
                      <th class="all">Product Category Name</th>
                      <th class="all">Added By</th>
                      <th class="all">Status</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>Product Category Name</th>
                      <th>Added By</th>
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
      <form class="form-horizontal" method="POST" action="{{ route('category.store') }}" autocomplete="off">
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
          <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i> Create Category</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
          <strong><font color="red">*</font> Indicates required fields.</strong>
          <div class="form-group row">
            <div class="col-md-4">
              <label for="category_name" class="col-sm-6 col-form-label">Category Name <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><strong>N</strong></span>
                </div>
                <input type="text" 
                        class="form-control" 
                        name = "category_name" 
                        maxlength = "190"
                        value="{{ old('category_name') }}"
                        placeholder="Category Name" 
                        required>
              </div>
            </div>

            <div class="col-md-4">
              <label for="no_invoice" class="col-sm-6 col-form-label">Category Status <font color="red">*</font></label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                </div>
                  <select name="category_status" class="custom-select">
                    <option value="1" selected>Active</option>
                    <option value="0">Inactive</option>
                  </select> 
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

{{-- Edit Modal Start --}}  
<div class="modal fade" id="modalEdit" tabIndex="-1" aria-labelledby="edit-modal-title" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="/category" id="editForm">
        @method('PUT')
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
          <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i> Update Product Category</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
          <strong><font color="red">*</font> Indicates required fields.</strong>
          <div class="form-group row">
            <div class="col-md-4">
              <label for="category_name" class="col-sm-6 col-form-label">Category Name <font color="red">*</font></label>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><strong>N</strong></span>
                </div>
                <input type="hidden" class="form-control" id="category_id" value="category_id" name = "category_id" required>
                <input type="text" 
                        class="form-control" 
                        id="category_name" 
                        name = "category_name" 
                        maxlength = "190"
                        value="category_name"
                        placeholder="Category Name" 
                        required>
              </div>
            </div>

            <div class="col-md-4">
              <label for="no_invoice" class="col-sm-6 col-form-label">Category Status <font color="red">*</font></label>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                </div>
                  <select class = "form-control" id = "category_status" name = "category_status" required>
                    <option value = "1">Active</option>
                    <option value = "0">Inactive</option>
                  </select>
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
{{-- Edit Modal End --}}  

@endsection
@push('js')
<script>
  $(function () {
    var table1 = $("#categoryTbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false, 
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('category/datatable')}}",
      order:[1, 'desc'],
      columns:[ 
        {data:"checkbox", searchable:false, sortable:false},
        {data:'category_name', name: 'Category Name'},
        {data:'user_modified', name: 'Added By'},
        {data:'category_status',
          render:function(data){
              return '<span class = "badge badge-success">Active</span>';
          },
        }, 
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    var table2 = $("#categoryTrash").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('category/datatableTrash')}}",
      order:[1, 'desc'],
      columns:[
        {data:"checkbox_t", searchable:false, sortable:false},
        {data:'category_name', name: 'Category Name'},
        {data:'user_modified', name: 'Added By'},
        {data:'category_status',
          render:function(data){
              return '<span class = "badge badge-danger">Deactivated</span>';
          },
        },
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });

    $('#modal-default').bind('show.bs.modal', function(e){
      var link = $(e.relatedTarget);
      $(this).find(".modal-body").load(link.attr("href"));
    });

    table1.on('click', '.edit', function(){

      $tr = $(this).closest('tr');
      if($($tr).hasClass('child')){
        $tr = $tr.prev('parent');
      }

      var data = table1.row($tr).data();
      
      $('#category_id').val(data['category_id']);
      $('#category_name').val(data['category_name']);
      $('#category_status').val(data['category_status']);

      $('#editForm').attr('action', '/master/category/'+data['category_id']);
      $('#modalEdit').modal('show');
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
    if(confirm("Are you sure you want to Deactivate this Categories?"))
    {
      $('.category_checkbox:checked').each(function(){
        id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{ url('category/deactivateCategory')}}",
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
    if(confirm("Are you sure you want to Reactivate this Categories?"))
    {
      $('.category_trash_checkbox:checked').each(function(){
        id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{ url('category/reactivateCategory')}}",
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