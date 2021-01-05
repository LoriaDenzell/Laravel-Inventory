@extends('layouts.backend.app')
<title>View Product</title>
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
                <li class="breadcrumb-item active">View Product</li>
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
          <!-- left column -->
          <div class="col-md-12">
            <!-- Horizontal Form -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-file"></i> View Product</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method = "POST">
                @csrf
                <div class="card-body">
                  <!-- IMAGES -->
                  @if($data->product_image != NULL)
                    <div class="form-group row">
                      <div class="col-md-6 mx-auto">
                        <div class="card card-info">
                          <div class="card-header ">
                            <h3 class="card-title text-center "><strong><i class="fas fa-image"></i></strong> Product Image</h3>
                          </div>
                          <div class = "form-group row">
                            <div class="col-md-6">
                              <div class = "col-sm-6 mx-auto">
                                <img src = "{{asset('/storage/images/'.$data->product_image)}}" 
                                    alt = "img_product"
                                    height = "450"
                                    width = "390"
                                    />
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  @endif

                  <div class="form-group row">
                    <div class="col-md-4">
                      <label for="product_code" class="col-sm-6 col-form-label">Product Code</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                        </div>
                        <input type="text" 
                                class="form-control" 
                                id="product_code" 
                                value = "{{ $data->product_code}}" 
                                name = "product_code" 
                                placeholder="Product Code" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="product_name" class="col-sm-6 col-form-label">Product Name</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><strong>N</strong></span>
                        </div>
                        <input type="text" 
                                class="form-control" 
                                id="product_name" 
                                value = "{{ $data->product_name}}" 
                                name = "product_name" 
                                placeholder="Product Name" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="category_name" class="col-sm-6 col-form-label">Product Category</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                        </div>
                        <input type="text" 
                                class="form-control" 
                                id="category_name"  
                                value = "{{ $category }}" 
                                name = "category_name" 
                                placeholder="Product Type" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="product_brand" class="col-sm-6 col-form-label">Product Brand</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                        </div>
                        <input type="text" 
                                class="form-control" 
                                id="product_brand" 
                                value = "{{ $data->product_brand}}" 
                                name = "product_brand" 
                                placeholder="Product Brand" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="stock_total" class="col-sm-6 col-form-label">Stock Total</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        </div>
                        <input type="number" 
                                class="form-control" 
                                id="stock_total" 
                                value = "{{ $data->stock_total}}"  
                                name = "stock_total" 
                                placeholder="Stock Total" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="stock_available" class="col-sm-6 col-form-label">Stock Available</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                        </div>
                        <input type="number" 
                                class="form-control" 
                                id="stock_available" 
                                value = "{{ $data->stock_available}}"  
                                name = "stock_available" 
                                placeholder="Stock Available" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="purchase_price" class="col-sm-6 col-form-label">Purchase Price</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><strong>₱</strong></span>
                        </div>
                        <input type="number" 
                                class="form-control" 
                                id="purchase_price" 
                                value = "{{ $data->purchase_price}}" 
                                name = "purchase_price"
                                placeholder="1.00" disabled>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="product_selling_price" class="col-sm-6 col-form-label">Selling Price</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-tags"></i></span>
                        </div>
                        <input type="number" 
                                class="form-control" 
                                id="product_selling_price" 
                                value = "{{ $data->product_selling_price}}" 
                                name = "product_selling_price"
                                placeholder="1.00" 
                                disabled>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="status" class="col-sm-6 col-form-label">Product Status</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select class = "form-control" id = "status" name = "status" disabled>
                          <option value = "1" {{ $data->product_active===1 ? 'selected' : ''}}>Active</option>
                          <option value = "0" {{ $data->product_active===0 ? 'selected' : ''}}>Inactive</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <label for="status" class="col-sm-8 col-form-label">Last modified by</label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input type="text" 
                          class="form-control" 
                          id="user_name"  
                          value = "{{ $data->user_modify->first_name }} {{ $data->user_modify->last_name }}" 
                          name = "user_name" 
                          placeholder="User Name" 
                          disabled>
                      </div>
                    </div>
                  </div>
                </div>

                <div class = "form-group row">
                    <div class="col-md-12">
                        <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-comments"></i></strong> Product Description</label>
                        <div class = "col-sm-12">
                            <textarea name = "product_information" 
                                      class = "form-control" 
                                      rows = "4" 
                                      disabled>{{ $data->product_information}}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
              </form>
            </div>
            
            <div class="card">
              <div class="card-header">
                <h3 class="card-title"><i class="fas fa-history"></i> Product Activity Log</h3>
              </div>
              
              <div class="card-body">
                <table id="prod_log_tbl" class="table table-bordered table-striped" style = "width: 100%;">
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
    <!-- /.content -->

@endsection

@push('js')
  <script>
  
  $(function () {
     
    $("#prod_log_tbl").DataTable({
      responsive:true,
      stateSave:false,
      scrollY:true,
      autoWidth: false,
      ajax: "{{ url('product/activities', [Request::segment(3)]) }}",
      order:[4, 'desc'],
      columns:[
        {data: function(row, arg1, arg2, meta)
        {
          var list = "";
            $.each(row.user,
            function(key,value){ 

              if(key == "first_name" || key == "last_name"){
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
            function(key,value){
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