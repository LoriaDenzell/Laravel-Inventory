@extends('layouts.backend.app')
<title>Products Categories Report</title>
@section('content')

<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Products Categories Report</h1>
      </div>
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="/home">Home</a></li>
          <li class="breadcrumb-item active">Product Categories</li>
        </ol>
      </div>
    </div>
  </div>
</div>

  <!-- Main content -->
  <section class="content">
    <div class="card-body">
      <div class="row d-flex justify-content-center">
        <div class="col-6">
          <div class="card">
            <div class="card-header bg-primary d-flex justify-content-center">
              <h3 class="card-title "><i class="far fa-calendar-alt"></i> Select Date</h3>
            </div>
            <div class="card-body align-items-center d-flex justify-content-center">
              <div class="col-md-8">
                <label for="date" class="col-sm-12 col-form-label">Date <font color="red">*</font></label>
                <form class="form-horizontal" method = "GET" action = "{{ route('/categoriesSummary') }}">
                  <div class="input-group mb-4 {{$errors->has('date') ? 'has-error' : ''}}" >
                      <div class="input-group-prepend">
                          <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                      </div>
                  
                      <input type="text" 
                              class="form-control" 
                              id="date" 
                              name = "date_input"
                              autocomplete="off" 
                              required>

                      <span class="input-group-append">
                          <button type="submit" name = "dateSubmit" id = "dateSubmit" class="btn btn-info btn-flat">Submit</button>
                      </span>
                  </div>
                  </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="card-body">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header bg-info">
              <h3 class="card-title">
              <i class="fas fa-clipboard-list"></i> <b>Products Categories Sales Summary Report ({{ $dateQuery }})</b></h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="prod_types_tbl" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Category</th>
                    <th>Products Sold 
                      <?php 
                        if($QtyToday ?? '' != null){
                          echo "(".number_format($QtyToday ?? '', 0, '.', ',').")";
                        }else{
                          echo "()";
                        }
                      ?>
                    </th>
                    <th>Profit 
                      <?php
                        if($SalesToday ?? '' != null){
                          echo "(".number_format($SalesToday ?? '', 0, '.', ',').")";
                        }else{
                          echo "()";
                        }
                      ?>
                    </th>
                    <th>Percentage of Total Products Sold</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 

                    for($i=0; $i<count($categories); $i++){
                  ?>
                      <tr>
                        <td>{{$categories[$i]->category_name}}</td>
                        <td>{{$categories[$i]->quantity ?? 0}}</td>
                        <td>{{$categories[$i]->total ?? 0}}</td>
                        <td> 
                          <?php 
                              if($categories[$i]->quantity == 0 || $categories[$i]->total == 0){
                                $currentPercentage = 0;
                              }else{
                                $currentPercentage = round(($categories[$i]->total / $SalesToday) * 100, 2);
                              }

                              if($currentPercentage >= floatval($content->high_pct)){
                                echo "<span class='badge bg-success'>".$currentPercentage." %</span>";
                              }else if($currentPercentage >= floatval($content->ave_pct) and $currentPercentage <= floatval($content->high_pct)-.01){
                                echo "<span class='badge bg-warning'>".$currentPercentage." %</span>";
                              }else if($currentPercentage >= floatval($content->low_pct) and $currentPercentage <= floatval($content->ave_pct)-.01){
                                echo "<span class='badge bg-danger'>".$currentPercentage." %</span>";
                              }else if($currentPercentage <= floatval($content->low_pct)-.01){
                                echo "<span class='badge bg-secondary'>".$currentPercentage." %</span>";
                              }
                          ?>
                        </td>
                      </tr>
                  <?php 
                    }
                  ?>
                </tbody>
                <tfoot>
                  <th>Category</th>
                  <th>Products Sold 
                      <?php 
                        if($QtyToday ?? '' != null){
                          echo "(".number_format($QtyToday ?? '', 0, '.', ',').")";
                        }else{
                          echo "()";
                        }
                      ?>
                    </th>
                    <th>Profit 
                      <?php
                        if($SalesToday ?? '' != null){
                          echo "(".number_format($SalesToday ?? '', 0, '.', ',').")";
                        }else{
                          echo "()";
                        }
                      ?>
                    </th>
                  <th>Percentage</th>
                </tfoot>
              </table>
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
  $(function () {
    $("#prod_types_tbl").DataTable({
      "responsive": true,
      "autoWidth": false,
      order:[3, 'desc']
    });

    $('#date').datepicker({
        autoclone: true,
        dateFormat: 'yy-mm-dd',
    });
  });
</script>
@endpush