<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>
            <ul>
                <li><a href="{{ route('post.index') }}">← НАЗАД НА ГЛАВНУЮ САЙТА</a></li>
                <li><a href="{{ route('p.home.index') }}">ОБО МНЕ</a></li>
                <li><a href="{{ route('p.analytics.index') }}">АНАЛИТИКА ПО ФИЛИАЛУ</a></li>
				<li><a href="{{ route('p.tickets.index') }}">МОИ ЗАЯВКИ</a></li>
                <li>
                    <span class="opener">ЗАПОЛНИТЬ ЗАЯВКУ</span>
                    <ul>
                        <li><a href="{{ route('p.tickets.create-template', 'maket') }}">ЗАЯВКА НА ДИЗАЙН</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'cert') }}">ЗАЯВКА НА СЕРТИФИКАТ</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'barberplus') }}">ЗАЯВКА НА БАРБЕРА+</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'top') }}">ЗАЯВКА НА ТОП-БАРБЕРА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brand') }}">ЗАЯВКА НА БРЕНД-БАРБЕРА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandplus') }}">ЗАЯВКА НА БРЕНД-БАРБЕРА+</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandpro') }}">ЗАЯВКА НА БРЕНД-БАРБЕРА ПРО</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'expert') }}">ЗАЯВКА НА ЭКСПЕРТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'blacklist') }}">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
                    </ul>
                </li>
                <li><a href="{{ route('p.user.password.index') }}">СМЕНА ПАРОЛЯ</a></li>
                @if(Route::has('p.teams.index') && $isBritvaPartner)
                    <li><a href="{{ route('p.teams.index') }}">Команда</a></li>
                @endif
            </ul>
        </nav>
    </div>
</div>
