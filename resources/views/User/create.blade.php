@extends('layouts.backend.app')
<title>Create User</title>
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create User</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">User</li>
                <li class="breadcrumb-item active">Create User</li>
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
            <div class="col-md-12">
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-user-plus"></i> Create a new User Account</h3>
                    </div>
                        <div class="card-body">
                            <form class="form-horizontal" method = "POST" action = "{{ route('user.store') }}">
                            @csrf
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <strong><font color="red">*</font> Indicates required fields.</strong>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_fname" class="col-sm-6 col-form-label">First Name <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" 
                                                    class="form-control{{ $errors->has('first_name') ? ' is-invalid' : '' }}" 
                                                    id="first_name" 
                                                    name = "first_name"
                                                    value="{{ old('first_name') }}"
                                                    minlength="2"
                                                    maxlength="150"
                                                    required/>
                                            
                                        </div>
                                        @error('first_name')
                                            <div class="alert-danger">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_lname" class="col-sm-6 col-form-label">Last Name <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fa fa-user" aria-hidden="true"></i></span>
                                            </div>
                                            <input type="text" 
                                                    class="form-control{{ $errors->has('last_name') ? ' is-invalid' : '' }}" 
                                                    id="last_name" 
                                                    name = "last_name"
                                                    value="{{ old('last_name') }}"
                                                    minlength="2"
                                                    maxlength="150"
                                                    required/>
                                        </div>
                                            @error('last_name')
                                                <div class="alert-danger">{{$errors->first('last_name') }} </div>
                                            @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_gender" class="col-sm-6 col-form-label">Gender <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                            </div>
                                            <select class = "form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" id = "gender" name = "gender" required>
                                                <option value = "M">Male</option>
                                                <option value = "F">Female</option>
                                            </select>
                                        </div>
                                        @error('gender')
                                            <div class="alert-danger">{{$errors->first('gender') }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_birthdate" class="col-sm-6 col-form-label">Birth Date <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                            </div>
                                            <input type="text" 
                                                    class="form-control{{ $errors->has('birthdate') ? ' is-invalid' : '' }}" 
                                                    id="birthdate" 
                                                    name = "birthdate"
                                                    value="{{ old('birthdate') }}"
                                                    required/>
                                            <div class="alert-danger">{{$errors->first('birthdate') }} </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_contact" class="col-sm-6 col-form-label">Contact Number <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                            </div>
                                            <input type="text" 
                                                    class="form-control{{ $errors->has('user_contact') ? ' is-invalid' : '' }}" 
                                                    id="user_contact" 
                                                    name = "user_contact"
                                                    value="{{ old('user_contact') }}"
                                                    onkeyup="this.value=this.value.replace(/[^\d]/,'')"
                                                    minlength="10"
                                                    maxlength="20"
                                                    required/>
                                        </div>
                                        @error('user_contact')
                                            <div class="alert-danger">{{$errors->first('user_contact') }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_email" class="col-sm-6 col-form-label">E-Mail Address<font color="red">*</font></label>
                                        <div class="input-group mb-2 {{$errors->has('u_email') ? 'has-error' : ''}}" >
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-at"></i></span>
                                            </div>
                                            <input type="email" 
                                                    class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" 
                                                    id="email" 
                                                    name = "email"
                                                    value="{{ old('email') }}"
                                                    minlength="10"
                                                    maxlength="150"
                                                    required/>
                                        </div>
                                        @error('email')
                                            <div class="alert-danger">{{$errors->first('email') }} </div>
                                        @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <label for="u_utype" class="col-sm-6 col-form-label">User Type <font color="red">*</font></label>
                                        <div class="input-group mb-2">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-user-cog"></i></span>
                                            </div>
                                            <select class = "form-control{{ $errors->has('u_type_input') ? ' is-invalid' : '' }}" id = "u_type_input" name = "u_type_input" required>
                                                <option value = "E">Employee</option>
                                                <option value = "A">Administrator</option>
                                            </select>
                                        </div>
                                        @error('u_type_input')
                                            <div class="alert-danger">{{$errors->first('u_type_input') }} </div>
                                        @enderror
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-default float-right bg-primary" name = "submit_create" id = "submit_create">Submit</button>
                            </form>
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
    $('#birthdate').datepicker({
        autoclone:true,
        dateFormat:'yy-mm-dd',
    });
</script>
@endpush