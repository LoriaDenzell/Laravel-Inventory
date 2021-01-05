@extends('layouts.backend.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<!-- Date --> 
<link rel="stylesheet" href="{{ asset('asset/plugins/jquery-ui/jquery-ui.css')}}">
@endpush
<title>Sales Order Report</title>
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Sales Order Report by Date</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
              <li class="breadcrumb-item active">Sales</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="card-body">
        <div class="row d-flex justify-content-center">
          <div class="col-6">
            <div class="card">
              <div class="card-header bg-primary d-flex justify-content-center">
                <h3 class="card-title"><i class="far fa-calendar-alt"></i> Select Date</h3>
              </div>
              <div class="card-body align-items-center d-flex justify-content-center">
                <div class="col-md-8">
                  <label for="date" class="col-sm-12 col-form-label">Date <font color="red">*</font></label>
                  <form class="form-horizontal" method = "GET" action = "{{ route('/salesSummary') }}">
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
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info">
                <h3 class="card-title">
                <i class="fas fa-clipboard-list"></i>
                    <b>Sales Orders ({{$dateQuery}})</b>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="sales_rec_tbl" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="all">Customer</th>
                            <th class="all">Total Price of Order ({{number_format($SalesToday ?? '', 0, '.', ',')}})</th>
                            <th class="all">Products Quantity ({{number_format($ProductsSold ?? '', 0, '.', ',')}})</th>
                            <th class="all">Products</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            for($i=0; $i<count($FinalSalesRecord); $i++){
                                echo "<tr>";
                                echo "<td>".$FinalSalesRecord[$i]['customer']."</td>";
                                echo "<td>".$FinalSalesRecord[$i]['total_price']."</td>";
                                echo "<td>";
                                    echo str_replace(',', '<br />', $FinalSalesRecord[$i]['quantity']);
                                echo "</td>";
                                echo "<td>";
                                    echo str_replace(',', '<br />', $FinalSalesRecord[$i]['products']);
                                echo "</td>";
                                echo "</tr>";
                            }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="all">Customer</th>
                            <th class="all">Total Price of Order ({{$SalesToday}})</th>
                            <th class="all">Products Quantity ({{$ProductsSold}})</th>
                            <th class="all">Products</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    <!-- /.card -->
    </div>
     
  </section>
@endsection
@push('js')
<script>
  $(function () {
    $("#sales_rec_tbl").DataTable({
      "responsive": true,
      "autoWidth": false,
      order:[1, 'desc']
    });

    $('#date').datepicker({
        autoclone: true,
        dateFormat: 'yy-mm-dd',
    });

  });
</script>
@endpush