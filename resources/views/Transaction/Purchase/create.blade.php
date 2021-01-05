@extends('layouts.backend.app')

@push('css')
<!-- Date --> 
<link rel="stylesheet" href="{{ asset('asset/plugins/jquery-ui/jquery-ui.css')}}">
@endpush
<title>Create Purchase Order</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Create Purchase Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Transaction</li>
                <li class="breadcrumb-item active">Purchase</li>
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
            <h3 class="card-title"><i class="fas fa-paste"></i> Create Purchase Order</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method = "POST" action = "{{ route('purchase-order.store') }}" autocomplete = "off">
            @csrf
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-12">
                        <div class="alert alert-info alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-info" d></i> Info!</h5>
                            A purchase order, or PO, is an <strong>official document issued by a buyer</strong> 
                            committing to pay the seller for the sale of specific products or services to be 
                            delivered in the future.
                        </div>
                        <strong><font color="red">*</font> Indicates required fields.</strong>
                    </div>
                    <div class="col-md-4">
                        <label for="date" class="col-sm-6 col-form-label">Date <font color="red">*</font></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" 
                                    class="form-control" 
                                    id="date" 
                                    name = "date" 
                                    required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="no_invoice" class="col-sm-6 col-form-label">Invoice No. <font color="red">*</font></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            </div>
                            <input type="text" 
                                    class="form-control" 
                                    id="no_invoice" 
                                    name = "no_invoice" 
                                    value = "{{ $invoice_no }}"
                                    maxlength = "15"
                                    required readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label for="no_invoice" class="col-sm-6 col-form-label">Purchase Status <font color="red">*</font></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                            </div>
                                <select name="purchase_status" id="purchase_status" class="custom-select">
                                <option value="received" selected>Received</option>
                                <option value="order">Order</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class="col-md-12">
                        <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-info-circle"></i></strong> Purchase Order Information </label>
                        <div class = "col-sm-12">
                            <textarea name = "product_information" 
                                        class = "form-control" 
                                        rows = "4"
                                        style="text-transform: uppercase"></textarea>
                        </div>
                    </div>
                </div>
                
                <div class = "col-md-12 field-wrapper">
                    <div class = "form-group row">
                        <div class="col-md-12">
                            <label for="id_raw_product" class="col-sm-4 col-form-label">Expense Name</label>
                        </div>
                        <div class="col-sm-3">
                            <input type="hidden" readonly = "true" class="form-control" id="id_raw_product_1" name = "id_raw_product[]" placeholder = "Product Name" required>
                            <input type="text" 
                                    class="form-control" 
                                    id="name_raw_product_1" 
                                    name = "name_raw_product[]" 
                                    placeholder = "Product Name" 
                                    autocomplete = "off"
                                    required>
                        </div>
                        <div class="col-sm-3">
                            <input type="number" 
                                    class="form-control" 
                                    id="price_1" 
                                    name = "price[]" 
                                    placeholder = "Product Price" 
                                    min = "1"
                                    max = "999999999"
                                    onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                                    required>
                        </div>
                        <div class = "col-sm-2">
                            <a href = "javascript:void(0)" 
                                class = "btn btn-primary add_Button" 
                                title = "Add Row"><i class = "fas fa-plus"></i></a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-default float-right" name = "submit_create" id = "submit_create">Submit</button>
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

@endsection

@push('js')
<script>    

    $("#submit_create").prop('disabled', true);

    $(document).ready(function(){
        var addButton = $('.add_Button');
        var wrapper = $('.field-wrapper');
        var X = "{{ $detail_count + 1}}";

        $(addButton).click(function(){
            X++;
            $(wrapper).append(' <div class = "form-group row"> ' +
                    '<div class="col-sm-3">' +
                        '<input type="hidden" class="form-control" id="id_raw_product_'+X+'" name = "id_raw_product[]" placeholder = "Product Name" required>' +
                        '<input type="text" class="form-control" id="name_raw_product_'+X+'" name = "name_raw_product[]" placeholder = "Product Name" required>'+
                    '</div>' +
                    '<div class="col-sm-3">' +
                        '<input type="number" class="form-control" id="price_'+X+'" name = "price[]" placeholder = "Product Price" onkeyup="this.value=this.value.replace(/[\d]/, '+0+')" required>' +
                    '</div>' +
                    '<div class = "col-sm-2">' +
                        '<a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete"><i class = "fas fa-minus"></i></a>' +
                    '</div>' +
                '</div>'
            );
        });

        $(wrapper).on('click', '.remove', function(e){
            if(confirm("Do you want to delete this row?")){
                e.preventDefault();
                $(this).parent().parent().remove();
            }
        });

        $("#date").datepicker('setDate', new Date());

        $('#date').datepicker({
            autoclone:true,
            dateFormat:'dd-mm-yy',
        });
    });

    $("#date").datepicker({
        onSelect: function(dateText) {
        console.log("input's current value: " + this.value);
    }}).on("change", function() {

        currentDate = this.value;
        if (!moment(currentDate, 'DD-MM-YYYY', true).isValid()){         
            $(this).val('');      
            alert("Not a valid date! Date must be in 'DD-MM-YYYY' format. Choose from date picker by clicking the Date field.");
        }  
    });

    $("input[id^='name_raw_product_'], input[id^='price_']").on("keyup", function(){
        if($(this).val() != ""){
            $("#submit_create").prop('disabled', false);
        } else {
            $("#submit_create").prop('disabled', true);
        }
    });

</script>
@endpush