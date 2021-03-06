@extends('layouts.backend.app')
<title>View Product Addon</title>
@section('content')

 <!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Product Addon</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">Master</li>
          <li class="breadcrumb-item active">View Product Addon</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-file"></i> View Product Addon</h3>
          </div>
          
          <form class="form-horizontal" method = "POST">
            <div class="card-body">
              @csrf
              <div class="form-group row">
                <div class="col-md-3">
                  <label for="addon_name" class="col-sm-8 col-form-label">Addon Name</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="addon_name" 
                            value = "{{ $addon->addon_name}}" 
                            name = "addon_name" 
                            placeholder="Product Addon Name" 
                            disabled>
                  </div>
                </div>
                <div class="col-md-3">
                <label for="no_invoice" class="col-sm-6 col-form-label">Addon Cost </label>
                <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><strong>₱</strong></span>
                    </div>
                      <input type="number" 
                          class="form-control" 
                          id="addon_cost" 
                          name = "addon_cost" 
                          step="0.01" 
                          min="0" 
                          max="999999999" 
                          value="{{ $addon->addon_cost}}" 
                          required disabled>
                </div>
              </div>
                <div class="col-md-3">
                  <label for="addon_active" class="col-sm-8 col-form-label">Addon Status</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                    </div>
                    <select class = "form-control" id = "addon_active" name = "addon_active" disabled>
                      <option value = "1" {{ $addon->addon_active===1 ? 'selected' : ''}}>Active</option>
                      <option value = "0" {{ $addon->addon_active===0 ? 'selected' : ''}}>Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-3">
                  <label for="product_type" class="col-sm-8 col-form-label">Last Modification by</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="user_name"  
                            value = "{{ $addon->user_modify->first_name }} {{ $addon->user_modify->last_name }}" 
                            name = "user_name" 
                            placeholder="User Name" 
                            disabled>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>

        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history"></i> Addon Activity Log</h3>
          </div>
          
          <div class="card-body">
            <table id="addon_log_tbl" class="table table-bordered table-striped" style = "width: 100%;">
              <thead>
                <tr>
                  <th>Made By</th>
                  <th>Description</th>
                  <th>Old Data</th>
                  <th>New Data</th>
                  <th>Date & Time</th>
                </tr>
              </thead>
              <tbody>
                
              </tbody>
              <tfoot>
                <th>Made By</th>
                <th>Description</th>
                <th>Old Data</th>
                <th>New Data</th>
                <th>Date & Time</th>
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
     
    $("#addon_log_tbl").DataTable({
      responsive:true,
      stateSave:false,
      scrollY:true,
      autoWidth: false,
      ajax: "{{ url('addon/activities', [Request::segment(3)]) }}",
      order:[4, 'desc'],
      columns:[
        {data: function(row, arg1, arg2, meta)
        {
          var list = "";
            $.each(row.user,
            function(key,value)
            {
              if(key == "first_name" || key == "last_name")
              {
                list += value + " ";
              }
            });
            return list;
        }, name: 'made_by'},
        {data:'description', name: 'description'},
        { data: function(row, arg1, arg2, meta)
          {
            var list = "";
            $.each(row.properties.old, 
            function(key,value)
            {
              list += key + ": " + value + "<br>";
            })
            return list;
          }, name: 'old'
        }, 
        {data: function(row, arg1, arg2, meta)
          {
            var list = "";
            $.each(row.properties.attributes, 
            function(key,value){
              list += key + ": " + value + "<br>";
            })
            return list;
          }, name: 'new'
        },
        {data:'created_at', name: 'created_at',
        searchable: false, sortable:true, fixedColumns: true},
      ]
    });
  });
  </script>
@endpush