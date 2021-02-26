@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

        <div class="mb-6 text-right">
            <a href="{{ route('lend.add') }}" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold"><i class="fas fa-plus"></i> Add</a>
        </div>
        
        <div class="table-responsive">
            <table class="display dataTable" id="datatablesList">
                <thead>
                    <tr>
                        <th>Checkbox</th>
                        <th class="dt-head-center">ID</th>
                        <th class="dt-head-center">Created Date</th>
                        <th class="dt-head-center">Name</th>
                        <th class="dt-head-center">Nominal</th>
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
            url : '{{ route('lend.datatables') }}',
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

                    actionBtns += '<a href="{{ route('lend') }}/edit/' + row.id + '" class="bg-blue-500 text-white px-4 py-1 rounded-lg ml-3" title="Edit Data"><i class="fas fa-edit"></i> Edit</a>';
                    actionBtns += '<a href="javascript: confirmDelete(\'' + row.id + '\')" class="bg-red-500 text-white px-4 py-1 rounded-lg" title="Delete Data" style="margin: 10px;"><i class="fas fa-trash"></i> Delete</a>';

                    return actionBtns;
                }
            },
        ],
        columns : [
            {data : 'id'},
            {data : 'id', className : 'dt-center'},
            {data : 'created_at', className : 'dt-center'},
            {data : 'name', className : 'dt-center'},
            {data : 'nominal', className : 'dt-center'},
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

    jQuery('#deleteSelectedBtn').on('click', function (e) {
        let ids = [];
        let checked = dtRef.$('input[name="id[]"]:checked');

        for (let i = 0; i < checked.length; i++) {
            ids.push(checked[i].value);
        }

        if (checked.length > 0) {
            confirmDelete(ids);
        } else {
            swal("Info", "Please select data to delete!", "info");
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
        url : '{{ route('lend') }}/delete/' + rowId,
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