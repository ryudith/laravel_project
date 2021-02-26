@extends('backend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-8/12 bg-white p-6 rounded-lg">
        <h3 class="text-4xl mb-6 text-center">Edit Lend</h3>

        @if(session('error'))
        <div class="bg-red-500 p-4 rounded mb-6 text-white text-center">{{ session('error') }}</div>
        @endif

        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

        <form action="{{ route('lend.edit', $data->id) }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="is_member" class="text-2xl block mb-2 text-gray-500">Is Member<span class="text-md text-red-900">*</span> : </label>
            <select name="is_member" id="is_member" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('is_member') border-red-500 @enderror">
                <option value="0"@if(old('is_member', $data->is_member) == '0') selected @endif>No</option>
                <option value="1"@if(old('is_member', $data->is_member) == '1') selected @endif>Yes</option>
            </select>

            @error('is_member')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4" id="inputNameContainer">
            <label for="name" class="text-2xl block mb-2 text-gray-500">Name<span class="text-md text-red-900">*</span> : </label>
            <input type="text" name="name" id="name" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('name') border-red-500 @enderror" placeholder="Your name" value="{{ old('name', $data->name) }}" />

            @error('name')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4" id="inputUserContainer" style="display: none;">
            <label for="user" class="text-2xl block mb-2 text-gray-500">User<span class="text-md text-red-900">*</span> : </label>
            <select name="is_member" id="is_member" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('user') border-red-500 @enderror">
                @foreach($users as $user)
                <option value="{{ $user->id }}"@if(old('user', $data->user) == $user->id) selected @endif>{{ $user->name }}</option>
                @endforeach
            </select>

            @error('user')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="status" class="text-2xl block mb-2 text-gray-500">Status<span class="text-md text-red-900">*</span> : </label>
            <select name="status" id="status" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('status') border-red-500 @enderror">
                <option value="0"@if(old('status', $data->user) == '0') selected @endif>Disable</option>
                <option value="1"@if(old('status', $data->user) == '1' || ! old('status')) selected @endif>Unpaid</option>
                <option value="2"@if(old('status', $data->user) == '2') selected @endif>Paid</option>
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
            <label for="lend_file" class="text-2xl block mb-2 text-gray-500">File<span class="text-md text-red-900">*</span> : </label>
            <a target="_blank" href="{{ asset($data->lend_file) }}" class="text-blue-500 block mb-2">Open image</a>
            <input type="file" name="lend_file" id="lend_file" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('lend_file') border-red-500 @enderror" placeholder="Lend File" />

            @error('lend_file')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="description" class="text-2xl block mb-2 text-gray-500">Description<span class="text-md text-red-900">*</span> : </label>
            <textarea name="description" id="description" class="bg-gray-100 border-2 w-full p-4 rounded-lg mb-6 @error('description') border-red-500 @enderror" placeholder="Lend description">{{ old('description', $data->description) }}</textarea>

            @error('nominal')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4 grid grid-cols-2 gap-4 border-b-2 pb-3">
            <a class="bg-gray-100 text-black px-4 py-3 rounded-lg font-bold text-2xl w-full text-center" href="{{ route('lend') }}">Cancel</a>
            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold text-2xl w-full">Save</button>
        </div>

        </form>
    </div>
</div>


<script type="text/javascript">

jQuery(document).ready(function () {
    jQuery('#is_member').on('change', function (evt) {
        let val = jQuery('#is_member').val();
        let nameInput = jQuery('#inputNameContainer');
        let userInput = jQuery('#inputUserContainer');

        if (val == '1') {
            jQuery('#name').val('');

            nameInput.hide();
            userInput.show();
        } else {
            jQuery('#user').val('0');

            nameInput.show();
            userInput.hide();
        }
    });
});

</script>

@endsection