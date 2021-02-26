@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

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
            url : '{{ route('pay.lend.datatables') }}',
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

                    if (row.status != 'Paid') {
                        actionBtns += '<a href="{{ route('pay.lend') }}/' + row.id + '" class="bg-blue-500 text-white px-4 py-1 rounded-lg ml-3" title="Edit Data"><i class="fas fa-money-bill-wave"></i> Pay</a>';
                    }
                    
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
            console.log('check all');
            dtRef.$('input[type="checkbox"]').prop('checked', true);
        } else {
            console.log('uncheck all');
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


function reloadDatatables () 
{
    dtRef.ajax.reload();
    jQuery('#idChkboxAll').prop('checked', false);
}
</script>

@endsection