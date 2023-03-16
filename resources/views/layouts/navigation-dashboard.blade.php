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
            <ul>
				<!-- <li><a href="{{ route('post.index') }}">Перейти на главную сайта</a></li> -->
                <li><a href="{{ route('d.home.index') }}">Панель администратора</a></li>
                <li><a href="{{ route('d.post.index') }}">Записи</a></li>
                <li><a href="{{ route('d.digest.index') }}">Блок дайджестов</a></li>
                <li><a href="{{ route('d.page.index') }}">Страницы</a></li>
                <li><a href="{{ route('d.sheet.index') }}">Интеграция с Google-документами</a></li>
                <li><a href="{{ route('d.user.index') }}">Пользователи</a></li>
            </ul>
        </nav>
    </div>
</div>
