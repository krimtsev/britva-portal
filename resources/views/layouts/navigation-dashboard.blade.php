<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>
            <!-- <header class="major">
                    <h2>Меню</h2>
                </header> -->
            @if(Auth::user()->isSysAdmin())
                <ul>
                    <li><a href="{{ route('d.home.index') }}">Панель администратора</a></li>
                    <li><a href="{{ route('d.post.index') }}">Записи</a></li>
                    <li><a href="{{ route('d.digest.index') }}">Блок дайджестов</a></li>
                    <li><a href="{{ route('d.page.index') }}">Страницы</a></li>
                    <li><a href="{{ route('d.sheet.index') }}">Интеграция с Google-документами</a></li>
                    <li><a href="{{ route('d.user.index') }}">Пользователи</a></li>
                    <li><a href="{{ route('d.analytics.index') }}">Аналитика</a></li>
                    <li><a href="{{ route('d.jobs.status-company') }}">Задачи</a></li>
                    <li><a href="{{ route('d.royalty.index') }}">Роялти</a></li>
                    <li><a href="{{ route('d.partner.index') }}">Партнеры</a></li>
                    <li><a href="{{ route('d.missed-calls.index') }}">Пропущенные звонки</a></li>
                    <li><a href="{{ route('d.upload.index') }}">Файлы</a></li>
                </ul>
            @elseif(Auth::user()->isAdmin())
                <ul>
                    <li><a href="{{ route('d.home.index') }}">Панель администратора</a></li>
                    <li><a href="{{ route('d.post.index') }}">Записи</a></li>
                    <li><a href="{{ route('d.page.index') }}">Страницы</a></li>
                    <li><a href="{{ route('d.analytics.index') }}">Аналитика</a></li>
                    <li><a href="{{ route('d.royalty.index') }}">Роялти</a></li>
                    <li><a href="{{ route('d.partner.index') }}">Партнеры</a></li>
                </ul>
            @endif
        </nav>
    </div>
</div>
