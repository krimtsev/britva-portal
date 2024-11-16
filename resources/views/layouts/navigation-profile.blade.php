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
                <li><a href="{{ route('p.tickets.index') }}">Заявки</a></li>
                @if (Route::has('p.analytics.index'))
                    <li><a href="{{ route('p.analytics.index') }}">Аналитика</a></li>
                @endif
                <li><a href="{{ route('p.user.password.index') }}">Смена пароля</a></li>
            </ul>
        </nav>
    </div>
</div>
