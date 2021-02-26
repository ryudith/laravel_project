<nav class="bg-white flex justify-between p-6 mb-6">
    <ul class="flex item-center">
        <li>
            <a class="p-3" href="{{ route('home') }}">Home</a>
        </li>
    </ul>

    <ul class="flex item-center">
    @auth
        <li>
            <a class="p-3" href="{{ route('dashboard') }}">App</a>
        </li>
        <li>
            <a class="p-3" href="{{ route('profile') }}">Profile</a>
        </li>
        <li>
            <form method="post" action="{{ route('logout') }}" class="px-3">
            @csrf
            <button type="submit">Logout</button>
            </form>
        </li>
    @endauth

    @guest
        <li>
            <a class="p-3" href="{{ route('register') }}">Register</a>
        </li>
        <li>
            <a class="p-3" href="{{ route('login') }}">Login</a>
        </li>
    @endguest
    </ul>
</nav>