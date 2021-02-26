@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

        <div class="mb-6 grid grid-cols-2 gap-4 border-b-2 pb-3">
            <div class="text-left">
                <label class="block">Debitor : @if($lend->user > 0) {{ $lend->user()->name }} @else {{ $lend->name }} @endif</label>
                <label class="block">Lend : {{ $lend->nominal }}</label>
                <label class="block">Payment : {{ $totalPayment }}</label>
                <label class="block">Left Payment : {{ $leftPayment }}</label>
            </div>
            <div class="text-right">
                <a href="{{ route('pay.lend') }}" class="bg-gray-100 text-black px-4 py-3 rounded-lg font-bold text-right"><i class="fas fa-step-backward border-1"></i> Back</a> &nbsp; 
                <a href="{{ route('pay.add', $lend->id) }}" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold text-right"><i class="fas fa-plus"></i> Add Payment</a>
            </div>
        </div>

        <div class="table-responsive">
            <table class="display dataTable" id="datatablesList">
                <thead>
                    <tr>
                        <th>Checkbox</th>
                        <th class="dt-head-center">ID</th>
                        <th class="dt-head-center">Created Date</th>
                        <th class="dt-head-center">Payment</th>
                        <th class="dt-head-center">Note</th>
                        <th class="dt-head-center">Status</th>
                        <th class="dt-head-center"></th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<script>
let dtRef = null;
let csrfName = '{{ $csrfTokenName }}';
let csrfValue = '{{ $csrfTokenValue }}';

jQuery(document).ready(function () {
    setCsrf(csrfName, csrfValue);
    
    dtRef = jQuery('#datatablesList').DataTable({
        // dom : "<'row'<'col-md-6'l><'col-md-6'f>><'row'<'col-md-12'tr>><'row'<'col-sm-12 col-md-6'i><'col-sm-12 col-md-6'p>>",
        initComplete : function () {
            jQuery('.dataTables_filter input').unbind();
            jQuery('.dataTables_filter input').bind('keyup', function (e) {
                if (e.keyCode == 13) dtRef.search(this.value).draw();
            });
        },
        drawCallback : function (settings) {
            jQuery('input[name="id[]"]').on('click', function (e) {
                if (jQuery(this).is(':not(:checked)')) {
                    jQuery('#idChkboxAll').prop('checked', false);
                }
            });
        },
        lengthChange : true,
        searching : true,
        info : true,
        autoWidth : false,
        ordering : true,
        order : [1, 'DESC'],
        paging : true,
        lengthMenu : [[10, 50, 100], [10, 50, 100]],
        responsive : true,
        processing : true,
        serverSide : true,
        ajax : {
            url : '{{ route('pay.datatables', $lend->id) }}',
            type : 'POST',
            dataSrc : 'data',
            data : function (d) {
                d[csrfName] = getCsrfVal();
            },
            dataFilter : function (resp) {
                let respData = JSON.parse(resp);
                setCsrf(respData.csrfTokenName, respData.csrfTokenValue);

                jQuery(".dataTables_processing").hide();

                return resp;
            },
        },
        columnDefs : [
            {
                targets : 0,
                className : 'dt-center',
                title : '<input type="checkbox" id="idChkboxAll">',
                orderable : false,
                render : function (data, type, row, meta) {
                    return '<input type="checkbox" value="' + row.id + '" name="id[]" id="idChkbox' + row.id + '" onclick="" />';
                }
            },
            {
                targets : -1,
                title : 'Actions',
                className : 'dt-center',
                orderable : false,
                render : function (data, type, row, meta) {
                    let actionBtns = '';

                    actionBtns += '<a href="{{ route('pay.lend').'/'.$lend->id.'/' }}edit/' + row.id + '" class="bg-blue-500 text-white px-4 py-1 rounded-lg ml-3" title="Edit Data"><i class="fas fa-edit"></i> Edit</a>';
                    actionBtns += '<a href="javascript: confirmDelete(\'' + row.id + '\')" class="bg-red-500 text-white px-4 py-1 rounded-lg" title="Delete Data" style="margin: 10px;"><i class="fas fa-trash"></i> Delete</a>';
                    return actionBtns;
                }
            },
        ],
        columns : [
            {data : 'id'},
            {data : 'id', className : 'dt-center'},
            {data : 'created_at', className : 'dt-center'},
            {data : 'nominal', className : 'dt-center'},
            {data : 'note', className : 'dt-center'},
            {data : 'status', className : 'dt-center'},
            {data : 'id', className : 'dt-center'},
        ]
    });

    jQuery('#idChkboxAll').on('click', function (e) {
        if (jQuery(this).is(':checked')) {
            dtRef.$('input[type="checkbox"]').prop('checked', true);
        } else {
            dtRef.$('input[type="checkbox"]').prop('checked', false);
        }
    });

});


function setCsrf (name, value) 
{
    csrfName = name;
    csrfValue = value;
    localStorage.setItem('token', value);

    jQuery('#uploadToken').attr('name', csrfName);
    jQuery('#uploadToken').val(csrfValue);
}


function getCsrfVal () 
{
    let tmpCsrf = localStorage.getItem('token');

    if (tmpCsrf !== null) {
        csrfValue = tmpCsrf;
    }

    return csrfValue;
}


function confirmDelete (rowId) 
{
    Swal.fire({
        title: "Are you sure ?",
        text: "Once deleted, you will not be able to recover this data",
        icon: "warning",
        buttons: true,
        dangerMode: true,
        showCancelButton: true,

    }).then((result) => {
        if (result.isConfirmed) {
            deleteData(rowId);
        }
    });
}


function deleteData (rowId) 
{
    if (! Array.isArray(rowId)) rowId = [rowId];

    let postData = new Object();
    postData['id'] = rowId;
    postData[csrfName] = getCsrfVal();

    jQuery.ajax({
        url : '{{ route('pay.lend') }}/delete/' + rowId,
        method : 'POST',
        dataType : 'json',
        data : postData,
        dataFilter : function (resp, type) {
            let respData = JSON.parse(resp);

            setCsrf(respData.csrfTokenName, respData.csrfTokenValue);

            return resp;
        },
        success : function (d, sts, xhr) {
            if (d.type == 'success') {
                Swal.fire("Data deleted", {icon: "success",});
                reloadDatatables();
            } else if (d.type == 'error') {
                // console.log(d);
                Swal.fire("Response Error", {icon: "error", text:d.detail[0],});
            }
        },
    });
}


function reloadDatatables () 
{
    dtRef.ajax.reload();
    jQuery('#idChkboxAll').prop('checked', false);
}
</script>

@endsection