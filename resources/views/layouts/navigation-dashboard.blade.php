<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>

            @if(Auth::user()->isSysAdmin())
                <ul>
                    <li><a href="{{ route('d.home.index') }}">Панель администратора</a></li>
                    <li><a href="{{ route('d.post.index') }}">Записи</a></li>
                    <li><a href="{{ route('d.page.index') }}">Страницы</a></li>
					<li><a href="{{ route('d.upload.index') }}">Файлы</a></li>
                    <li><a href="{{ route('d.digest.index') }}">Блок дайджестов</a></li>
                    <li><a href="{{ route('d.user.index') }}">Пользователи</a></li>
                    <li><a href="{{ route('d.partner.index') }}">Партнеры</a></li>
					<li>
						<span class="opener">Сервис аналитики</span>
						<ul>
							<li><a href="{{ route('d.analytics.index') }}">Срез данных</a></li>
							<li><a href="{{ route('d.jobs.status-company') }}">Синхронизация</a></li>
						</ul>
					</li>
					<li>
						<span class="opener">Сервис пропущенных звонков</span>
						<ul>
							<li><a href="{{ route('d.missed-calls.index') }}">Биллинг</a></li>
							<li><a href="{{ route('d.blacklist.index') }}">Черный список</a></li>
						</ul>
					</li>
                    <li><a href="{{ route('d.royalty.index') }}">Роялти</a></li>
                    <li><a href="{{ route('d.sheet.index') }}">Интеграция с Google-документами</a></li>
                    @if(Route::has('d.audit.index'))
                        <li><a href="{{ route('d.audit.index') }}">Аудит</a></li>
                    @endif
                    @if(Route::has('d.tickets.index'))
                        <li><a href="{{ route('d.tickets.index') }}">Заявки</a></li>
                    @endif
                    @if(Route::has('d.teams.index'))
                        <li><a href="{{ route('d.teams.index') }}">Команда</a></li>
                    @endif
                </ul>
            @elseif(Auth::user()->isAdmin())
                <ul>
                    <li><a href="{{ route('d.home.index') }}">Панель администратора</a></li>
                    <li><a href="{{ route('d.post.index') }}">Записи</a></li>
                    <li><a href="{{ route('d.page.index') }}">Страницы</a></li>
                    <li><a href="{{ route('d.upload.index') }}">Файлы</a></li>
                    <li><a href="{{ route('d.analytics.index') }}">Аналитика</a></li>
                    <li><a href="{{ route('d.royalty.index') }}">Роялти</a></li>
                    <li><a href="{{ route('d.partner.index') }}">Партнеры</a></li>
                    @if(Route::has('d.tickets.index'))
                        <li><a href="{{ route('d.tickets.index') }}">Заявки</a></li>
                    @endif
                    @if(Route::has('d.teams.index'))
                        <li><a href="{{ route('d.teams.index') }}">Команда</a></li>
                    @endif
                </ul>
            @endif
        </nav>
    </div>
</div>
