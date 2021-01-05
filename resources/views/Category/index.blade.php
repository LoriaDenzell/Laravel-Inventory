@extends('layouts.backend.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush
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
              <a class = "btn btn-info btn-sm float-right" href = "{{ route('category.create')}}" title = "Create"><i class="fas fa-folder-plus"></i> Create Product Category</a>
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
          <div class="card-body">
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="categoryTable" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all">Product Category Name</th>
                      <th class="all">Added By</th>
                      <th class="all">Status</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                
                  <tfoot>
                    <tr>
                      <th>Product Category Name</th>
                      <th>Added By</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="categoryTrashTable" class="table table-bordered table-striped" data-toggle = "table2_"style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all">Product Category Name</th>
                      <th class="all">Added By</th>
                      <th class="all">Status</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                
                  <tfoot>
                    <tr>
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

@endsection
@push('js')
<script>
  $(function () {
    var table1 = $("#categoryTable").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false, 
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('category/datatable')}}",
      order:[0, 'desc'],
      columns:[ 
        {data:'category_name', name: 'Category Name'},
        {data:'user_modified', name: 'Added By'},
        {data:'category_status',
          render:function(data){
            if(data == '1'){
              return '<span class = "badge badge-success">Active</span>';
            }
            if(data == '2' || data == '0'){
              return '<span class = "badge badge-warning">Inactive</span>';
            }
          },
        }, 
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    var table2 = $("#categoryTrashTable").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('category/datatableTrash')}}",
      order:[0, 'desc'],
      columns:[
        {data:'category_name', name: 'Category Name'},
        {data:'user_modified', name: 'Added By'},
        {data:'category_status',
          render:function(data){
            if(data == '2'){
              return '<span class = "badge badge-danger">Deleted</span>';
            }
          },
        },
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });
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
@endpush