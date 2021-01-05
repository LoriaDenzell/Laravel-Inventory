
<table class = "table table-bordered dataTable-sales-product" cellspacing = 0 style = "width: 100%;" id = "table-media-{{$id_count}}">
    <thead>
        <tr>
            <td>ID</td>
            <td>Name</td>
            <td>Type</td>
            <td>Stocks Available</td>
            <td>Selling Price</td>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>

<script src="{{ asset('asset/plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('asset/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>

<script>
    $('#table-media-{{$id_count}}').on('click', 'tbody tr', function(e){
        e.preventDefault();

        $('#id_raw_product_{{$id_count}}').val($(this).find('td').html());
        $('#name_raw_product_{{$id_count}}').val($(this).find('td').next().html());
        $('#price_{{$id_count}}').val($(this).find('td').next().next().next().next().html());
    });

    $('.dataTable-sales-product').DataTable({
        processing:false, 
        serverSide:true, 
        ajax:"{{route('browse-product/datatable')}}",
        columns:[
            {data:'product_id', name: 'product_id'},
            {data:'product_name', name: 'product_name'},
            {data:'product_type', name: 'product_type'},
            {data:'stock_available', name: 'stock_available'},
            {data:'product_selling_price', name: 'product_selling_price'},
        ],
        responsive: true
    });

    $('#table-media-{{$id_count}}').on('click', 'tbody tr', function(e){
        $('#modal-default').modal('hide');
    });
</script>