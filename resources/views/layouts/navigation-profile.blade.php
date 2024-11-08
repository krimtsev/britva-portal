<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>
            <ul>
                <li><a href="{{ route('p.home.index') }}">Профиль</a></li>
                <li><a href="{{ route('p.statements.index') }}">Заявки</a></li>
                <li><a href="{{ route('p.analytics.index') }}">Аналитика</a></li>
                <li><a href="{{ route('p.user.password.index') }}">Смена пароля</a></li>
            </ul>
        </nav>
    </div>
</div>
