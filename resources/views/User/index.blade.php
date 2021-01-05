@extends('layouts.backend.app')

@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush
<title>User Management</title>
@section('content')

 <!-- Content Header (Page header) -->
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
              <a class = "btn btn-info btn-sm float-right" href = "{{ route('user.create')}}" title = "Create"><i class="fas fa-user-plus"></i> Create User</a>
          </div>
          <!-- /.card-header -->
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
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="userTable" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all">First Name</th>
                      <th class="all">Last Name</th>
                      <th class="all">E-Mail Address</th>
                      <th class="all">User Type</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                  <tbody> 
                      <?php 

                          for($i=0; $i<count($activeUsers); $i++)
                          {
                            $userObject = \App\User::find($activeUsers[$i]->id);

                            $url_edit = url('user/'.$activeUsers[$i]->id.'/edit');
                            $url = url('user/'.$activeUsers[$i]->id);
                            
                            $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
                            $edit = "<a class = 'btn btn-warning' href = '".$url_edit."' title = 'Edit'><i class = 'nav icon fas fa-edit'></i></a>";
                            $delete = "<button data-url = '".$url."' onclick = 'deleteData(this)' class = 'btn btn-action btn-danger' title = 'Delete'><i class = 'nav-icon fas fa-trash-alt'></i></button>";
                            
                            echo "<tr>";
                            echo "<td>". $activeUsers[$i]->first_name. "</td>";
                            echo "<td>". $activeUsers[$i]->last_name. "</td>";
                            echo "<td>". $activeUsers[$i]->email. "</td>";
                            echo "<td>";

                            if($userObject->hasRole('A')){
                              echo "Administrator";
                            }else{
                              echo "Employee";
                            }

                            echo "</td>";
                            echo "<td>". $view."".$edit."".$delete ."</td>";
                            echo "</tr>";
                          }
                      ?>
                  </tbody>
                  <tfoot>
                    <tr>
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
                <table id="userTrashBin" class="table table-bordered table-striped" data-toggle = "table2_"style = "width: 100%;">
                  <thead>
                    <tr>
                      <th class="all">First Name</th>
                      <th class="all">Last Name</th>
                      <th class="all">E-Mail Address</th>
                      <th class="all">User Type</th>
                      <th class="all">Action</th>
                    </tr>
                  </thead>
                      <tbody>
                      <?php 
                        for($j=0; $j<count($inactiveUsers); $j++)
                        {
                          $userObject = \App\User::find($inactiveUsers[$j]->id);

                          $url_edit = url('user/'.$inactiveUsers[$j]->id.'/edit');
                          $url = url('user/'.$inactiveUsers[$j]->id);
                          $undoTrash = url('user/undoTrash/'.$inactiveUsers[$j]->id);

                          $view = "<a class = 'btn btn-primary' href = '".$url."' title = 'View'><i class = 'nav icon fas fa-eye'></i></a>";
                          $undo = "<button data-url = '".$undoTrash."' onclick = 'undoTrash(this)' class = 'btn btn-action btn-success' title = 'Undo Delete'>Activate User</button>";

                          echo "<tr>";
                          echo "<td>". $inactiveUsers[$j]->first_name. "</td>";
                          echo "<td>". $inactiveUsers[$j]->last_name. "</td>";
                          echo "<td>". $inactiveUsers[$j]->email. "</td>";
                          echo "<td>";

                          if($userObject->hasRole('A')){
                            echo "Administrator";
                          }else{
                            echo "Employee";
                          }

                          echo "</td>";
                          echo "<td>". $view."".$undo ."</td>";
                          echo "</tr>";
                        }

                      ?>
                      </tbody>
                  <tfoot>
                    <tr>
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
@push('js')
<script>
  $(document).ready(function() {
    $("#userTable").DataTable({
      "responsive": true,
      "autoWidth": false
    });

    $("#userTrashBin").DataTable({
      "responsive": true,
      "autoWidth": false
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
</script>
@endpush