@extends('layouts.backend.app')

@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark"><i class="fas fa-user"></i> User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active">Change Password</li>
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
            <h3 class="card-title"><i class="fas fa-asterisk"></i> Change Password</h3>
          </div>
          <!-- /.card-header -->
          
          <!-- FORM STARTS HERE -->
          <!-- form start -->
          <form class="form-horizontal" method = "POST" action = "{{ route('user.updatePassword', Auth::user()->id) }}">
            @csrf
            <div class="card-body">
              <div class="form-group row">
              <div class="col-md-12">
                
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                  <h5><i class="icon fas fa-info" d></i> Info!</h5>
                  This form will change your account password. 
                  <br><br>
                  <b>Guidelines</b> for a new Password: <br><br>
                  <ul>
                    <li> New password must not be the same with your current password. </li>
                    <li> New password and confirm password must be the same. </li>
                    <li> Minimum of 8 Characters for the new password.</li>
                  </ul>
              </div>
              <strong><font color="red">*</font> Indicates required fields.</strong>
              </div>
                

                <div class="col-md-4">
                  <label for="current_pw" class="col-sm-6 col-form-label">Current Password <font color="red">*</font></label>
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-key"></i></span>
                    </div>
                    <input type="password" 
                            class="form-control" 
                            id="current_pw" 
                            name = "current_pw" 
                            value = "{{ old('current_pw') }}"
                            placeholder="Current Password" 
                            maxlength="190" 
                            required>

                    @if ($errors->has('current_pw'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('current_pw') }}</strong>
                        </span>
                    @endif
                  </div>
                </div>

                <div class="col-md-4">
                  <label for="new_pw" class="col-sm-6 col-form-label">New Password <font color="red">*</font></label>
                    <div class="input-group mb-3">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-asterisk"></i></span>
                      </div>
                    <input type="password" 
                            class="form-control" 
                            id="new_pw" 
                            name = "new_pw" 
                            minlength="8" 
                            maxlength="190" 
                            value = "{{ old('new_pw') }}"
                            placeholder="New Password" 
                            required>

                    @if ($errors->has('new_pw'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('new_pw') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>

                <div class="col-md-4">
                  <label for="confirm_pw" class="col-sm-6 col-form-label">Confirm Password <font color="red">*</font></label>
                  <i class="fas fa-eye" 
                      onmouseover="p1_mouseoverPass();" 
                      onmouseout="p1_mouseoutPass();">
                  </i> &nbsp;
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-asterisk"></i></span>
                    </div>
                    <input type="password" 
                            class="form-control" 
                            id="confirm_pw" 
                            name = "confirm_pw" 
                            minlength="8" 
                            maxlength="190" 
                            value = "{{ old('confirm_pw') }}"
                            placeholder="Confirm Password" 
                            required>

                    @if ($errors->has('confirm_pw'))
                      <span class="invalid-feedback" role="alert">
                          <strong>{{ $errors->first('confirm_pw') }}</strong>
                      </span>
                    @endif
                  </div>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
              <button type="submit" class="btn btn-default float-right">Submit</button>
            </div>
          </form>

          <!-- FORM ENDS HERE -->
          
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
  function p1_mouseoverPass(obj) {
      var obj = document.getElementById('new_pw');
      var obj2 = document.getElementById('confirm_pw');
      obj.type = "text";
      obj2.type = "text";
  }
  function p1_mouseoutPass(obj) {
      var obj = document.getElementById('new_pw');
      var obj2 = document.getElementById('confirm_pw');
      obj.type = "password";
      obj2.type = "password";
  }
</script>

@endpush