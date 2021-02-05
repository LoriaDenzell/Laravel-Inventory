@extends('layouts.backend.app')
<title>Update Sales Order</title>
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Update Sales Order</h1>
            </div>
            <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                <li class="breadcrumb-item">Transaction</li>
                <li class="breadcrumb-item active">Update Sales</li>
            </ol>
            </div>
        </div>
    </div>
</div>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
                <!-- Horizontal Form -->
                <div class="card card-info">
                    <div class="card-header">
                        <h3 class="card-title">Update Sales Order</h3>
                    </div>
                    <form class="form-horizontal" method = "POST" action = "{{ route('sales.update', $salesH->id) }}">
                        @method('PUT')
                        @csrf
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    </ul>
                                </div>
                            @endif
                            <div class="form-group row">
                                <div class="col-md-12">
                                    <strong><font color="red">*</font> Indicates required fields.</strong>
                                </div>
                                <div class="col-md-4">
                                    <label for="date" class="col-sm-6 col-form-label">Date <font color="red">*</font></label>
                                    <div class="input-group mb-2 {{$errors->has('date') ? 'has-error' : ''}}" >
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" 
                                                class="form-control" 
                                                id="date" 
                                                name = "date"
                                                autocomplete="off" 
                                                value="{{ $salesH->date }}"
                                                required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="invoice_no" class="col-sm-6 col-form-label">Invoice No. <font color="red">*</font></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend {{$errors->has('invoice_no') ? 'has-error' : ''}}">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type="text" 
                                                class="form-control" 
                                                id="invoice_no" 
                                                name = "invoice_no" 
                                                maxlength = "15"
                                                value="{{ $salesH->invoice_no }}"
                                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                                                readonly>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label for="customer" class="col-sm-6 col-form-label">Customer Name <font color="red">*</font></label>
                                    <div class="input-group mb-2">
                                        <div class="input-group-prepend {{$errors->has('customer') ? 'has-error' : ''}}">
                                            <span class="input-group-text"><i class="fas fa-hashtag"></i></span>
                                        </div>
                                        <input type = "text" 
                                                class="form-control" 
                                                id="customer" 
                                                name = "customer" 
                                                placeholder = "Customer Name" 
                                                autocomplete = "on"
                                                value="{{ $salesH->customer }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class = "form-group row">
                                <div class="col-md-12">
                                    <label for = "info" class = "col-md-4 col-form-label"><strong><i class="fas fa-info-circle"></i></strong> Sales Order Information</label>
                                    <div class = "col-sm-12  {{$errors->has('product_information') ? 'has-error' : ''}}">
                                        <textarea name = "product_information" 
                                                class = "form-control" 
                                                rows = "4"
                                                >{{ $salesH->information }}</textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <div class = "col-md-12 field-wrapper">
                                <div class="col-md-12">
                                    <label for="id_raw_product" class="col-sm-4 col-form-label">Product Name <font color="red">*</font></label>
                                </div> 
                                @for($i=0; $i<@count($salesD); $i++)
                                    <div class = "form-group row">
                                        <br>
                                        <div class="col-sm-3">
                                            <input type="hidden" readonly = "true" value="{{$salesD[$i]->product->product_id}}" class="form-control" id="name_raw_product_{{$i}}" name = "id_raw_product[]" placeholder = "Product Name" required>
                                            <input type="text" 
                                                    readonly = "true" 
                                                    class="form-control" 
                                                    id="name_raw_product_{{$i}}" 
                                                    name = "name_raw_product[]" 
                                                    value="{{$salesD[$i]->product->product_name}}"
                                                    placeholder = "Product Name">
                                        </div>
                                        <div class="col-sm-1">
                                            <a href = "/transaction/sales/product/popup_media/1" 
                                                class = "btn btn-info" 
                                                title = "Product" 
                                                data-toggle = "modal" 
                                                data-target = "#modal-default">Product</a>
                                        </div>
                                        <div class="col-sm-2">
                                            <input type="number" 
                                                class="form-control" 
                                                id="price_{{$i}}" 
                                                name = "price[]" 
                                                value="{{$salesD[$i]->price}}"
                                                placeholder = "Price" 
                                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="number" 
                                                class="form-control" 
                                                id="total_{{$i}}" 
                                                name = "total[]" 
                                                value="{{$salesD[0]->total}}"
                                                placeholder = "Quantity" 
                                                onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
                                        </div>
                                        <div class="col-sm-2">
                                        <input type="hidden" readonly = "true" value="{{$sales_addons[$i]->id}}" class="form-control" name = "id_raw_addon[]">
                                            <select class = "form-control" id = "addons" name = "addons[]">
                                                @foreach($addons as $addon)
                                                    <option value="{{$addon->id}}" {{$sales_addons[$i]->addon_id == $addon->id  ? 'selected' : ''}}>{{$addon->addon_name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-sm-1">
                                            <input type="number" 
                                                class="form-control" 
                                                id="addon_total" 
                                                name = "addon_total[]" 
                                                value="{{$sales_addons[$i]->total_addon != null ? $sales_addons[$i]->total_addon : ''}}"
                                                placeholder = "Qty" 
                                                onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                                        </div>
                                        @if(@count($salesD) == 1)
                                            <div class = "col-sm-1">
                                                <a href = "javascript:void(0)" class = "btn btn-primary add_Button" title = "Add Row"><i class = "fas fa-plus"></i></a>
                                            </div>
                                        @endif
                                        @if(@count($salesD) > 1)
                                            <div class = "col-sm-2">
                                                <a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete"><i class = "fas fa-minus"></i></a>
                                            </div>
                                        @endif
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-default float-right" name = "submit_update" id = "submit_update">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

{{--    Modal --}}  
<div class="modal fade" id="modal-default">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"><i class="fas fa-boxes"></i> Products List</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"</span>
                </button>
            </div>
            <div class="modal-body">
                
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script>    
    $(document).ready(function(){
        var addButton = $('.add_Button');
        var wrapper = $('.field-wrapper');
        var X = "{{ $detail_count + 1}}";

        $(addButton).click(function(){
            X++;
            $(wrapper).append(' <div class = "form-group row" id = "salesRow_'+X+'"> ' +
                    '<div class="col-sm-3">' +
                        '<input type="hidden" class="form-control" id="id_raw_product_'+X+'" name = "id_raw_product[]" readonly="readonly" value = {{ old("id_raw_product_'+X+'") }} required>' +
                        '<input type="text" class="form-control" id="name_raw_product_'+X+'" name = "name_raw_product[]" placeholder = "Product Name"  value = {{ old("name_raw_product_'+X+'") }}>'+
                    '</div>' +
                    '<div class="col-sm-1">' +
                        '<a href = "/transaction/sales/product/popup_media/'+X+'" class = "btn btn-info" title = "Product" data-toggle = "modal" data-target = "#modal-default">Product</a>' +
                    '</div>' +
                    '<div class="col-sm-2">' +
                        '<input type="text" class="form-control" id="price_'+X+'" value="{{ old("price_'+X+'") }}" name = "price[]" placeholder = "Price" required>' +
                    '</div>' +
                    '<div class="col-sm-1">' +
                        '<input type="text" class="form-control" id="total_'+X+'" value="{{ old("total_'+X+'") }}" name = "total[]" placeholder = "Qty" required>' +
                    '</div>' +
                    '<div class="col-sm-2">' +
                        '<select class = "form-control" id = "addons" name = "addons[]">' +
                                '<option selected disabled value = "">Choose Addons</option>' +
                                    @foreach($addons as $addon)
                                        '<option value="{{$addon->id}}">{{$addon->addon_name}}</option>' +
                                    @endforeach
                            '</select>' +
                    '</div>' +
                    '<div class = "col-sm-1">' +
                        '<input type="number" class="form-control" id="addon_total" name = "addon_total[]" placeholder = "Qty">' + 
                    '</div>' +
                    '<div class = "col-sm-1">' +
                        '<a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete"><i class = "fas fa-minus"></i></a>' +
                    '</div>' +
                    '<div class = "col-sm-1">' +
                        '<strong id="subtotal_'+X+'"></strong>' +
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
        var currentDateInput = $(this).val();

    }}).on("change", function() {

        currentDate = this.value;
        
        if (!moment(currentDate, 'DD-MM-YYYY', true).isValid()){         
            $(this).val('');      
            alert("Not a valid date! Date must be in 'DD-MM-YYYY' format. Choose from date picker by clicking the Date field.");
        }
    });

    $('#modal-default').bind('show.bs.modal', function(e){
        var link = $(e.relatedTarget);
        $(this).find(".modal-body").load(link.attr("href"));
    });

    $("#submit_create").hover(function(){

        var product_elements_length = $("input[id^='name_raw_product_']").length;
        var product_quantities_array = $("input[id^='total']");
        var product_elements_array = $("input[id^='name_raw_product_']");

        var emptyFlag = false;

        for(i=0;i<product_elements_length;i++)
        {
            if(product_elements_array.eq(i).val() == "No matching records found" || product_elements_array.eq(i).val() == ""){
                emptyFlag = true;
            }

            if(product_quantities_array.eq(i).val() == ""){
                emptyFlag = true;
            }
        }

        if(emptyFlag || $("#name_ven").val() == ''){
            alert("Please fill all required fields before submitting.");
            return false;
        }else{
            $('#submit_create').prop('disabled', false);
            console.log('Everything is ready to be submitted.');
        }
    });

</script>
@endpush