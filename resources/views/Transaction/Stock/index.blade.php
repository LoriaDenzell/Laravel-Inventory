@extends('layouts.backend.app')

@push('css')
<!-- Date --> 
<link rel="stylesheet" href="{{ asset('asset/plugins/jquery-ui/jquery-ui.css')}}">
@endpush
<title>Stock Correction</title>
@section('content')

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Stock Correction</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Transaction</li>
                <li class="breadcrumb-item active">Stock</li>
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
            <h3 class="card-title"><i class="fas fa-clipboard-check"></i> Stock Correction</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method = "POST" action = "{{ route('transaction/stock')}}" autocomplete="off">
            @csrf
            <div class="card-body">
                <div class = "col-md-12 field-wrapper">
                    <div class = "form-group row">
                        <div class="col-md-12">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-info"></i> Info!</h5>
                                This will change the Total Stock of a product not the Stock Available.
                        </div>
                        </div>
                        <strong><font color="red">*</font> Indicates required fields.</strong>
                        <div class="col-md-12">
                            <label for="id_raw_product" class="col-sm-3 col-form-label">Product Name <font color="red">*</font></label>
                            <label for="id_raw_product" class="col-sm-4 col-form-label">Product Current Total Stock <font color="red">*</font></label>
                        </div>
                        <div class="col-sm-3"> 
                            <input type="hidden" readonly = "true" class="form-control" id="id_raw_product_1" name = "id_raw_product" placeholder = "Product Name" required>
                            <input type="text" readonly = "true" class="form-control" id="name_raw_product_1" name = "name_raw_product" placeholder = "Product Name" required>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" readonly = "true" class="form-control" id="total_stock_product_1" name = "total_stock_product" placeholder = "Product Current Total Stock" required>
                        </div>
                        <div class="col-sm-2">
                            <a href = "/transaction/stock/product/popup_media/" 
                                class = "btn btn-info" 
                                title = "Product" 
                                data-toggle = "modal" 
                                data-target = "#modal-default"><i class="fas fa-box"></i>&nbsp;&nbsp;Product</a>
                        </div>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class="col-md-12">
                        <label for="total" class="col-sm-4 col-form-label">New Stock Total <font color="red">*</font></label>
                    </div>
                    <div class="col-sm-4">
                        <input type = "number" class="form-control" id="total" name = "total" placeholder = "Total" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                    </div>
                    <div class="col-sm-4">
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-default float-right" name = "submit_correct" id = "submit_correct">Submit</button>
            </div>
            <!-- /.card-footer -->
            </form>
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

{{--    Modal --}} 
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-boxes"></i> Products</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"></span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@endsection

@push('js')
<script>
    $("#submit_correct").prop('disabled', true);

    $('#modal-default').bind('show.bs.modal', function(e){
        var link = $(e.relatedTarget);
        $(this).find(".modal-body").load(link.attr("href"));
    });
    
    $("input[type='text'], input[type='number']").on("keyup", function(){
        if($(this).val() != ""){
            $("#submit_correct").prop('disabled', false);
        } else {
            $("#submit_correct").prop('disabled', true);
        }
    });
</script>
@endpush