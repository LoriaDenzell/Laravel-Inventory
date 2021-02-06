@extends('layouts.backend.app')
<title>Sales Order Management</title>
@section('content')
@push('css')
<style>
.modal { overflow: auto !important; }
</style>
@endpush

<!-- Content Header (Page header) -->
<div class="content-header">
  <div class="container-fluid">
    <div class="row mb-2">
      <div class="col-sm-6">
          <h1 class="m-0 text-dark">Sales Order Management</h1>
      </div>
      <div class="col-sm-6">
      <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="/home">Home</a></li>
        <li class="breadcrumb-item">Transaction</li>
        <li class="breadcrumb-item active">Sales</li>
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
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Sales Order List</h3>
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-file-alt"></i> Create New Sales Order </button>
            <!--<a class = "btn btn-success btn-sm float-right" href = "{{ route('sales.create')}}" title = "Create"><i class="fas fa-file-alt"></i> Create Sales Order</a>-->
            @role('A')
              <a class ="btn btn-warning btn-sm float-right mr-1" href="{{ route('sales-export') }}" title = "Export"><i class="fas fa-file-export"></i> Export Data</a>
            @endrole
          </div>
          <ul class = "nav nav-tabs" role = "tablist" id = "myTab">
            <li class = "nav-item">
                <a class = "nav-link active" id = "active-panel" data-toggle = "tab" href = "#activePanel" role = "tab"><i class="fas fa-check"></i> Active</a>
            </li>
            <li class = "nav-item">
                <a class = "nav-link" id = "trash-panel" data-toggle = "tab" href = "#trashPanel" role = "tab"><i class="fas fa-times"></i> Trash</a>
            </li>
          </ul>
          <!-- /.card-header -->
          <div class="card-body"> 
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="sales_tbl" class="table table-bordered table-striped" style = "width: 100%;">
                  <thead>
                    <tr> 
                      <th><button type="button" name="bulk_deactivate" id="bulk_deactivate" class="btn btn-danger btn-xs"><i class = 'nav-icon fas fa-trash-alt'></i></button></th>
                      <th>Invoice #</th>
                      <th>Customer</th>
                      <th>Date</th>
                      <th>Total Revenue</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  
                  <tfoot>
                  <tr>
                    <th></th>
                    <th>Invoice #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Revenue</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="sales_trash" class="table table-bordered table-striped" data-toggle = "table2_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th><button type="button" name="bulk_reactivate" id="bulk_reactivate" class="btn btn-success btn-xs"><i class="fas fa-trash-restore-alt"></i></button></th>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Status</th>
                      <th>Action</th>
                    </tr>
                    </thead> 
                    
                    <tfoot>
                    <tr>
                      <th></th>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Status</th>
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

