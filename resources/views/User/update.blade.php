@extends('layouts.backend.app')
<title>Update Profile</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-user"></i> User</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">User</li>
          <li class="breadcrumb-item active">Update Profile</li>
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
            <h3 class="card-title"><i class="fas fa-user-edit"></i> Update Profile</h3>
          </div>
          
          <form class="form-horizontal" method = "POST" action = "{{ route('user.update', $data->id) }}">
            @method('PUT')
            @csrf
            <div class="card-body">
              <strong><font color="red">*</font> Indicates required fields.</strong>
              <div class="form-group row">
                <div class="col-md-4">
                  <label for="first_name" class="col-sm-6 col-form-label">First Name <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">F</span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="first_name" 
                            value = "{{ $data->first_name}}" 
                            name = "first_name" 
                            placeholder="First Name" 
                            maxlength= "35"
                            required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="last_name" class="col-sm-6 col-form-label">Last Name <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">L</span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="last_name" 
                            value = "{{ $data->last_name}}" 
                            name = "last_name" 
                            placeholder="Last Name" 
                            maxlength= "35"
                            required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">Gender <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                    </div>
                    <select class = "form-control" id = "gender" name = "gender" required>
                      <option value = "M" {{$data->gender === 'Male' ? 'selected' : ''}}>Male</option>
                      <option value = "F" {{$data->gender === 'Female' ? 'selected' : ''}}>Female</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">Contact No. <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="user_contact" 
                            value = "{{ $data->user_contact}}" 
                            name = "user_contact" 
                            onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                            placeholder="Contact No." 
                            minlength="3"
                            maxlength= "20"
                            required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="contact" class="col-sm-6 col-form-label">Birth Date <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                    </div>
                    <input type="date" 
                            class="form-control" 
                            id="birthdate"  
                            value = "{{$data->birthdate}}" 
                            name = "birthdate" 
                            placeholder="Birth Date" 
                            required>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">E-Mail Address <font color="red">*</font></label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-at"></i></span>
                    </div>
                    <input type="text" 
                            class="form-control" 
                            id="email" 
                            value = "{{ $data->email}}" 
                            name = "email" 
                            placeholder="E-Mail Address" 
                            maxlength= "200"
                            required>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="card-footer">
              <button type="submit" id = "submit_update" class="btn btn-default float-right">Submit</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@push('js')
<script>
  birthdate.max = new Date().toISOString().split("T")[0];
    $("input").on("keyup", function(){
        if($(this).val() != ""){
            $("#submit_update").prop('disabled', false);
        } else {
            $("#submit_update").prop('disabled', true);
        }
    });
</script>
@endpush