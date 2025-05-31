<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
		<!-- Menu -->
		<nav id="menu">

            <div class="logo" style="padding: 0 40px;">
                <img src="{{ asset('images/static/logo.svg') }}" />
            </div>

            <ul>
                <li><a href="{{ route('post.index') }}">Главная</a></li>
                <li>
                    <span class="opener">Обучение</span>
                    <ul>
                        <li><a href="https://mysoda.ru/page/start" target="_blank">С чего начать?</a></li>
                        <li><a href="http://learn.mybritva.ru/" target="_blank">Перейти на портал обучения</a></li>
                        <li><a href="http://britva.kassa.bizon365.ru/buy/kurs-dlya-administratorov-soda" target="_blank">Курс для администраторов</a></li>
                        <li><a href="http://britva.kassa.bizon365.ru/buy/kurs-mastera-nogtevogo-servisa" target="_blank">Курс для мастеров ногтевого сервиса</a></li>
                        <li><a href="http://britva.kassa.bizon365.ru/buy/vvodnaya-informaciya-dlya-stilistov-soda" target="_blank">Курс для стилистов</a></li>
						<li style="font-size: 10px; color: black;"><br>Если вам необходимо передать ссылку на курс сотруднику, нажмите на ссылку ПКМ (правая кнопка мыши) и "Копировать ссылку". А после отправьте своему сотруднику.</li>
                    </ul>
                </li>
				<li><a href="/cloud/">Облако файлов</a></li>
                <li>
                    <span class="opener">Документация</span>
                    <ul>

                        <!-- <li><a href="/page/price-list">Прайс-лист</a></li> -->
                        <li><a href="/page/certificate">Система работы сертификатов</a></li>
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
                <li>
					<span class="opener">Дополнительные услуги</span>
					<ul>
						<li><a href="/upload/co-price.pdf" target="_blank">Платные услуги ЦО</a></li>
						<li><a href="page/subscription-yandex-2gis">Подписка Яндекс.Карты и 2GIS</a></li>
						<li><a href="https://mysoda.ru/page/service-bot">Сервис пропущенных звонков</a></li>
					</ul>
				</li>
                <li><a href="/sheet/find-certificate" >Поиск по сертификатам</a></li>
                <li><a href="https://docs.google.com/spreadsheets/d/1EllHBxOGbK61fOl7rMqenM7oCF59e3_30F_JA3xTAw8/" target="_blank">Таблица оплаты телефонии</a></li>
                <li>
                    <span class="opener">Заявки</span>
                    <ul>
						<li><a href="{{ route('p.tickets.create-template', 'maket') }}">ЗАЯВКА НА ДИЗАЙН</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'cert') }}">ЗАЯВКА НА СЕРТИФИКАТ</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'admin') }}">ЗАЯВКА НА АТТЕСТАЦИЮ АДМИНИСТРАТОРА</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'dlyasebya') }}">ЗАЯВКА "МАКИЯЖ ДЛЯ СЕБЯ"</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'hairquestion') }}">ЗАЯВКА НА ОТКРЫТИЕ УСЛУГИ (СТИЛИСТЫ)</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'tophair') }}">ЗАЯВКА НА ТОП-СТИЛИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'topbrow') }}">ЗАЯВКА НА ТОП-БРОВИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'topnail') }}">ЗАЯВКА НА ТОП-МАСТЕРА НОГТЕВОГО СЕРВИСА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandbrow') }}">ЗАЯВКА НА БРЕНД-БРОВИСТА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'brandnail') }}">ЗАЯВКА НА БРЕНД-МАСТЕРА НОГТЕВОГО СЕРВИСА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'podolog') }}">ЗАЯВКА НА ПОДОЛОГА</a></li>
                        <li><a href="{{ route('p.tickets.create-template', 'blacklist') }}">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
                        <li><a href="{{ route('p.tickets.create') }}">ЗАЯВКА НА ИНДИВИДУАЛЬНОЕ СОГЛАСОВАНИЕ</a></li>
                        <li><a href="{{ route('p.tickets.create') }}">ПОДАТЬ ИДЕЮ</a></li>
                    </ul>
                </li>
				<li><a href="https://docs.google.com/forms/d/19-nRP02iMz7gbtViZ599YYZY9lK5eWTdE-mBcqVrRA0/viewform?edit_requested=true">KPI франшизы (регионы)</a></li>
                <li><a href="/page/discounts">Корпоративные скидки</a></li>
                <li><a target="_blank" href="https://britva.tech/soda/">Страница для администраторов</a></li>
                <li>
                    <span class="opener">Контакты</span>
                    <ul>
                        <li><a href="/page/contact-office">Сотрудников ЦО</a></li>
                        <li><a href="/contact-franchise">Владельцев франшиз</a></li>
                        <li><a href="/page/contact-partner">Партнеров</a></li>
                        <li><a href="/page/contacts-brands-nail">Торговые марки (ногтевой сервис)</a></li>
                        <li><a href="/page/contacts-brands-hair">Торговые марки (стилисты)</a></li>
                        <li><a href="/page/contacts-brands-brow">Торговые марки (бровисты)</a></li>
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
