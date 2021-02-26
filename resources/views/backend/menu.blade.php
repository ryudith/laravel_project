<nav class="bg-white flex justify-between p-6 mb-6">
    <ul class="flex item-center">
        <li>
            <a class="p-3" href="{{ route('dashboard') }}">Dashboard</a>
        </li>
        <li>
            <a class="p-3" href="{{ route('lend') }}">Lend</a>
        </li>
        <li>
            <a class="p-3" href="{{ route('pay.lend') }}">Pay</a>
        </li>
    </ul>

    <ul class="flex item-center">
        <li>
            <a class="p-3" href="{{ route('profile') }}">Profile</a>
        </li>
        <li>
            <form method="post" action="{{ route('logout') }}" class="px-3">
            @csrf
            <button type="submit">Logout</button>
            </form>
        </li>
    </ul>
</nav>