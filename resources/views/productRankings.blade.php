@extends('layouts.backend.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
@endpush
<title>Products Rankings Report</title>
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Products Rankings Report by Date</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
              <li class="breadcrumb-item active">Products</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="card-body">
        <div class="row">
          <div class="col-12">
            <div class="card">
              <div class="card-header bg-info">
                <h3 class="card-title">Products Rankings Report</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="prod_types_tbl" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Category</th>
                      <th>Products Sold</th>
                      <th>Profit</th>
                      <th>Percentage of Total Products Sold ({{$SalesToday}})</th>
                    </tr>
                  </thead>
                  <tbody>
                    @for($i=0; $i<@count($soldProducts); $i++)
                    <!--<span class="badge bg-warning">70%</span>-->
                      <tr>
                        <td>{{$soldProducts[$i]['category_name']}}</td>
                        <td>{{$soldProducts[$i]['sold_count']}}</td>
                        <td></td>
                        <td>
                          <?php 
                              $currentPercentage = round($soldProducts[$i]['sold_count'] / $SalesToday * 100, 2);

                              if($currentPercentage >= 50.00){
                                echo "<span class='badge bg-success'>".$currentPercentage." %</span>";
                              }else if($currentPercentage <= 49.99 && $currentPercentage >= 20.00){
                                echo "<span class='badge bg-warning'>".$currentPercentage." %</span>";
                              }else if($currentPercentage <= 19.99 && $currentPercentage >= 1.00){
                                echo "<span class='badge bg-danger'>".$currentPercentage." %</span>";
                              }else if($currentPercentage <= 0.99 && $currentPercentage >= 0.00){
                                echo "<span class='badge bg-secondary'>".$currentPercentage." %</span>";
                              }
                          ?>
                        </td>
                      </tr>
                    @endfor
                  </tbody>
                  <tfoot>
                    <th>Category</th>
                    <th>Products Sold</th>
                    <th>Profit</th>
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
  });
</script>
@endpush