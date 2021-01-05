@extends('layouts.backend.app')
<title>Dashboard (Home)</title>
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
        <h1 class="m-0 text-dark">Dashboard</h1>
      </div><!-- /.col -->
      <div class="col-sm-6">
        <ol class="breadcrumb float-sm-right">
          <li class="breadcrumb-item"><a href="#">Home</a></li>
          <li class="breadcrumb-item active">Dashboard</li>
        </ol>
      </div><!-- /.col -->
    </div><!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
  @role('A')
  <div class="row d-flex justify-content-center">
    <div class="col-6">
      <div class="card">
        <div class="card-header bg-primary d-flex justify-content-center">
          <h3 class="card-title "><i class="far fa-calendar-alt"></i> Select Date for Daily Operations Summary Report</h3>
        </div>
        <div class="card-body align-items-center d-flex justify-content-center">
          <div class="col-md-8">
            <label for="date" class="col-sm-12 col-form-label">Date <font color="red">*</font></label>
            <form class="form-horizontal" method = "GET" action = "{{ route('/home') }}">
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
  @endrole

  <div class="container-fluid">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title"><i class="far fa-file-alt"></i> Daily Operations Summary Report <b>({{ $dateQuery }}) </b>vs <b> ({{$yesterday}}) </b></h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
          </button>
          <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
        </div>
      </div>
      <br>
      <div class="card-body">
        <div class = "row">

          <!-- 1. SALES -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-info">
              <span class="info-box-icon">
              <?php
                if($SalesToday == 0 && $SalesYesterday == 0){
                  echo "<i class='fas fa-question'></i>";
                }else if($SalesToday > $SalesYesterday){
                  echo "<i class='fas fa-caret-up'></i>";
                }else if($SalesToday < $SalesYesterday){
                  echo "<i class='fas fa-caret-down'></i>";
                }
              ?>
              </span>

              <div class="info-box-content">
                <span class="info-box-text"><b>Sales:</b></span>
                <span class="info-box-number">{{ number_format($SalesToday, 0, '.', ',') ?? 'Not yet available.'}}</span>
                <div class="border-top my-3"></div>
                <span class="progress-description">
                {{ $SalesPercentageChange ?? '(N/A)' }} %
                  <?php 
                    if($SalesToday <= 0 || $SalesYesterday <= 0 ){
                      echo "Data not Available";
                    }else if($SalesToday > $SalesYesterday && ($SalesToday != 0 && $SalesYesterday != 0))
                    {
                      echo "Increase from Yesterday <br><b>(". number_format($SalesYesterday, 0, '.', ','). ")</b>";
                    }else if($SalesToday < $SalesYesterday && ($SalesToday != 0 && $SalesYesterday != 0)){
                      echo "Decrease from Yesterday <br><b>(". number_format($SalesYesterday, 0, '.', ','). ")</b>";
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>

          <!-- 2. NET PROFIT -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-success">
              <span class="info-box-icon">
              <?php
                if($RevenueToday == 0 && $RevenueYesterday == 0){
                  echo "<i class='fas fa-question'></i>";
                }else if($RevenueToday > $RevenueYesterday){
                  echo "<i class='fas fa-caret-up'></i>";
                }else if($RevenueToday < $RevenueYesterday){
                  echo "<i class='fas fa-caret-down'></i>";
                }else{
                  echo "<i class='fas fa-question'></i>";
                }
              ?>
              </span>

              <div class="info-box-content">
                <span class="info-box-text"><b>Revenue:</b></span>
                <span class="info-box-number">{{ number_format($RevenueToday, 0, '.', ',') ?? 'Not yet available.'}}</span>
                <div class="border-top my-3"></div>
                <span class="progress-description">
                {{ $ProfitPercentageChange ?? '(N/A)' }} %
                  <?php 
                    if($RevenueToday <= 0 || $RevenueYesterday <= 0){
                      echo "Data not Available";
                    }else if($RevenueToday > $RevenueYesterday && ($RevenueToday != 0 && $RevenueYesterday != 0)){
                      echo "Increase from Yesterday <br><b>(".number_format($RevenueYesterday, 0, '.', ','). ")</b>";
                    }else if($RevenueToday < $RevenueYesterday && ($RevenueToday != 0 && $RevenueYesterday != 0)){
                      echo "Decrease from Yesterday <br><b>(".number_format($RevenueYesterday, 0, '.', ','). ")</b>";
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>

          <!-- 3. EXPENSES -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-danger">
              <span class="info-box-icon">
              <?php
                if($ExpensesToday == 0 && $ExpensesYesterday == 0){
                  echo "<i class='fas fa-question'></i>";
                }else if($ExpensesToday > $ExpensesYesterday){
                  echo "<i class='fas fa-caret-up'></i>";
                }else if($ExpensesToday < $ExpensesYesterday){
                  echo "<i class='fas fa-caret-down'></i>";
                }else{
                  echo "<i class='fas fa-question'></i>";
                }
              ?>
              </span>

              <div class="info-box-content">
                <span class="info-box-text"><b>Expenses:</b></span>
                <span class="info-box-number">{{ number_format($ExpensesToday, 0, '.', ',') ?? 'Not yet available.'}}</span>
                <div class="border-top my-3"></div>
                <span class="progress-description">
                {{ $ExpensesPercentageChange ?? '(N/A)' }} %
                  <?php 
                    if($ExpensesToday <= 0 || $ExpensesYesterday <= 0){
                      echo "Data not Available";
                    }else if($ExpensesToday > $ExpensesYesterday && ($ExpensesToday != 0 && $ExpensesYesterday != 0)){
                      echo "Increase from Yesterday <br><b>(". number_format($ExpensesYesterday, 0, '.', ','). ")</b>";
                    }else if($ExpensesToday < $ExpensesYesterday && ($ExpensesToday != 0 && $ExpensesYesterday != 0)){
                      echo "Decrease from Yesterday <br><b>(". number_format($ExpensesYesterday, 0, '.', ','). ")</b>";
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>
          
          <!-- 4. BEST SELLER -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-gradient-warning">
              <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><b>Best Seller:</b></span>
                <span class="info-box-text"><b>{{ $TodayBestSeller ?? '(N/A)'}}</b></span>

                <div class="border-top my-3"></div>
                <span class="progress-description">
                  <?php 
                    if($YesterdayBestSeller == null || $TodayBestSeller == null){
                      echo "Data not available.";
                    }else{
                      echo "Yesterday's Best Seller: <br><b>".$YesterdayBestSeller."</b>";
                    }
                  ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      
        <div class="row">
          <div class="col-md-3">
            <div class="card bg-gradient-primary">
              <div class="card-header">
                <h3 class="card-title">Highest sales from a single Order</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php 
                  if($HighestSalesToday != null && isset($SalesHInformation->shop_name)){
                    echo "<b>".number_format($HighestSalesToday, 0, '.', ',')." - ". $SalesHInformation->shop_name."</b>";
                  }else{
                    echo "<i class='icon fas fa-ban'></i> ";
                    echo "Data not Available";
                  }
                ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card bg-gradient-success">
              <div class="card-header">
                <h3 class="card-title">Highest revenue from a customer</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php 
                  if($HighestRevenueToday != null && isset($HighestRevenue->shop_name)){
                    echo "<b>".number_format($HighestRevenueToday, 0, '.', ',')." - ". $HighestRevenue->shop_name."</b>";
                  }else{
                    echo "<i class='icon fas fa-ban'></i> ";
                    echo "Data not Available";
                  }
                ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card bg-gradient-danger">
              <div class="card-header">
                <h3 class="card-title">Highest single expense from expenses/purchases</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php
                  if($ExpensivePurchaseDToday != null)
                  {
                    echo "<b>".number_format($ExpensivePurchaseDToday->price, 0, '.', ',')." - ". $ExpensivePurchaseDToday->product_name."</b>";
                  }else{
                    echo "<i class='icon fas fa-ban'></i> ";
                    echo "Data not Available";
                  }
                ?>
              </div>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card bg-gradient-warning">
              <div class="card-header">
                <h3 class="card-title">Net Profit <br>(Revenue - Expenses)</h3>

                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body">
                <?php
                  if($NetProfit != null)
                  {
                    echo "<b>".number_format($NetProfit, 0, '.', ',')."</b>";
                  }else{
                    echo "<i class='icon fas fa-ban'></i> ";
                    echo "Data not Available";
                  }
                ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="border-top my-3"></div>

    <div class="row">
      <div class="col-md-12">
        <div class="card card-success">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line"></i> Top 10 Best Sellers </h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body ">
            <div class="barChart1">
              <canvas id="barChart1"></canvas>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <?php

              for($p=0; $p<3; $p++){
                echo"
                <div class='col-sm-4 col-6'>
                  <div class='description-block border-right'>
                    <h5 class='description-header'>". $ValuesArray[$p]. "</h5>
                    <span class='description-text'>". $NamesArray[$p]. "</span>".
                  "</div>".
                  "</div>";
              }
              ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>

    <div class="border-top my-3"></div>

    <div class = "row">
        <!-- LEFT -->
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header">
                    <h3 class="card-title">Products Sold on Product Categories</h3>

                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                    </button>
                        <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="donutChart" style="min-height: 250px; height: 300px; max-height: 350px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

        <!-- RIGHT -->
        @role('A')
        <div class="col-md-6">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="far fa-chart-bar"></i>
                        Volume of Orders by Day
                    </h3>
                    <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div id="order_vol_day" style="height: 300px;"></div>
            </div>
        </div>
        @endrole
    </div>
    </div>

    <div class="border-top my-3"></div>

    <div class = "row">
      @role('A')
      <div class="col-md-6">
        <div class="card card-info">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-line"></i> Revenue Vs. Expenses (All Time)</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body ">
            <div class="barChart_sales_purchase">
              <canvas id="barChart_sales_purchase"></canvas>
            </div>
          </div>
        </div>
      </div>
      @endrole

      <!-- RIGHT -->
      @role('A')
      <div class="col-md-6">
        <div class="card card-danger">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-chart-pie"></i> Products By Status</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
            </div>
          </div>
          <div class="card-body">
            <canvas id="pieChart1"></canvas>
          </div>
        </div>
      </div>
      @endrole
    </div>
    
    <div class="border-top my-3"></div>

    <div class="row">
      <div class="col-md-6">
        <div class="card">
          <div class="card-header bg-info">
            <h3 class="card-title"><i class="fa fa-users" aria-hidden="true"></i> Top 5 Frequent Customers</h3>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <table class="table table-bordered">
              <thead>                  
                <tr>
                  <th style="width: 10px">#</th>
                  <th>Customer Name</th>
                  <th># of Purchases</th>
                  <th style="width: 50px">Total revenue earned</th>
                </tr>
              </thead>
              <tbody>
                <?php
                  if(count($MostLoyalCustomer) >= 5){
                    for($x=0; $x<count($MostLoyalCustomer); $x++){
                      echo "<tr>";
                      echo "<td>".$x."</td>";
                      echo "<td>".strtoupper($MostLoyalCustomer[$x]->shop_name)."</td>"; 
                      echo "<td>".number_format($MostLoyalCustomer[$x]->count, 0, '.', ',')."</td>";
                      echo "<td>".number_format($MostLoyalCustomer[$x]->total, 0, '.', ',')."</td>";
                      echo "</tr>";
                    }
                  }else{
                      echo "<tr>";
                      echo "<td>Data Not Available</td>";
                      echo "<td>Data Not Available</td>";
                      echo "<td>Data Not Available</td>";
                      echo "<td>Data Not Available</td>";
                      echo "</tr>";
                  }
                ?>
              </tbody>
            </table>
          </div>
          <!--
          <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
              <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
              <li class="page-item"><a class="page-link" href="#">1</a></li>
              <li class="page-item"><a class="page-link" href="#">2</a></li>
              <li class="page-item"><a class="page-link" href="#">3</a></li>
              <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
            </ul>
          </div>
          -->
        </div>
      </div>
      <div class = "col-md-6">
        <div class="card">
          <div class="card-header bg-warning">
            <h3 class="card-title"><i class="fab fa-dropbox"></i> Recently Added Products</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body p-0">
            <ul class="products-list product-list-in-card pl-2 pr-2">
              <?php 
                if(count($RecentlyAddedProducts) >= 5){
                  for($y=0; $y<count($RecentlyAddedProducts); $y++){
                    ?>
                    <li class='item'>
                      <div class='product-img'>
                        <?php 
                          if($RecentlyAddedProducts[$y]->product_image == null){
                        ?>
                          <img src="{{asset('/storage/images/default-150x150.png')}}" alt='Product Image' class='img-size-50'>
                        <?php 
                          }else{
                        ?>
                          <img src="{{asset('/storage/images/'.$RecentlyAddedProducts[$y]->product_image)}}" alt='Product Image' class='img-size-50'>
                        <?php 
                          }
                        ?>
                      </div>
                      <div class='product-info'>
                        <a href='javascript:void(0)' class='product-title-".$y."'> {{ $RecentlyAddedProducts[$y]->product_name }}
                        <span class='badge badge-success float-right'>{{ number_format($RecentlyAddedProducts[$y]->product_selling_price, 0, '.', ',') }}</span></a>
                        <?php 
                          if($RecentlyAddedProducts[$y]->product_information != null){
                            $productDescription = str_ireplace("\r\n", ',', $RecentlyAddedProducts[$y]->product_information);
                            $productDescription = Str::limit($productDescription, 25, '...');
                        ?>
                            <span class='product-description'>
                              <?php echo $productDescription; ?>
                            </span>
                        </a>
                        <?php
                          }else{
                        ?>
                          <span class='product-description'>Data not Available</span></a>
                        <?php
                        }
                        ?>
                        </span>
                      </div>
                    </li>
                  <?php 
                  }
                }else{
                  for($y=0; $y<count($RecentlyAddedProducts); $y++){
                    echo "<li class='item'>";
                    echo "<div class='product-img'>";
                    echo "<img src='dist/img/default-150x150.png' alt='Product Image' class='img-size-50'>";
                    echo "</div>";
                    echo "<div class='product-info'>";
                    echo "<a href='javascript:void(0)' class='product-title'> Data Not Available";
                    echo "<span class='badge badge-info float-right'>Data Not Available</span></a>";
                    echo "<span class='product-description'>";
                    echo "Data Not Available";
                    echo "</span>";
                    echo "</div>";
                    echo "</li>";
                  }
                }
              ?>
            </ul>
          </div>
          <!-- /.card-body -->
          <div class="card-footer text-center">
            <a href="{{ route('product.index')}}" class="uppercase">View All Products</a>
          </div>
          <!-- /.card-footer -->
        </div>
      </div>
    </div>

    <div class="border-top my-3"></div>

    <div class="row">
      @role('A')
      <div class="col-md-12">
        <div class="card">
          <div class="card-header bg-success">
            <h5 class="card-title"><i class="fas fa-business-time"></i><b> Recap Report for this Month ({{$CurrentMonth}})</b></h5>
            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <div class="card-header">
            <ul class="nav nav-pills card-header-pills">
              <li class="nav-item">
                <a class="nav-link active" href="#chartPanel" data-toggle = "tab" role = "tab">Chart</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#recordPanel" data-toggle = "tab" role = "tab">Record</a>
              </li>
              <li class="nav-item">
                <a class="nav-link disabled" href="#">Disabled</a>
              </li>
            </ul>
          </div>
          
          <div class="card-body">
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "chartPanel" role = "tabPanel">
                <div class="row">
                  <div class="col-md-8">
                    <p class="text-center">
                      <strong>Sales: {{$MonthStart}} to {{$MonthEnd}}</strong>
                    </p>

                    <div class="chart">
                      <!-- Sales Chart Canvas -->
                      <canvas id="salesChart" height="180" style="height: 180px;"></canvas>
                    </div>
                  </div>
                  <div class="col-md-4">
                    <p class="text-center">
                      <strong>Monthly Summary</strong>
                    </p>

                    <div class="progress-group">
                      Date with highest # of Sales:
                      <span class="float-right"><?php echo "<b>".$Date_HighestMonthlySoldProducts." (".number_format($HighestMonthlySoldProducts, 0, '.', ',').")"; echo "</b>";?></span>
                    </div>

                    <div class="progress-group">
                      Date with lowest # of Sales:
                      <span class="float-right"><?php echo "<b>".$Date_LowestMonthlySoldProducts." (".number_format($LowestMonthlySoldProducts, 0, '.', ',').")"; echo "</b>";?></span>
                    </div>

                    <div class="progress-group">
                      Highest Expenses:
                      <span class="float-right"><?php echo "<b>".$HighestMonthlyExpense['date']." (".number_format($HighestMonthlyExpense['total'], 0, '.', ',').")"; echo "</b>";?></span>
                    </div>

                    <div class="progress-group">
                      Lowest Expenses:
                      <span class="float-right"><?php echo "<b>".$LowestMonthlyExpense['date']." (".number_format($LowestMonthlyExpense['total'], 0, '.', ',').")"; echo "</b>";?></span>
                    </div>
                    <div class="progress-group">
                      # of Net Loss Days:
                      <span class="float-right"><?php echo "<b>".$NumberOfNetLossDays. " </b>";?></span>
                    </div>

                    <div class="progress-group">
                    # of Net Profit Days:
                      <span class="float-right"><?php echo "<b>".$NumberOfNetProfitDays. " </b>";?></span>
                    </div>
                  </div>
                </div>
              </div>
              <div class = "tab-pane fade" id = "recordPanel" role = "tabPanel">
                <table id="monthly_record" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Date</th>
                      <th>Revenue</th>
                      <th>Profit</th>
                      <th>No. of Orders</th>
                      <th>Expenses</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php 
                    
                      if(count($MonthlyRevenue) != count($MonthlyExpenses))
                      {
                        echo "<tr>";
                        echo "<th scope='row'>Unsufficient Data</td>";
                        echo "<td>Unsufficient Data</td>";
                        echo "<td>Unsufficient Data</td>";
                        echo "<td>Unsufficient Data</td>";
                        echo "<td>Unsufficient Data</td>";
                        echo "</tr>";
                      }else{
                        for($i=0; $i<count($MonthlyRevenue); $i++)
                        {
                          echo "<tr>";
                          echo "<th scope='row'>". date('m.d.Y', strtotime($MonthlyRevenue[$i]->date)) ." - ". date("l", strtotime($MonthlyRevenue[$i]->date)). "</td>";
                          echo "<td>". number_format($MonthlyRevenue[$i]->total, 0, '.', ',') ."</td>";

                          echo "<td>"; 
                          $profit = ($MonthlyRevenue[$i]->total - $MonthlyExpenses[$i]->total ?? 'Unsufficient Data');
                          $profit_print = $profit > 0 ? "<span class='badge bg-success'>".number_format($profit, 0, '.', ',')."</span>" :  "<span class='badge bg-danger'>".number_format($profit, 0, '.', ',')."</span>";
                          echo $profit_print."</td>";

                          echo "<td>". number_format($MonthlySoldProducts[$i]->total, 0, '.', ',')."</td>";
                          echo "<td>". number_format($MonthlyExpenses[$i]->total, 0, '.', ',') ."</td>";
                          echo "</tr>";
                        }
                    }
                      
                    ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Date</th>
                      <th>Revenue</th>
                      <th>Profit</th>
                      <th>No. of Orders</th>
                      <th>Expenses</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
          
          <div class="card-footer">
            <div class="row">

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 17%</span>
                  <h5 class="description-header">{{ number_format($CurrentMonthRevenue, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">TOTAL REVENUE</span>
                </div>
              </div>

              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                  <h5 class="description-header">{{ number_format($CurrentMonthExpense, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">TOTAL EXPENSE</span>
                </div>
              </div>
              
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                  <h5 class="description-header">{{ number_format($CurrentMonthRevenue - $CurrentMonthExpense, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">TOTAL PROFIT</span>
                </div>
              </div>
              
              <div class="col-sm-3 col-6">
                <div class="description-block">
                  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                  <h5 class="description-header">{{ number_format($TotalMonthlyOrders, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">TOTAL CUSTOMERS SERVED</span>
                </div>
              </div>
            </div>

            <div class="row">
            <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                  <h5 class="description-header">{{ number_format($AverageMonthlyRevenue, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">AVERAGE DAILY REVENUE</span>
                </div>
              </div>
              
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-warning"><i class="fas fa-caret-left"></i> 0%</span>
                  <h5 class="description-header">{{ number_format($AverageMonthlyExpense, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">AVERAGE DAILY EXPENSE</span>
                </div>
              </div>
              
              <div class="col-sm-3 col-6">
                <div class="description-block border-right">
                  <span class="description-percentage text-success"><i class="fas fa-caret-up"></i> 20%</span>
                  <h5 class="description-header">{{ number_format($AverageDailyProfit, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">AVERAGE DAILY PROFIT</span>
                </div>
              </div>
              
              <div class="col-sm-3 col-6">
                <div class="description-block">
                  <span class="description-percentage text-danger"><i class="fas fa-caret-down"></i> 18%</span>
                  <h5 class="description-header">{{ number_format($AverageMonthlyOrder, 0, '.', ',') ?? 'Not yet available.'}}</h5>
                  <span class="description-text">AVERAGE DAILY CUSTOMERS SERVED</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      @endrole
    </div>

    @role('A')
      <div class = "row">
        <div class="col-md-12">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title"><i class="fas fa-history"></i> Recent Activities</h3>
              <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove"><i class="fas fa-times"></i></button>
              </div>
            </div>
            <div class="card-body">
              <table id="activity_table_log" class="table table-bordered table-striped" style = "width: 100%;">
                <thead>
                  <tr>
                    <th>Made By</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>Date & Time</th>
                  </tr>
                </thead>
                
                <tfoot>
                  <tr>
                    <th>Made By</th>
                    <th>Description</th>
                    <th>Subject</th>
                    <th>Date & Time</th>
                  </tr>
                </tfoot>
              </table>
            </div>
          </div>
        </div>
      </div>
    @endrole
  </div>
</section>
@endsection

@push('js')
<script>
  $(function () {

    $('#date').datepicker({
        autoclone: true,
        dateFormat: 'yy-mm-dd',
    });

    $("#activity_table_log").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      order: [],
      ajax:"{{route('RecentActivities')}}",
      columns:[
        {data: function(row, arg1, arg2, meta)
        {
          var list = "";
            $.each(row.user,
            function(key,value){

              if(key == "first_name" || key == "last_name"){
                list += value + " ";
              }
            });
            return list;
        }, name: 'made_by'},
        {data:'description', name: 'description'},
        {data:'subject_id', name: 'subject_id'},
        {data:'created_at', name: 'created_at',
        searchable: false, sortable:true},
      ]
    });

    /* TOP 10 BEST SELLERS - BAR CHART */
    var TenProductsBestSellers = <?php echo json_encode($TenBestSellers); ?>;
    var labels1 = new Array();
    var data1 = new Array();

    for (var i = 0; i < TenProductsBestSellers.length; i++) {
        labels1[i] = TenProductsBestSellers[i].product_name;
        data1[i] = TenProductsBestSellers[i].count;

        if(i == TenProductsBestSellers.length-1){
          labels1[i] = TenProductsBestSellers[i].product_name;
          data1[i] = TenProductsBestSellers[i].count;
        }
    }

    //Initialization of Bar Chart 
    var myChart = document.getElementById('barChart1').getContext('2d');
    var bestSellerBGColors = [];

    for(var x=0; x<TenProductsBestSellers.length; x++){
      var r = Math.floor(Math.random() * 255);
      var g = Math.floor(Math.random() * 255);
      var b = Math.floor(Math.random() * 255);
      bestSellerBGColors[x] = "rgb(" + r + "," + g + "," + b + ")";
    }

    var stocksChart = new Chart(myChart, {
      type:'bar',
      data: {
        labels:
          labels1,
        datasets:[{
            label: 'Quantities Sold',
            data: data1,
            backgroundColor: bestSellerBGColors,
        }],
      },
      options: {},
    });

    /* Products sold based on Product Category - DONUT CHART*/
    var categoryLabels = <?php echo json_encode($NamesArray) ?>;
    var categoryValues = <?php echo json_encode($ValuesArray) ?>;
    var bgColors = [];
    
    for(var x=0; x<categoryValues.length; x++){
      var r = Math.floor(Math.random() * 255);
      var g = Math.floor(Math.random() * 255);
      var b = Math.floor(Math.random() * 255);
      bgColors[x] = "rgb(" + r + "," + g + "," + b + ")";
    }
    var donutChartCanvas = $('#donutChart').get(0).getContext('2d')
    var donutData        = {
      labels: categoryLabels,
      datasets: [
        {
          data: categoryValues,
          backgroundColor : bgColors,
        }
      ]
    }

    var donutOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }

    var donutChart = new Chart(donutChartCanvas, {
      type: 'doughnut',
      data: donutData,
      options: donutOptions      
    })

    /* Products by Status - PIE CHART*/
    var myChart = document.getElementById('pieChart1').getContext('2d');
    var stocksChart = new Chart(myChart, {
      type:'pie',
      data: {
        labels:['Inactive', 'Active', 'Deleted'],
        datasets:[{
            labels: 'Quantities Sold',
            data:[ {{$Inactive_Products ?? ''}}, {{$Active_Products ?? ''}}, {{$Deleted_Products ?? ''}} ],
            backgroundColor: [ 'yellow', 'green', 'red'],
        }],
      },
      options: {},
    });

  });

  $(document).ready( function () {
    $('#monthly_record').DataTable();
  });

  var myChart = document.getElementById('barChart_sales_purchase').getContext('2d');
  var stocksChart2 = new Chart(myChart, {
    type:'bar',
    data : 
    {
      labels:['Revenue & Expense Figure'],
      datasets: [
      {
        label              : 'Revenue',
        backgroundColor     : 'green',
        data                : [{{ $Sales_Max ?? ''}}],
      },
      {
        label              : 'Expenses',
        backgroundColor     : 'red',
        data                : [{{ $Purchase_Max ?? ''}}],
      }]
    },
    options: {
      legend: {
        display: true,
        position: 'bottom',
        labels: {
          fontColor: "#000080",
        }
      }
    }
  });

  //--------------
  //- AREA CHART -
  //--------------

  // Get context with jQuery - using jQuery's .get() method.
  
  var areaChartCanvas = $('#salesChart').get(0).getContext('2d')
  var labelsArr = <?php echo json_encode($MonthlySalesDays) ?> ;

  //new array
  //loop 
  //if skip is detected
  //splice the array values until before the skip was detected
  //then insert the current number to the new array


  var valuesArr = <?php echo json_encode($MonthlySalesValues) ?>;

  var areaChartData = {
    labels  : labelsArr,
    datasets: [
      {
        label               : 'Products',
        backgroundColor     : 'rgba(60,141,188,0.9)',
        borderColor         : 'rgba(60,141,188,0.8)',
        pointRadius          : false,
        pointColor          : '#3b8bba',
        pointStrokeColor    : 'rgba(60,141,188,1)',
        pointHighlightFill  : '#fff',
        pointHighlightStroke: 'rgba(60,141,188,1)',
        data                : valuesArr
      }
    ]
  }

  var areaChartOptions = {
    maintainAspectRatio : false,
    responsive : true,
    legend: {
      display: false
    },
    scales: {
      xAxes: [{
        gridLines : {
          display : false,
        }
      }],
      yAxes: [{
        gridLines : {
          display : false,
        }
      }]
    }
  }

  var DayValues = <?php echo json_encode($DayRecords) ?>;
  
  var bar_data = {
      data : [[1,DayValues[0]], [2,DayValues[1]], [3,DayValues[2]], [4,DayValues[3]], [5,DayValues[4]], [6,DayValues[5]], [7, DayValues[6]]],
      bars: { show: true }
    }
    $.plot('#order_vol_day', [bar_data], {
      grid  : {
        borderWidth: 1,
        borderColor: '#f3f3f3',
        tickColor  : '#f3f3f3'
      },
      series: {
         bars: {
          show: true, barWidth: 0.5, align: 'center',
        },
      },
      colors: ['#3c8dbc'],
      xaxis : {
        ticks: [[1,'SUN'], [2,'MON'], [3,'TUES'], [4,'WED'], [5,'THURS'], [6,'FRI'], [7,'SAT']]
      }
    })

  // This will get the first returned node in the jQuery collection.
  var areaChart       = new Chart(areaChartCanvas, { 
    type: 'line',
    data: areaChartData, 
    options: areaChartOptions
  })
    
</script>
@endpush
