@extends('layouts.backend.app')
<title>Content Management System</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Content Management System</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Update Content Configuration</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<!-- Main content -->
<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card card-info">
          <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-file"></i> Create Content Configuration</h3>
          </div>
          <form class="form-horizontal" action = "{{ route('content.update', $content->id) }}" method = "POST" autocomplete="off">
            <div class="card-body">
              @method('PUT')
              @if ($errors->any())
                <div class="alert alert-danger">
                  <ul>
                    @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              @csrf
              <strong><font color="red">*</font> Indicates required fields.</strong>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label for="org_name" class="col-sm-14 col-form-label">Organization Name <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                      </div>
                      <input type="text" 
                              class="form-control" 
                              id="org_name" 
                              name = "org_name" 
                              value = "{{ $content->org_name ?? 'N/A' }}" 
                              minlength="3"
                              maxlength="100"
                              required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="org_contact" class="col-sm-14 col-form-label">Organization Contact Number <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control" 
                              id="org_contact" 
                              name = "org_contact" 
                              value = "{{ $content->org_contact ?? 'N/A' }}" 
                              minlength="3"
                              maxlength="30"
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="org_email" class="col-sm-12 col-form-label">Organization E-Mail address <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-at"></i></span>
                      </div>
                      <input type="email" 
                              class="form-control" 
                              id="org_email" 
                              name = "org_email" 
                              value = "{{ $content->org_email ?? 'N/A' }}" 
                              minlength="8"
                              maxlength="50"
                              required>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label for="org_address" class="col-sm-12 col-form-label">Organization Address <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-building"></i></span>
                      </div>
                    <input type="text" 
                            class="form-control" 
                            id="org_address" 
                            name = "org_address" 
                            value = "{{ $content->org_address ?? 'N/A' }}" 
                            minlength="8"
                            maxlength="190"
                            required>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label for="high_pct" class="col-sm-12 col-form-label">High Percentage (Minimum Limit) <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-success" 
                              id="high_pct"   
                              name = "high_pct" 
                              value = "{{ $content->high_pct ?? 'N/A'  }}" 
                              min="1"
                              max="100"
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="ave_pct" class="col-sm-12 col-form-label">Average Percentage (Minimum Limit) <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-warning" 
                              id="ave_pct"  
                              name = "ave_pct" 
                              value = "{{ $content->ave_pct ?? 'N/A'  }}" 
                              min="1"
                              max="100"
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="low_pct" class="col-sm-12 col-form-label">Minimum Percentage (Minimum Limit) <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-danger" 
                              id="low_pct"  
                              name = "low_pct" 
                              value = "{{ $content->low_pct ?? 'N/A'  }}" 
                              min="1"
                              max="100"
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
                    </div>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-md-4">
                    <label for="max_activities" class="col-sm-12 col-form-label">Max Records to be Displayed <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control" 
                              id="max_activities"  
                              value = "{{ $content->max_activities ?? '0' }}" 
                              min="10"
                              name = "max_activities" 
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="tax_pct" class="col-sm-12 col-form-label">Taxable Percentage <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control" 
                              id="tax_pct"  
                              value = "{{ $content->tax_pct ?? '0' }}" 
                              min="1"
                              name = "tax_pct" 
                              onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                              required>
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
</section>
@endsection
