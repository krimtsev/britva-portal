<header id="header">
    <div class="logo">{{ $title }}</div>

    @if (Route::has('login'))
        <ul class="icons">
            <li>{{ Auth::user()->login }}</li>

            @auth
                @if(Route::is('d.*'))
                    <li><a href="{{ route('post.index') }}" class="border-none">{{ __('Портал') }}</a></li>
                @else
                    @if (Auth::user()->isAdmin())
                        <li><a href="{{ route('d.home.index') }}" class="border-none">{{ __('Панель администратора') }}</a></li>
                    @endif
                @endif
            @endauth

            <li>
                <form method="POST" action="{{ route('logout') }}" class="ma-0">
                    @csrf
                    <a :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();" class="pointer border-none">
                        {{ __('Выйти') }}
                    </a>
                </form>
            </li>
        </ul>
    @endif
</header>
