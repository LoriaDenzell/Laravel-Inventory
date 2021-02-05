@extends('layouts.backend.app')
<title>User Profile</title>
@section('content')


<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item">User</li>
          <li class="breadcrumb-item active">View User Profile</li>
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
            <h3 class="card-title"><i class="fas fa-user"></i> User Profile Information</h3>
          </div>
          
          <form class="form-horizontal" method = "POST">
            @csrf 
            <div class="card-body">
              <div class="form-group row">
                <div class="col-md-4">
                  <label for="first_name" class="col-sm-6 col-form-label">First Name</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">F</span>
                    </div>
                    <input type="text" class="form-control" id="first_name" value = "{{ $data->first_name}}" name = "first_name" placeholder="First Name" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="last_name" class="col-sm-6 col-form-label">Last Name</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">L</span>
                    </div>
                    <input type="text" class="form-control" id="last_name" value = "{{ $data->last_name}}" name = "last_name" placeholder="Last Name" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">Gender</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                    </div>
                    <?php 
                      $genderDisplay = $data->gender == 'M' ? 'Male' : 'Female';
                    ?>
                    <input type="text" class="form-control" id="gender" value = "{{ $genderDisplay }}" name = "gender" placeholder="Gender" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">Contact No.</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                    </div>
                    <input type="text" class="form-control" id="user_contact" value = "{{ $data->user_contact}}" name = "gender" placeholder="Contact No." disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="contact" class="col-sm-6 col-form-label">Birth Date</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-birthday-cake"></i></span>
                    </div>
                    <input type="date" class="form-control" id="birthdate"  value = "{{$data->birthdate}}" name = "birthdate" placeholder="Birth Date" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="status" class="col-sm-6 col-form-label">Account Status</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text">
                        <?php 
                          if($data->status === 1){
                        ?> 
                          <i class="fas fa-check-circle"></i> 
                        <?php
                          }else{
                        ?>
                          <i class="fas fa-times-circle"></i>
                        <?php
                          }
                        ?>
                      </span>
                    </div>
                    <select class = "form-control" id = "status" name = "status" disabled>
                      <option value = "1" {{$data->status === 1 ? 'selected' : ''}}>Active</option>
                      <option value = "0" {{$data->status === 0 ? 'selected' : ''}}>Inactive</option>
                    </select>
                  </div>
                </div>
                <div class="col-md-4">
                  <label for="address" class="col-sm-6 col-form-label">E-Mail Address</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-at"></i></span>
                    </div>
                    <input type="text" class="form-control" id="email" value = "{{ $data->email}}" name = "email" placeholder="E-Mail Address" disabled>
                  </div>
                </div>
                <div class="col-md-4">
                    <label for="user_created_at" class="col-sm-6 col-form-label">Registered At: </label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                    </div>
                    <input type="text" class="form-control" name="user_created_at" id="user_created_at" value="{{ $data->created_at }}" disabled>
                    </div>
                </div>
                <div class="col-md-4">
                    <label for="user_updated_at" class="col-sm-6 col-form-label">Last Update/Activity:</label>
                    <div class="input-group mb-2">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="fas fa-user-clock"></i></span>
                    </div>
                    <input type="text" class="form-control" name="user_updated_at" id="user_updated_at" value="{{ $data->updated_at }}" disabled>
                    </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history"></i> User Activity Log</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="alert alert-info alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
              <h5>
                <i class="icon fas fa-info"></i> Info!
              </h5>
                'Activity log' is a record of a report in which all the recorded events that you made while 
                using and/or logged-in your account aresequentially ordered and displayed.<br><br>
                <strong>The following columns and its description is stated below: </strong><br><br>
                <ul>
                  <li> <strong>Log type</strong> - What entity does the activity involved. </li>
                  <li> <strong>Description</strong> - Defines the recorded activity.</li>
                  <li> <strong>Old Data</strong> - The attribute/s and its old data/s before the change/s has been made.</li>
                    <ul>
                      <li>If blank or null then it means that the activity it is a 'creation'</li>
                    </ul>
                  <li> <strong>New Data</strong> - The attribute/s and its new data/s after the change/s has been made.</li>
                  <li> <strong>Date & Time</strong> - The Date and time where the activity occured and recorded.</li>

                </ul>
            </div>
            <table id="activity_table_log" class="table table-bordered table-striped" style = "width: 100%;">
              <thead>
              <tr>
                <th>Log Type</th>
                <th>Description</th>
                <th>Subject ID</th>
                <th>Old Data</th>
                <th>New Data</th>
                <th>Date & Time</th>
              </tr>
              </thead>
              <tfoot>
              <tr>
                <th>Log Type</th>
                <th>Description</th>
                <th>Subject ID</th>
                <th>Old Data</th>
                <th>New Data</th>
                <th>Date & Time</th>
              </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
@push('js')
<script>
  
  $(function () {
    var user_ID = {{$data->id}}
    
    $("#activity_table_log").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:'/userActivities/' + {{$data->id}},
      order:[],
      columns:[
        {data:'log_name', name: 'log_name'},
        {data:'description', name: 'description'},
        {data:'subject_id', name: 'subject_id'},
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
        searchable: true, sortable:true},
      ]
    });
  });
</script>
@endpush