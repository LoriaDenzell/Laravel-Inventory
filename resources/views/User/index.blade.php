@extends('layouts.backend.app')
<title>User Management</title>
@section('content')
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
          <h1 class="m-0 text-dark"><i class="fas fa-user"></i> User Management</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="/home">Home</a></li>
            <li class="breadcrumb-item active">User</li>
        </ol>
      </div>
    </div>
  </div>
</div>

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-address-book"></i> User List</h3>
            <button id="createModal" type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-user-plus"></i> Create New User </button>
          </div>
          
          <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
            <li class = "nav-item">
                <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
            </li>
            <li class = "nav-item">
                <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
            </li>
          </ul>

          <div class="card-body">
            <div class = "tab-content">
              @if(count($errors) > 0)
                <div class="alert alert-danger">
                  <ul>
                    @foreach($errors->all() as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  </ul>
                </div>
              @endif
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="user_tbl" data-toggle = "table1_" class="table table-bordered table-striped" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th><button type="button" name="bulk_deactivate" id="bulk_deactivate" class="btn btn-danger btn-xs"><i class = 'nav-icon fas fa-trash-alt'></i></button></th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>E-Mail Address</th>
                      <th>User Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach($activeUsers as $activeUser):
                    ?>
                    <tr>
                      <td>
                        <?php  
                          if($activeUser->id != Auth::user()->id){ 
                        ?>
                        <input type="checkbox" name="users_checkbox[]" class="users_checkbox" value="{{$activeUser->id}}" /></td>
                      <?php } ?>
                        <td><?php echo Str::limit($activeUser->first_name, 25, '...'); ?></td>
                      <td><?php echo Str::limit($activeUser->last_name, 25, '...'); ?></td>
                      <td><?php echo Str::limit($activeUser->email, 25, '...'); ?></td>
                      <td><?php echo $activeUser->hasRole('A') ? "Admin" : "Employee"; ?></td>
                      <td>
                        <?php
                          $url_edit = url('user/'.$activeUser->id.'/edit');
                          $url = url('user/'.$activeUser->id);
                          
                          $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
                          $edit = "<a class = 'btn btn-warning edit' href = '".$url_edit."'  title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
                          $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Deactivate'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
              
                          if($activeUser->id == Auth::user()->id){
                            echo $view."".$edit;
                          }else{
                            echo $view."".$edit."".$delete;
                          }
                        ?>
                      </td>
                    </tr>
                    <?php
                      endforeach;
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>E-Mail Address</th>
                      <th>User Type</th>
                      <th>Action</th>
                    </tr>
                  </tfoot> 
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="userTrashBin" data-toggle = "table2_" class="table table-bordered table-striped" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th><button type="button" name="bulk_reactivate" id="bulk_reactivate" class="btn btn-success btn-xs"><i class = 'fas fa-trash-restore-alt'></i></button></th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>E-Mail Address</th>
                      <th>User Type</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                      foreach($inactiveUsers as $inactiveUser):
                    ?>
                    <tr>
                      <td><input type="checkbox" name="users_trash_checkbox[]" class="users_trash_checkbox" value="{{$inactiveUser->id}}" /></td>
                      <td><?php echo Str::limit($inactiveUser->first_name, 25, '...'); ?></td>
                      <td><?php echo Str::limit($inactiveUser->last_name, 25, '...'); ?></td>
                      <td><?php echo Str::limit($inactiveUser->email, 25, '...'); ?></td>
                      <td><?php echo $inactiveUser->hasRole('A') ? "Admin" : "Employee"; ?></td>
                      </td>
                      <td>
                        <?php
                          $url = url('user/'.$inactiveUser->id);
                          $undoTrash = url('user/undoTrash/'.$inactiveUser->id);
                          
                          $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
                          $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Undo Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
                          
                          echo $view."".$undo;
                        ?>
                      </td>
                    </tr>
                    <?php
                      endforeach;
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th></th>
                      <th>First Name</th>
                      <th>Last Name</th>
                      <th>E-Mail Address</th>
                      <th>User Type</th>
                      <th>Action</th>
                    </tr>
                  </tfoot>
                </table>
              </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@endsection

{{-- Create Modal Start --}}  
<div class="modal fade" id="modalCreate" tabIndex="-1" aria-labelledby="create-modal-title" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ route('user.store') }}" autocomplete="off">
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
            <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i> Create New User</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
          <strong><font color="red">*</font> Indicates required fields.</strong>
            <div class="form-group row">
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
                  <input type="date" 
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
        </div>

        <!-- MODAL FOOTER -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger float-right">Cancel</button>
          <button type="submit" class="btn btn-success float-right">Submit</button>
        </div>

      </form>
    </div>
  </div>
</div>
{{-- Create Modal End --}}  
@push('js')

<script>
  var tbl1;
  var tbl2; 

  $(document).ready(function () {
    var tbl1 = $("#user_tbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      searchable: true, 
      sortable: true,
      order: [1, 'desc'],
      columnDefs: [
        { orderable: false, targets: [0, -1] }
      ]
    });

    var tbl2 = $("#userTrashBin").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      searchable: true, 
      sortable:true,
      order: [1, 'desc'],
      columnDefs: [
        { orderable: false, targets: [0, -1] }
      ]
    });
    
    $(document).on('shown.bs.tab', function (e) {
      tbl1.columns.adjust();
      tbl2.columns.adjust();
    });
  });

  function deleteData(dt){
    if(confirm("Are you sure you want to delete this data?")){
      $.ajax({
        type: 'DELETE',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function(response){
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
    return false;
  }

  function undoTrash(dt){
    if(confirm("Are you sure you want to activate this data?")){
      $.ajax({
        type: 'POST',
        url:$(dt).data("url"),
          data:{
            "_token":"{{ csrf_token() }}"
          },
          success:function(response){
            console.log(response);
            if(response.status){
              location.reload();
            }
          },
          error:function(response){
            console.log(response);
          }
      });
    }
    return false;
  }

  $(document).on('click', '#bulk_deactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Deactivate this User/s?"))
    {
      $('.users_checkbox:checked').each(function(){
        id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{url('user/deactivateUser')}}",
          method:"get",
          data:{id:id},
          success:function(data)
          {
            alert(data);
            location.reload();
          }
        });
      }
      else
      {
        alert("Please select atleast one checkbox");
      }
    }
  });

  $(document).on('click', '#bulk_reactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Reactivate this User/s?"))
    {
      $('.users_trash_checkbox:checked').each(function(){
          id.push($(this).val());
      });
      if(id.length > 0)
      {
        $.ajax({
          url:"{{ url('user/reactivateUser')}}",
          method:"get",
          data:{id:id},
          success:function(data)
          {
            alert(data);
            location.reload();
          }
        });
      }
      else
      {
        alert("Please select atleast one checkbox");
      }
    }
  });
</script>
@endpush