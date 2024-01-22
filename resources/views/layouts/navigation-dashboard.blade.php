<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <!-- Menu -->
        <nav id="menu">
            <div class="logo" style="padding: 0 40px;">
                <img src="{{ asset('images/static/logo.svg') }}" />
            </div>
            <!-- <header class="major">
                    <h2>Меню</h2>
                </header> -->
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
            </ul>
        </nav>
    </div>
</div>
