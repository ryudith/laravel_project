@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 flex-row">
        <div class="bg-white p-6 rounded-lg mb-6">
            <h3 class="text-4xl mb-6 text-center">Lend</h3>

            <div class="table-responsive">
                <table class="display dataTable" id="datatablesList">
                    <thead>
                        <tr>
                            <th class="dt-head-center">ID</th>
                            <th class="dt-head-center">Created Date</th>
                            <th class="dt-head-center">Name</th>
                            <th class="dt-head-center">Nominal</th>
                            <th class="dt-head-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($lends as $lend)
                        <tr>
                            <td class="dt-center">{{ $lend->id }}</td>
                            <td class="dt-center">{{ $lend->created_at }}</td>
                            <td>@if($lend->user > 0) {{ $lend->user()->name }} @else {{ $lend->name }} @endif</td>
                            <td>{{ $lend->nominal }}</td>
                            <td class="dt-center">@if($lend->status < 1) Disable @elseif($lend->status > 1) Paid @else Unpaid @endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 text-right">
                    <a href="{{ route('lend') }}" class="text-blue-500 font-bold">View all lend</a>
                </div>
            </div>
        </div>

        <div class="bg-white p-6 rounded-lg">
            <h3 class="text-4xl mb-6 text-center">Pay</h3>

            <div class="table-responsive">
                <table class="display dataTable" id="datatablesList">
                    <thead>
                        <tr>
                            <th class="dt-head-center">ID</th>
                            <th class="dt-head-center">Created Date</th>
                            <th class="dt-head-center">Nominal</th>
                            <th class="dt-head-center">Note</th>
                            <th class="dt-head-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pays as $pay)
                        <tr>
                            <td class="dt-center">{{ $pay->id }}</td>
                            <td class="dt-center">{{ $pay->created_at }}</td>
                            <td>{{ $pay->nominal }}</td>
                            <td>{{ $pay->note }}</td>
                            <td class="dt-center">@if($pay->status < 1) Cancel @else Accept @endif</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-3 text-right">
                    <a href="{{ route('pay.lend') }}" class="text-blue-500 font-bold">View all pay</a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection