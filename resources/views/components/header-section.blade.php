<header id="header">
    <div class="logo">{{ $title }}</div>

    @if (Route::has('login'))
        <ul class="icons">
            <li>
				@if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
                    <a onclick="toogleTheme()" class="pointer icon solid fa-sun"></a>
                @else
                    <a onclick="toogleTheme()" class="pointer icon solid fa-moon"></a>
                @endif
			</li>

            <li>{{ Auth::user()->login }}</li>

            @auth
                @if(!Route::is('p.*') && false)
                    <li><a href="{{ route('p.home.index') }}" class="border-none">{{ __('Профиль') }}</a></li>
                @endif

                @if(!Route::is('d.*'))
                    @if (Auth::user()->isAdmin())
                        <li><a href="{{ route('d.home.index') }}" class="border-none">{{ __('Консоль') }}</a></li>
                    @endif
                @endif

                @if(Route::is('d.*') || Route::is('p.*'))
                    <li><a href="{{ route('post.index') }}" class="border-none">{{ __('Портал') }}</a></li>
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
