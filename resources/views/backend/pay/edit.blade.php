@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        <h3 class="text-4xl mb-6 text-center">Edit Pay</h3>

        @if(session('error'))
        <div class="bg-red-500 p-4 rounded mb-6 text-white text-center">{{ session('error') }}</div>
        @endif

        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

        <form action="{{ route('pay.edit', [$lend->id, $data->id]) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="name" class="text-2xl block mb-2 text-gray-500">Name : </label>
            <input type="text" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6" value="{{ $lend->name ? $lend->name : '' }}" disabled />
        </div>

        <div class="mb-4">
            <label for="user" class="text-2xl block mb-2 text-gray-500">Left Lending : </label>
            <input type="text" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6" value="{{ $leftLending }}" disabled />
        </div>

        <div class="mb-4">
            <label for="status" class="text-2xl block mb-2 text-gray-500">Status<span class="text-md text-red-900">*</span> : </label>
            <select name="status" id="status" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('status') border-red-500 @enderror">
                <option value="0"@if(old('status', $data->status) == '0') selected @endif>Cancel</option>
                <option value="1"@if(old('status', $data->status) == '1' || ! old('status')) selected @endif>Accept</option>
            </select>

            @error('status')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="nominal" class="text-2xl block mb-2 text-gray-500">Nominal<span class="text-md text-red-900">*</span> : </label>
            <input type="number" name="nominal" id="nominal" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('nominal') border-red-500 @enderror" placeholder="Lend nominal" value="{{ old('nominal', $data->nominal) }}" />

            @error('nominal')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="pay_file" class="text-2xl block mb-2 text-gray-500">File : </label>
            @if($data->pay_file)
            <a target="_blank" href="{{ asset($data->pay_file) }}" class="text-blue-500 block mb-2">Open image</a>
            @endif
            <input type="file" name="pay_file" id="pay_file" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('pay_file') border-red-500 @enderror" placeholder="Pay File" />

            @error('pay_file')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="note" class="text-2xl block mb-2 text-gray-500">Note : </label>
            <textarea name="note" id="note" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('note') border-red-500 @enderror" placeholder="Pay note">{{ old('note', $data->note) }}</textarea>

            @error('note')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4 border-b-2 pb-3">
            <a class="bg-gray-100 text-black px-4 py-3 rounded-lg font-bold text-2xl w-full text-center" href="{{ route('pay', $lend->id) }}">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold text-2xl w-full">Save</button>
        </div>

        </form>
    </div>
</div>


<script type="text/javascript">

jQuery(document).ready(function () {
    
});

</script>

@endsection