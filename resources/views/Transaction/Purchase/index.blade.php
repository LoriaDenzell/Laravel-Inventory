@extends('layouts.backend.app')
<title>Purchase Order Management</title>
@section('content')

 <!-- Content Header (Page header) -->
 <div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Purchase Order Management</h1>
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
      <div class="col-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title"><i class="fas fa-clipboard-list"></i> Purchase Order List</h3>
            <!--<a class = "btn btn-success btn-sm float-right" href = "{{ route('purchase-order.create')}}" title = "Create"><i class="fas fa-file-alt"></i> Create Purchase Order</a>-->
            <button type="button" class="btn btn-primary btn-sm float-right" data-toggle="modal" data-target="#modalCreate"><i class="fas fa-file-alt"></i> Create New Purchase Order </button>
            @role('A')
              <a class ="btn btn-warning btn-sm float-right mr-1" href="{{ route('purchases-export') }}" title = "Export"><i class="fas fa-file-export"></i> Export Data</a>
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
          <div class="card-body">
            <div class = "tab-content">
              <div class = "tab-pane fade in active show" id = "activePanel" role = "tabPanel">
                <table id="purchase_tbl" class="table table-bordered table-striped" data-toggle = "table1_" style = "width: 100%;">
                  <thead>
                  <tr>
                    <th><button type="button" name="bulk_deactivate" id="bulk_deactivate" class="btn btn-danger btn-xs"><i class = 'nav-icon fas fa-trash-alt'></i></button></th>
                    <th>Invoice No.</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Modified By</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  
                  <tfoot>
                  <tr>
                    <th></th>
                    <th>Invoice No.</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Modified By</th>
                    <th>Action</th>
                  </tr>
                  </tfoot>
                </table>
              </div>

              <div class = "tab-pane fade" id = "trashPanel" role = "tabPanel">
                <table id="purchase_trash" class="table table-bordered table-striped" data-toggle = "table2_" style = "width: 100%;">
                  <thead>
                    <tr>
                      <th><button type="button" name="bulk_reactivate" id="bulk_reactivate" class="btn btn-success btn-xs"><i class="fas fa-trash-restore-alt"></i></button></th>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Modified By</th>
                      <th>Action</th>
                    </tr>
                    </thead> 
                    
                    <tfoot>
                    <tr>
                      <th></th>
                      <th>Invoice No.</th>
                      <th>Date</th>
                      <th>Total</th>
                      <th>Modified By</th>
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
<div class="modal fade" id="modalCreate" tabIndex="-1" aria-labelledby="create-modal-title" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form class="form-horizontal" method="POST" action="{{ route('purchase-order.store') }}" autocomplete="off">
        @csrf

        <!-- MODAL HEADER -->
        <div class="modal-header bg-info">
            <h4 class="modal-title" id="create-modal-title"><i class="fas fa-plus"></i>Create Purchase Order</h4>
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
                <input type="date" 
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
          <div class = "col-md-12 field-wrapper" id="purchaseInputArea">
            <div class = "form-group row">
              <div class="col-md-12">
                <label for="id_raw_product" class="col-sm-4 col-form-label">Purchase Name</label>
              </div>
              <div class="col-sm-3">
                <input type="hidden" readonly = "true" class="form-control" id="id_raw_product_1" name = "id_raw_product[]" placeholder = "Product Name" required>
                <input type="text" 
                        class="form-control" 
                        id="name_raw_product_1" 
                        name = "name_raw_product[]" 
                        autocomplete = "off"
                        style="text-transform: uppercase"
                        placeholder = "Purchase Name" 
                        required>
              </div>
              <div class="col-sm-3">
                <input type="number" 
                        class="form-control" 
                        id="price_1" 
                        name = "price[]" 
                        placeholder = "Purchase Cost" 
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

        <!-- MODAL FOOTER -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger float-right">Cancel</button>
          <button type="submit" class="btn btn-success float-right">Submit</button>
        </div>

      </form>
    </div> 
  </div>
</div>
{{-- Create Modal End --}}  

