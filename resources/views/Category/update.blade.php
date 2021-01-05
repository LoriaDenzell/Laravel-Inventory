@extends('layouts.backend.app')
<title>Update Product Category</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Product Category</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Master</li>
                <li class="breadcrumb-item active">Update Product Category</li>
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
                <h3 class="card-title">Update Product Category</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method = "POST" action = "{{ route('category.update', $data->category_id) }}" enctype = "multipart/form-data">
                @method('PUT')
                @csrf
                <div class="card-body">
                <strong><font color="red">*</font> Indicates required fields.</strong>
                  <div class="form-group row">
                    <div class="col-md-4">
                    <label for="category_name" class="col-sm-6 col-form-label">Category Name <font color="red">*</font></label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                        </div>
                          <input type="text" 
                                class="form-control" 
                                id="category_name" 
                                name = "category_name"
                                value = "{{ $data->category_name}}"  
                                maxlength = "150"
                                placeholder="Category Name" 
                                required>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <label for="no_invoice" class="col-sm-6 col-form-label">Category Status <font color="red">*</font></label>
                      <div class="input-group mb-3">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select class = "form-control" id = "category_status" name = "category_status" required>
                          <option value = "1" {{ $data->category_status===1 ? 'selected' : ''}}>Active</option>
                          <option value = "0" {{ $data->category_status===0 ? 'selected' : ''}}>Inactive</option>
                        </select>
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
