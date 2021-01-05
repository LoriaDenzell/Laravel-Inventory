@extends('layouts.backend.app')
<title>Update Product</title>
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
                <li class="breadcrumb-item active">Update Product</li>
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
                <h3 class="card-title">Edit Product</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method = "POST" action = "{{ route('product.update', $data[0]->product_id) }}" enctype = "multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body">
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
                                value = "{{ $data[0]->product_code}}" 
                                name = "product_code" 
                                maxlength = "15"
                                placeholder="Product Code" 
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
                                value = "{{ $data[0]->product_name}}" 
                                name = "product_name" 
                                placeholder="Product Name" 
                                maxlength = "150"
                                required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="product_type" class="col-sm-6 col-form-label">Product Type <font color="red">*</font></label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-box-open"></i></span>
                        </div>
                        <select name="category_status" id="category_status" class="custom-select" required>
                          @foreach($categories as $category)
                            <option value= "{{ $category->category_id}}" {{  $data[0]->product_type ==  $category->category_id ? 'selected' : ''}}>{{ $category->category_name }}</option>
                          @endforeach
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="product_brand" class="col-sm-6 col-form-label">Product Brand <font color="red">*</font></label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-copyright"></i></span>
                        </div>
                        <input type="text" 
                                class="form-control" 
                                id="product_brand" 
                                value = "{{ $data[0]->product_brand}}" 
                                name = "product_brand" 
                                placeholder="Product Brand" 
                                maxlength = "150"
                                required>
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
                                value = "{{ $data[0]->stock_total}}"  
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
                                value = "{{ $data[0]->stock_available}}"  
                                name = "stock_available" 
                                placeholder="Stock Available" 
                                min="0" 
                                max="999999999" 
                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                                required>
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
                                value = "{{ $data[0]->purchase_price}}" 
                                name = "purchase_price" 
                                step="0.01" 
                                min="0" 
                                max="999999999" 
                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                                placeholder="1.00" required>
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
                                value = "{{ $data[0]->product_selling_price}}"  
                                name = "product_selling_price" 
                                step="0.01" 
                                min="0" 
                                max="999999999" 
                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
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
                          <option value = "1" {{ $data[0]->product_active===1 ? 'selected' : ''}}>Active</option>
                          <option value = "0" {{ $data[0]->product_active===0 ? 'selected' : ''}}>Inactive</option>
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
                                  name = "image">
                        </div>
                    </div>
                  </div>
                </div>

                <div class = "form-group row">
                    <div class="col-md-12">
                        <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-comments"></i></strong> Product Description</label>
                        <div class = "col-sm-12">
                            <textarea name = "product_information" class = "form-control" rows = "4">{{ $data[0]->product_information}}</textarea>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-default float-right">Submit</button>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card -->

          </div>
          <!--/.col (left) -->
          <!-- right column -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

@endsection

@push('js')

<script>
$( document ).ready(function() {

  var curretStockTotalVal;
  var curretStockAvailableVal;
  $('#stock_total').on("keyup", function(){  
    curretStockTotalVal = $(this).val(); 
    
    $("#stock_available").val(curretStockTotalVal);
    $('#stock_available').attr('max', curretStockTotalVal);
  });

  $('#stock_available').on("keyup", function(){  
    curretStockAvailableVal = $(this).val(); 
    
    if(curretStockAvailableVal > curretStockTotalVal){
      $(this).val(curretStockTotalVal); 
    }
    
  });
});
</script>

@endpush