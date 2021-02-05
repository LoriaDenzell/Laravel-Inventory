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
          <li class="breadcrumb-item active">View Content Configuration</li>
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
          <div class="card-header bg-primary">
            <h3 class="card-title"><i class="fas fa-file"></i> View Content Configuration</h3>
            @if($content != null)
              <a class = "btn btn-success btn-sm float-right" href = "{{ url('content/'.$content->id.'/edit') }}" title = "Edit CMS"><i class="fas fa-edit"></i> Edit CMS</a>
            @else
              <a class = "btn btn-success btn-sm float-right" href = "{{ route('content.create') }}" title = "Edit CMS"><i class="fas fa-edit"></i> Create CMS</a>
            @endif
          </div>
          
          <form class="form-horizontal" method = "POST">
            <div class="card-body">
              @if($content == null)
                <div class="alert alert-warning alert-dismissible">
                  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                  <h5><i class="icon fas fa-ban"></i> Warning!</h5>
                  Content Management System has not been set-up yet.
                </div>
              @endif
              @csrf
              <div class="form-group row">
                <div class="col-md-4">
                  <label for="org_name" class="col-sm-12 col-form-label">Organization Name</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-qrcode"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="org_name" 
                            value = "{{ $content->org_name ?? 'N/A' }}" 
                            name = "org_name" 
                            placeholder="Organization Name" 
                            disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="org_contact" class="col-sm-12 col-form-label">Organization Contact Number</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="org_contact" 
                            value = "{{ $content->org_contact ?? 'N/A' }}" 
                            name = "org_contact" 
                            placeholder="Organization Contact Number" 
                            disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="org_email" class="col-sm-12 col-form-label">Organization E-Mail Address</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-at"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="org_email" 
                            value = "{{ $content->org_email ?? 'N/A' }}" 
                            name = "org_email" 
                            placeholder="Organization E-Mail Address" 
                            disabled>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                <div class="col-md-12">
                  <label for="org_address" class="col-sm-12 col-form-label">Organization Address</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-building"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="org_address" 
                            value = "{{ $content->org_address ?? 'N/A' }}" 
                            name = "org_address" 
                            placeholder="Organization Address" 
                            disabled>
                  </div>
                </div>
              </div>
              <div class="form-group row">
                  <div class="col-md-4">
                    <label for="high_pct" class="col-sm-12 col-form-label">High Percentage (Minimum Limit)</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-success" 
                              id="high_pct"  
                              value = "{{ $content->high_pct ?? 'N/A'  }}" 
                              name = "high_pct" 
                              disabled>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="ave_pct" class="col-sm-12 col-form-label">Average Percentage (Minimum Limit)</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-warning" 
                              id="ave_pct"  
                              value = "{{ $content->ave_pct ?? 'N/A'  }}" 
                              name = "ave_pct" 
                              disabled>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <label for="low_pct" class="col-sm-12 col-form-label">Minimum Percentage (Minimum Limit)</label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-percent"></i></span>
                      </div>
                      <input type="number" 
                              class="form-control border border-danger" 
                              id="low_pct"  
                              value = "{{ $content->low_pct ?? 'N/A'  }}" 
                              name = "low_pct" 
                              disabled>
                    </div>
                  </div>
              </div>
              <div class="form-group row">
                <div class="col-md-4">
                  <label for="max_activities" class="col-sm-12 col-form-label">Max Records to be Displayed</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                    </div>
                    <input type="number" 
                            class="form-control" 
                            id="max_activities"  
                            value = "{{ $content->max_activities ?? '0' }}" 
                            name = "max_activities" 
                            placeholder="Max Records to be Displayed" 
                            disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="tax_pct" class="col-sm-12 col-form-label">Taxable Percentage</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                    </div>
                    <input type="number" 
                            class="form-control" 
                            id="tax_pct"  
                            value = "{{ $content->tax_pct ?? '1' }}" 
                            name = "tax_pct" 
                            placeholder="Max Records to be Displayed" 
                            disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="product_type" class="col-sm-12 col-form-label">Last Modification by</label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user-edit"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="user_name"  
                            value = "{{ $content->user_modify->first_name ?? 'N/A'  }} {{ $content->user_modify->last_name ?? 'N/A'  }}" 
                            name = "user_name" 
                            placeholder="User Name" 
                            disabled>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection
