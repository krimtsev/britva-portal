<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo" style="padding: 0 40px;">
                <img src="{{ asset('images/static/logo.svg') }}" />
            </div>
            <ul>
                <li><a href="{{ route('p.home.index') }}">Профиль</a></li>
                <!-- <li><a href="{{ route('p.analytics.index') }}">Аналитика</a></li> -->
                <li><a href="{{ route('p.user.password.index') }}">Смена пароля</a></li>
                @if (Auth::user()->isAdmin() || Auth::user()->isSysAdmin())
                    <li><a href="{{ route('p.partners.index') }}">Партнеры</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
