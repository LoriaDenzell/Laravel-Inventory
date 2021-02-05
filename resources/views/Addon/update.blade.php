@extends('layouts.backend.app')
<title>Update Product Addon</title>
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
          <li class="breadcrumb-item active">Update Product Addon</li>
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
            <h3 class="card-title"><i class="nav-icon fas fa-edit"></i> Update Product Addon</h3>
          </div>

          <form class="form-horizontal" method = "POST" action = "{{ route('addon.update', $addon->id) }}">
            @method('PUT')
            <div class="card-body">
              <strong><font color="red">*</font> Indicates required fields.</strong>
              @csrf
              <div class="form-group row">
                <div class="col-md-4">
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
                          required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="no_invoice" class="col-sm-6 col-form-label">Addon Cost <font color="red">*</font></label>
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
                          onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                          placeholder="1.00" 
                          required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="addon_active" class="col-sm-8 col-form-label">Addon Status <font color="red">*</font></label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                    </div>
                    <select class = "form-control" id = "addon_active" name = "addon_active" required>
                      <option value = "1" {{ $addon->addon_active===1 ? 'selected' : ''}}>Active</option>
                      <option value = "0" {{ $addon->addon_active===0 ? 'selected' : ''}}>Inactive</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="card-footer">
                <button type="submit" class="btn btn-default float-right">Submit</button>
              </div>

            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection