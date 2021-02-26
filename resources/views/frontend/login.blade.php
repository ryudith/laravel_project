@extends('frontend.app')

@section('content')

<div class="flex justify-center">
    <div class="w-4/12 bg-white p-6 rounded-lg">
        <h3 class="text-4xl mb-6 text-center">Login</h3>

        @if(session('error'))
        <div class="bg-red-500 p-4 rounded mb-6 text-white text-center">{{ session('error') }}</div>
        @endif

        @if(session('message'))
        <div class="bg-green-500 p-4 rounded mb-6 text-white text-center">{{ session('message') }}</div>
        @endif

        <form action="{{ route('login') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label for="email" class="sr-only">Email</label>
            <input type="email" name="email" id="email" class="bg-gray-100 border-2 w-full p-4 rounded-lg" placeholder="Your email" />
        </div>

        <div class="mb-4">
            <label for="password" class="sr-only">Password</label>
            <input type="password" name="password" id="password" class="bg-gray-100 border-2 w-full p-4 rounded-lg" placeholder="Your password" />
        </div>

        <div class="mb-4">
            <input type="checkbox" name="remember" id="remember" />
            <label for="remember">Remember me</label>
        </div>

        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-3 rounded-lg font-bold text-2xl w-full">Login</button>
        </div>

        </form>
    </div>
</div>

@endsection