{{-- Create Modal Start --}}  
<div class="modal fade" id="modalCreate" tabIndex="-1" data-focus-on="input:first"aria-labelledby="create-modal-title" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ route('sales.store') }}" autocomplete="off">
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
            <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i> Create Sales Order</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <!-- MODAL BODY -->
        <div class="modal-body">
          <div class="form-group row">
            <div class="col-md-12">
              <div class="alert alert-info alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-info" d></i> Info!</h5>
                A sales order is a document generated by the seller upon receiving a 
                purchase order from a <strong>buyer/customer</strong> specifying the details about the product or service 
                along with price, quantity, buyer details.
              </div>
              <strong><font color="red">*</font> Indicates required fields.</strong>
            </div>
            <div class="col-md-4">
              <label for="date" class="col-sm-6 col-form-label">Date <font color="red">*</font></label>
              <div class="input-group mb-2 {{$errors->has('date') ? 'has-error' : ''}}" >
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="date" 
                        class="form-control" 
                        id="date" 
                        name = "date"
                        autocomplete="off" 
                        value="{{ old('date') }}"
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
                        value="{{ $invoice_no }}"
                        onkeyup="this.value=this.value.replace(/[^\d]/,'')" 
                        required readonly>
              </div>
            </div>
            <div class="col-md-4">
              <label for="customer" class="col-sm-6 col-form-label">Customer Name</label>
              <div class="input-group mb-2">
                <div class="input-group-prepend {{$errors->has('customer') ? 'has-error' : ''}}">
                    <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                </div>
                <input type = "text" 
                        class="form-control" 
                        id="customer" 
                        name = "customer" 
                        placeholder = "Customer Name" 
                        autocomplete = "on"
                        value="{{ old('customer') }}"
                        style="text-transform: uppercase">
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
                        value="{{ old('product_information') }}"
                        style="text-transform: uppercase"
                        ></textarea>
              </div>
            </div>
          </div>
          <div class = "col-md-12 field-wrapper" id="salesInputArea">
            <div class = "form-group row" id = "salesRow_1">
              <div class="col-md-12">
                <label for="id_raw_product" class="col-sm-4 col-form-label">Product Name <font color="red">*</font></label>
              </div>
              <div class="col-sm-3">
                <input type="hidden" 
                        class="form-control" 
                        id="id_raw_product_1" 
                        name = "id_raw_product[]" 
                        required>

                <input type="text" 
                        class="form-control" 
                        id="name_raw_product_1" 
                        name = "name_raw_product[]" 
                        value="{{ old('id_raw_product_1') }}"
                        placeholder = "Product Name"
                        readonly="readonly" 
                        required>
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
                    id="price_1" 
                    name = "price[]" 
                    value="{{ old('price_1') }}"
                    placeholder = "Price" 
                    onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
              </div>
              <div class="col-sm-1">
                <input type="number" 
                    class="form-control" 
                    id="total_1" 
                    name = "total[]" 
                    value="{{ old('total_1') }}"
                    placeholder = "Qty" 
                    min = "1"
                    onkeyup="this.value=this.value.replace(/[^\d]/,'')" required>
              </div>
              @if(count($addons) > 0)
                <div class="col-sm-2">
                  <select class = "form-control" id = "addons_1" name = "addons[]">
                    <option selected disabled>Choose Addons</option>
                    @foreach($addons as $addon)
                        <option value="{{$addon->id}}">{{$addon->addon_name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-sm-1">
                  <input type="number" 
                      class="form-control" 
                      id="addon_total_1" 
                      name = "addon_total[]" 
                      value="{{ old('addon_total') }}"
                      placeholder = "Qty" 
                      onkeyup="this.value=this.value.replace(/[^\d]/,'')">
                </div>
              @endif
              <div class = "col-sm-1">
                <a href = "javascript:void(0)" class = "btn btn-primary add_Button" title = "Add Row"><i class = "fas fa-plus"></i></a>
              </div>
              <div class = "col-sm-1">
                <strong id="subtotal_1"></strong>
              </div>
            </div>
          </div>
        
          <!-- MODAL FOOTER -->
          <div class="modal-footer">
            <button type="submit" class="btn btn-danger float-right" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success float-right">Submit</button>
          </div>

        </div>
      </form>
    </div> 
  </div>
</div>
{{-- Create Modal End --}}  

{{-- Product Modal --}}  
<div class="modal hide fade" tabindex="-1" data-focus-on="input:first" id="modal-default">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-info">
        <h4 class="modal-title"><i class="fas fa-boxes"></i> Products List</h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
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

  var date = new Date();
  var day = ("0" + date.getDate()).slice(-2);
  var month = ("0" + (date.getMonth() + 1)).slice(-2);
  var today = date.getFullYear() + "-" + (month) + "-" + (day);
  $('#date').val(today);

  $(document).ready(function() {

    $("#sales_tbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('sales/datatable')}}",
      order:[1, 'desc'],
      columns:[
        {data:"checkbox", searchable:false, sortable:false},
        {data:'invoice_no', name: 'invoice_no'},
        {data:'customer', name: 'customer'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'action', name: 'action', searchable: true, sortable: true}
      ]
    });

    $("#sales_trash").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('sales/datatableTrash')}}",
      order:[1, 'desc'],
      columns:[
        {data:"checkbox_t", searchable:false, sortable:false},
        {data:'invoice_no', name: 'invoice_no'},
        {data:'customer', name: 'customer'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    var addButton = $('.add_Button');
    var wrapper = $('.field-wrapper');
    var X = "{{ $detail_count + 1}}";

    var inputQtyLength = $('[id^=total_]').length;

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
                @if(count($addons) > 0)
                '<div class="col-sm-2">' +
                    '<select class = "form-control" id = "addons_'+X+'" name = "addons[]">' +
                            '<option selected disabled>Choose Addons</option>' +
                                @foreach($addons as $addon)
                                    '<option value="{{$addon->id}}">{{$addon->addon_name}}</option>' +
                                @endforeach
                        '</select>' +
                '</div>' +
                '<div class = "col-sm-1">' +
                    '<input type="number" class="form-control" id="addon_total_'+X+'" name = "addon_total[]" placeholder = "Qty">' + 
                '</div>' +
                @endif
                '<div class = "col-sm-1">' +
                    '<a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete"><i class = "fas fa-minus"></i></a>' +
                '</div>' +
                '<div class = "col-sm-1">' +
                    '<strong id="subtotal_'+X+'"></strong>' +
                '</div>' +
            '</div>'
        );

        $("[id^='addons_']").change(function() {
          var addonElement = $(this).closest(".row").find("[id^='addon_total_']");
          addonElement.prop("required",true);
          addonElement.attr({"min" : 1});
        });
    });

    $("[id^='addons_']").change(function() {
      var addonElement = $(this).closest(".row").find("[id^='addon_total_']");
      addonElement.prop("required",true);
      addonElement.attr({"min" : 1});
    });

    $(wrapper).on('click', '.remove', function(e){
      if(confirm("Do you want to delete this row?")){
        e.preventDefault();
        $(this).parent().parent().remove();
      }
    });

    $('#modal-default').bind('show.bs.modal', function(e){
        var link = $(e.relatedTarget);
        $(this).find(".modal-body").load(link.attr("href"));
    });

    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });

    $('#sales_trash').on('click', '.btn-danger[data-remote]', function (e) 
    { 
      e.preventDefault(); 
      console.log('ok');
    });
  });

  function deactivateSalesData(dt){
    if(confirm("Are you sure you want to deactivate this data?")){
      $.ajax({
        type: 'GET',
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

  $(document).on('click', '#bulk_deactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Deactivate this Sales Order/s?"))
    {
        $('.sales_checkbox:checked').each(function(){
            id.push($(this).val());
        });
        if(id.length > 0)
        {
            $.ajax({
                url:"{{ url('sales/deactivateSales')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    location.reload();
                }
            });
        }
        else
        {
            alert("Please select atleast one checkbox");
        }
    }
  });

  $(document).on('click', '#bulk_reactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Reactivate this Sales Order/s?"))
    {
        $('.sales_trash_checkbox:checked').each(function(){
            id.push($(this).val());
        });
        if(id.length > 0)
        {
            $.ajax({
                url:"{{ url('sales/reactivateSales')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    location.reload();
                }
            });
        }
        else
        {
            alert("Please select atleast one checkbox");
        }
    }
  });
</script>
@endpush