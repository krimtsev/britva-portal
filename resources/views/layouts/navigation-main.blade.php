<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
        <div class="logo" style="padding: 0 40px;">
			@if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
				<img src="{{ asset('images/static/logo.svg') }}" />
			@else
				<img src="{{ asset('images/static/logo.svg') }}" />
			@endif

		</div>
		<!-- Menu -->
		<nav id="menu">

			<!-- <header class="major">
				<h2>Меню</h2>
			</header> -->

            <ul>
                <li><a href="{{ route('post.index') }}">Главная</a></li>
                <li>
                    <span class="opener">Обучение</span>
                    <ul>
                        <li><a href="https://mysoda.ru/page/start" target="_blank">С чего начать?</a></li>
                        <li><a href="http://learn.mybritva.ru/" target="_blank">Перейти на портал обучения</a></li>
                        <li><a href="http://britva.kassa.bizon365.ru/buy/kurs-mastera-nogtevogo-servisa" target="_blank">Курс мастера ногтевого сервиса</a></li>
                        <li><a href="http://britva.kassa.bizon365.ru/buy/vvodnaya-informaciya-dlya-stilistov-soda" target="_blank">Курс для стилистов</a></li>
						<li style="font-size: 10px; color: black;"><br>Если вам необходимо передать ссылку на курс сотруднику, нажмите на ссылку ПКМ (правая кнопка мыши) и "Копировать ссылку". А после отправьте своему сотруднику.</li>
                    </ul>
                </li>
                <li>
                    <span class="opener">Документация</span>
                    <ul>
                        <li><a href="https://disk.yandex.ru/d/ULIeIC7SnSRLGA" target="_blank">Облако документов</a></li>
                        <!-- <li><a href="/page/subscription">Абонементы</a></li>
                        <li><a href="/page/certificate">Сертификаты</a></li>
                        <li><a href="/page/price-list">Прайс-лист</a></li>
                        <li><a href="/page/rospotrebnadzor">Роспотребнадзор</a></li> -->
                        <li><a href="/page/fines-audit">Штрафы по аудиту</a></li>
                        <li><a href="/page/fines">Штрафы</a></li>
                        <li><a href="/page/mango-audit">Балльная система по манго-аудиту</a></li>
                    </ul>
                </li>
                <li>
                    <span class="opener">Инструкции</span>
                    <ul>
                        <li><a href="/page/mango-forwarding">Настройка переадресации Mango</a></li>
                        <li><a href="/page/yclients-alerts">Всплывающие уведомления Yclients</a></li>
                        <li><a target="_blank" href="https://www.youtube.com/playlist?list=PLhU6BmCA9pdCwapNa44e3BWdPe0EnvJJ2">Настройка Wahelp</a></li>
                        <li><a target="_blank" href="https://youtu.be/6cy9sEyNDXU?si=3x0R0pmKIRHM08YO">ЭВОТОР. Настройка цен</a></li>
                    </ul>
                </li>
                <li><a href="https://mysoda.ru/page/service-bot">Сервис пропущенных звонков</a></li>
                <li><a href="/sheet/find-certificate" >Поиск по сертификатам</a></li>
                <li><a href="https://docs.google.com/spreadsheets/d/1EllHBxOGbK61fOl7rMqenM7oCF59e3_30F_JA3xTAw8/" target="_blank">Таблица оплаты телефонии</a></li>
                <li>
                    <span class="opener">Макеты</span>
                    <ul>
                        <!-- <li><a href="https://disk.yandex.ru/d/I_GTnZygB632BA" target="_blank">Макеты печатные</a></li> -->
                        <li><a href="https://disk.yandex.ru/d/QlK_B4ArlUdxHQ" target="_blank">Макеты печатные</a></li>
                        <li><a href="https://disk.yandex.ru/d/gYdjTyCWu_bU3A" target="_blank">Макеты на НГ 2023-2024</a></li>
                    </ul>
                </li>
                <li><a href="/upload/co-price.pdf" target="_blank">ПЛАТНЫЕ УСЛУГИ ЦО</a></li>
                <li>
                    <span class="opener">Заявки</span>
                    <ul>
                        <li><a href="https://forms.gle/vStrvEx5uw9NqicR6">Заявка на дизайн</a></li>
                        <li><a target="_blank" href="https://docs.google.com/forms/d/e/1FAIpQLSey7nWdiT1diYpgpMYapsWLxFlppWFnAUjGnq8cyroQOjctQA/viewform">ЗАЯВКА ПО СЕРТИФИКАТУ</a></li>
                        <li><a target="_blank" href="https://docs.google.com/forms/d/1VVrRX_w9_V7h6_CXPRjt39l-LmAHVPgvAqDOIJTzx8c/viewform?edit_requested=true">ЗАЯВКА НА ТОП-СТИЛИСТА</a></li>
                        <li><a href="https://docs.google.com/forms/d/1SlR0SpPsqwQ2bRozL83KeDiWY7eq_aLeHx2M-L-gjek/edit">ЗАЯВКА НА ТОП-БРОВИСТА</a></li>
                        <li><a target="_blank" href="https://docs.google.com/forms/d/1mgmKAS_qtQZ1fSpsRD2IJ63q07Bmbw1VWqgt3zRCol0/viewform?edit_requested=true">ЗАЯВКА НА ТОП-МАСТЕРА МАНИКЮРА</a></li>
                        <li><a href="https://docs.google.com/forms/d/1WJJW7ihNhNz-xMG2rhoQ42zutwcAn-Wh0wGEe7anCb8/edit">ЗАЯВКА НА БРЕНД-МАСТЕРА МАНИКЮРА</a></li>
                        <li><a href="https://docs.google.com/forms/d/1d3nUJEG1bDynQMPm4EeKOk-gkLVxJyuN1VX3su9G6VE/viewform" target="_blank">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
                        <li><a href="https://docs.google.com/forms/d/1d7uw_rRJ3Ofw8GY5vg0yobMpYlvugO-KO6k9flBKYZE/viewform" target="_blank">ЗАЯВКА НА ИНДИВИДУАЛЬНОЕ СОГЛАСОВАНИЕ</a></li>
                    </ul>
                </li>
                <li><a href="/page/discounts">Корпоративные скидки</a></li>
                <li><a target="_blank" href="https://britva.tech/soda/">Страница для администраторов</a></li>
                <li>
                    <span class="opener">Контакты</span>
                    <ul>
                        <li><a href="/page/contact-office">Сотрудников ЦО</a></li>
                        <li><a href="/contact-franchise">Владельцев франшиз</a></li>
                        <li><a href="/page/contact-partner">Партнеров</a></li>
                        <li><a href="/sheet/contact-outstaff">Полезные контакты</a></li>
                    </ul>
                </li>
            </ul>
		</nav>

        <!-- Section -->
            @if($digests)
			<section>
                <header class="major">
                    <h2>Последние дайджесты:</h2>
                </header>
                <div class="mini-posts">
                    @foreach ($digests as $digest)
                    <article>
                        <h4> {{ $digest->title }} </h4>
                        <p> {!! $digest->description !!} </p>
                    </article>
                    @endforeach
                </div>
            </section>
            @endif
		<!-- Section -->

		<section>
			<header class="major">
				<h2>График работы ЦО:</h2>
			</header>
			<p>C понедельника по пятницу<br />C 10:00 до 20:00</p>
			<p>Больше информации на странице<br /><a href="/page/contact-office">контакты сотрудников ЦО</a></p>
			<!-- <p>Телефон офиса:<br /><a href="tel:+74994440270">+7 (499) 444-02-70</a></p> -->

		</section>

        <!-- Footer -->
        <footer id="footer">
            <p class="copyright">{{ date("Y") }} | BRITVA</p>
        </footer>

    </div>
</div>
