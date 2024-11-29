<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.svg') }}" />
            </div>
            <ul>
                <li><a href="{{ route('post.index') }}">← НАЗАД НА ГЛАВНУЮ САЙТА</a></li>
				@if (Route::has('p.analytics.index'))
                    <li><a href="{{ route('p.analytics.index') }}">АНАЛИТИКА ПО ФИЛИАЛУ</a></li>
                @endif
                <li><a href="{{ route('p.home.index') }}">ОБО МНЕ</a></li>
				<li><a href="{{ route('p.tickets.index') }}">МОИ ЗАЯВКИ</a></li>
                <li>
                    <span class="opener">ЗАПОЛНИТЬ ЗАЯВКУ</span>
                    <ul>
                        <li><a href="{{ route('p.tickets.create-template', 'maket') }}">ЗАЯВКА НА ДИЗАЙН</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'cert') }}">ЗАЯВКА НА СЕРТИФИКАТ</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'admin') }}">ЗАЯВКА НА АТТЕСТАЦИЮ АДМИНИСТРАТОРА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'tophair') }}">ЗАЯВКА НА ТОП-СТИЛИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'topbrow') }}">ЗАЯВКА НА ТОП-БРОВИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'topnail') }}">ЗАЯВКА НА ТОП-МАСТЕРА НОГТЕВОГО СЕРВИСА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandbrow') }}">ЗАЯВКА НА БРЕНД-БРОВИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandnail') }}">ЗАЯВКА НА БРЕНД-МАСТЕРА НОГТЕВОГО СЕРВИСА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'blacklist') }}">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('p.user.password.index') }}">СМЕНА ПАРОЛЯ</a></li>
            </ul>
        </nav>
    </div>
</div>
