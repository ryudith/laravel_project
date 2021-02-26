@extends('frontend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-4/12 bg-white p-6 rounded-lg">
        <h3 class="text-4xl mb-6 text-center">Register</h3>

        @if(session('error'))
        <div class="bg-red-500 p-4 rounded mb-6 text-white text-center">{{ session('error') }}</div>
        @endif

        <form action="{{ route('register') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="name" class="sr-only">Name</label>
            <input type="text" name="name" id="name" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('name') border-red-500 @enderror" placeholder="Your name" value="{{ old('name') }}" />
            
            @error('name')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('email') border-red-500 @enderror" placeholder="Your email" value="{{ old('email') }}" />
            
            @error('email')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="bg-gray-100 border-2 w-full p-4 rounded-lg @error('password') border-red-500 @enderror" placeholder="Your password" />
            
            @error('password')
            <div class="text-red-500 mt-2 text-sm">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="sr-only">Password Confirmation</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="bg-gray-100 border-2 w-full p-4 rounded-lg" placeholder="Repeat your password" />
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold text-2xl w-full">Register</button>
        </div>

        </form>
    </div>
</div>

@endsection