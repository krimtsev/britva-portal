<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>
            <ul>
                <li><a href="..">← НАЗАД НА ГЛАВНУЮ САЙТА</a></li>
                <li><a href="{{ route('p.home.index') }}">ОБО МНЕ</a></li>
                <li><a href="{{ route('p.analytics.index') }}">АНАЛИТИКА ПО ФИЛИАЛУ</a></li>
				<li><a href="{{ route('p.tickets.index') }}">МОИ ЗАЯВКИ</a></li>
				<li>
					<span class="opener">ЗАПОЛНИТЬ ЗАЯВКУ</span>
					<ul>
						<li><a href="//mybritva.ru/profile/tickets/create/maket">ЗАЯВКА НА ДИЗАЙН</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/cert">ЗАЯВКА НА СЕРТИФИКАТ</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/top">ЗАЯВКА НА ТОП-БАРБЕРА</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/brand">ЗАЯВКА НА БРЕНД-БАРБЕРА</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/brandplus">ЗАЯВКА НА БРЕНД-БАРБЕРА+</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/expert">ЗАЯВКА НА ЭКСПЕРТА</a></li>
						<li><a href="//mybritva.ru/profile/tickets/create/blacklist">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
					</ul>
				</li>
                <li><a href="{{ route('p.user.password.index') }}">СМЕНА ПАРОЛЯ</a></li>
            </ul>
        </nav>
    </div>
</div>