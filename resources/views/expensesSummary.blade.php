@extends('layouts.backend.app')
@push('css')
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('asset/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
<!-- Date --> 
<link rel="stylesheet" href="{{ asset('asset/plugins/jquery-ui/jquery-ui.css')}}">
@endpush
<title>Purchase Order Report</title>
@section('content')

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Purchase Order by Date</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/home">Home</a></li>
              <li class="breadcrumb-item active">Report</li>
              <li class="breadcrumb-item active">Purchase</li>
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
                <h3 class="card-title "><i class="far fa-calendar-alt"></i> Select Date</h3>
              </div>
              <div class="card-body align-items-center d-flex justify-content-center">
                <div class="col-md-8">
                  <label for="date" class="col-sm-12 col-form-label">Date <font color="red">*</font></label>
                  <form class="form-horizontal" method = "GET" action = "{{ route('/expensesSummary') }}">
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
                    <b> Expenses / Purchases ({{$dateQuery}})</b>
                </h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <table id="exp_rec_tbl" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="all">Who made the Purchase/s / Expense</th>
                            <th class="all">Total Price of Expense/s ({{$ExpensesToday}})</th>
                            <th class="all">Expense/s List</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                          for($i=0; $i<count($FinalExpensesRecord);$i++)
                          {
                            echo "<tr>";
                            echo "<td>".$FinalExpensesRecord[$i]['user']."</td>";
                            echo "<td>".number_format($FinalExpensesRecord[$i]['total'], 0, '.', ',')."</td>";
                            echo "<td>";
                              echo str_replace(',', '<br />', $FinalExpensesRecord[$i]['items']);
                            echo "</td>";
                            echo "</tr>";
                          }
                        ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th class="all">Who made the Purchase/s / Expense</th>
                            <th class="all">Total Price of Expense/s ()</th>
                            <th class="all">Expense/s List</th>
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
    $("#exp_rec_tbl").DataTable({
      "responsive": true,
      "autoWidth": false
    });

    $('#date').datepicker({
        autoclone: true,
        dateFormat: 'yy-mm-dd',
    });
  });
</script>
@endpush