@endsection

@push('js')
<script>

  var date = new Date();
  var day = ("0" + date.getDate()).slice(-2);
  var month = ("0" + (date.getMonth() + 1)).slice(-2);
  var today = date.getFullYear() + "-" + (month) + "-" + (day);
  $('#date').val(today);

  $(function () {
    $.ajaxSetup({
      header:{
        'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
      }
    });

    var addButton = $('.add_Button');
    var wrapper = $('.field-wrapper');
    var X = "{{ $detail_count + 1}}";

    $(addButton).click(function(){
        X++;
        $(wrapper).append(' <div class = "form-group row"> ' +
                '<div class="col-sm-3">' +
                    '<input type="hidden" class="form-control" id="id_raw_product_'+X+'" name = "id_raw_product[]" required>' +
                    '<input type="text" class="form-control" id="name_raw_product_'+X+'" name = "name_raw_product[]" style="text-transform: uppercase" placeholder = "Product Name" required>'+
                '</div>' +
                '<div class="col-sm-3">' +
                    '<input type="number" class="form-control" id="price_'+X+'" name = "price[]" placeholder = "Product Price" onkeyup="this.value=this.value.replace(/[\d]/, '+0+')" min="1" required>' +
                '</div>' +
                '<div class = "col-sm-2">' +
                    '<a href = "javascript:void(0)" class = "btn btn-danger remove" title = "Delete"><i class = "fas fa-minus"></i></a>' +
                '</div>' +
            '</div>'
        );
    });

    $(wrapper).on('click', '.remove', function(e){
      e.preventDefault();
      $(this).parent().parent().remove();
    });

    $("#purchase_tbl").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{!! url('purchase-order/datatable') !!}",
      order:[1, 'desc'],
      columns:[
        {data:"checkbox", searchable:false, sortable:false},
        {data:'no_invoice', name: 'no_invoice'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'user_modified', name: 'user'},
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });

    var table2 = $("#purchase_trash").DataTable({
      responsive:true,
      processing:true,
      pagingType: 'full_numbers',
      stateSave:false,
      scrollY:true,
      scrollX:true,
      autoWidth: false,
      ajax:"{{url('purchase-order/datatableTrash')}}",
      order:[1, 'desc'],
      columns:[
        {data:"checkbox_t", searchable:false, sortable:false},
        {data:'no_invoice', name: 'no_invoice'},
        {data:'date', name: 'date'},
        {data:'total', name: 'total'},
        {data:'user_modified', name: 'user'},
        {data:'action', name: 'Action', searchable: false, sortable:false},
      ]
    });
  });

  //If tab is clicked
  $(document).ready(function(){
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
        .columns.adjust();
    });
  });
  
  function deactivatePurchaseOrder(dt){
    if(confirm("Are you sure you want to Deactivate this Purchase Order?") == true){
      $.ajax({
        type: 'DELETE',
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
    }else{
      return false;
    }
    return false;
  }

  function undoTrash(dt){
    if(confirm("Are you sure you want to Re-Activate this Purchase Order?")){
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
            alert("Error Re-Activating purchase order");
          }
      });
    }
    return false;
  }

  $(document).on('click', '#bulk_deactivate', function(){
    var id = [];
    if(confirm("Are you sure you want to Deactivate this Purchase Order/s?"))
    {
        $('.purchases_checkbox:checked').each(function(){
            id.push($(this).val());
        });
        if(id.length > 0)
        {
            $.ajax({
                url:"{{ url('purchase-order/deactivatePurchase')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    location.reload();
                    //$('#purchase_tbl').DataTable().ajax.reload();
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
        $('.purchases_trash_checkbox:checked').each(function(){
            id.push($(this).val());
        });
        if(id.length > 0)
        {
            $.ajax({
                url:"{{ url('purchase-order/reactivatePurchase')}}",
                method:"get",
                data:{id:id},
                success:function(data)
                {
                    alert(data);
                    location.reload();
                    //$('#purchase_trash').DataTable().ajax.reload();
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