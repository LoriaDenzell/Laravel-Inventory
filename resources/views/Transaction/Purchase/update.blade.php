@extends('layouts.backend.app')
<title>Update Purchase Order</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Purchase Order</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Transaction</li>
                <li class="breadcrumb-item active">Update Purchase Order</li>
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
            <h3 class="card-title">Edit Purchase Order</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form class="form-horizontal" method = "POST" action = "{{ route('purchase-order.update', $data[0]->id) }}">
            @method('PUT')
            @csrf
            <div class="card-body">
                <strong><font color="red">*</font> Indicates required fields.</strong>
                <div class="form-group row">
                    <div class="col-md-6">
                        <label for="date" class="col-sm-6 col-form-label">Date <font color="red">*</font></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" 
                                    class="form-control" 
                                    id="date" 
                                    name = "date" 
                                    value = "{{ date('d-m-Y', strtotime($data[0]->date))}}" 
                                    required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label for="no_invoice" class="col-sm-6 col-form-label">Invoice No. <font color="red">*</font></label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                            </div>
                            <input type="text" 
                                    class="form-control" 
                                    id="no_invoice" 
                                    name = "no_invoice" 
                                    maxlength = "15"
                                    value = "{{$data[0]->no_invoice}}" 
                                    required readonly>
                        </div>
                    </div>
                </div>
                <div class = "form-group row">
                    <div class="col-md-12">
                        <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-info-circle"></i></strong> Purchase Order Information <font color="red">*</font></label>
                        <div class = "col-sm-12">
                            <textarea name = "information" class = "form-control" rows = "4">{{$data[0]->information}}</textarea>
                        </div>
                    </div>
                </div>

                <div class = "col-md-12 field-wrapper">
                    <div class = "form-group row">
                        <div class="col-md-12">
                            <label for="name_raw_product" class="col-sm-4 col-form-label">Product Name <font color="red">*</font></label>
                        </div>
                    </div>

                    <?php $i = 1; ?>

                    @foreach($detail as $key=>$value)
                        <div class = "form-group row">
                            <div class="col-sm-5">
                            
                                <input type="text" 
                                        class="form-control" 
                                        value ="<?=$value->product_name;?>" 
                                        id="name_raw_product_<?=$i;?>" 
                                        name = "name_raw_product[]" 
                                        placeholder = "Product Name" 
                                        required>
                            </div>
                            <div class="col-sm-3">
                                <input type="number" 
                                        class="form-control" 
                                        id="price_<?=$i;?>" 
                                        value ="<?=$value['price'];?>" 
                                        name = "price[]" 
                                        placeholder = "Price" 
                                        onkeyup="this.value=this.value.replace(/[^\d]/,'')"  
                                        required>
                            </div>
                            <?php 
                                if($i == 1)
                                {
                            ?>
                            <div class = "col-sm-2">
                                <a href = "javascript:void(0)" class = "btn btn-primary add_Button" title = "Add Purchase"><i class = "fas fa-plus"></i></a>
                            </div>
                            <?php
                                }else if($i >= 2)
                                {
                            ?>
                            <div class = "col-sm-2">
                                <a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete Purchase"><i class = "fas fa-minus"></i></a>
                            </div>
                            <?php
                                }
                            ?>
                        </div>
                        <?php $i++; ?>
                    @endforeach
                </div>
            </div>
            <!-- /.card-body -->
            <div class="card-footer">
                <button type="submit" class="btn btn-default float-right" name = "submit_edit" id = "submit_edit">Submit</button>
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
    $("#submit_edit").prop('disabled', true);

    $(document).ready(function(){
        var addButton = $('.add_Button');
        var wrapper = $('.field-wrapper');
        var X = "{{ $detail_count + 1}}";

        $(addButton).click(function(){ 
            X++;
            $(wrapper).append(' <div class = "form-group row"> ' +
                    '<div class="col-sm-3">' +
                        '<input type="hidden" readonly = "true" class="form-control" id="name_current_product_'+X+'" name = "name_current_product[]" value = {{ old("name_current_product_'+X+'") }} required>' +
                        '<input type="text" class="form-control" id="name_raw_product_'+X+'" name = "name_raw_product[]" placeholder = "Product Name" required>'+
                    '</div>' +
                    '<div class="col-sm-3">' +
                        '<input type="number" class="form-control" id="price_'+X+'" name = "price[]" placeholder = "Price" required>' +
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

    $("input").each(function() {
        var element = $(this);

        if(element.val() != ""){
            $("#submit_edit").prop('disabled', false);
        } else {
            $("#submit_edit").prop('disabled', true);
        }
    });
</script>
@endpush