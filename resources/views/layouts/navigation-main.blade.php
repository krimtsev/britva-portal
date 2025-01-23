<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
		<!-- Menu -->
		<nav id="menu">

            <div class="logo">
                <img src="{{ asset('images/static/logo.png') }}" />
            </div>

			<ul>
				<li><a href="{{ route('post.index') }}">Главная</a></li>
				<li>
					<span class="opener">Обучение</span>
					<ul>
						<li><a href="http://learn.mybritva.ru/" target="_blank">Перейти на портал обучения</a></li>
						<li><a href="https://britva-education.ru/" target="_blank">Курсы для сотрудников</a></li>
					</ul>
				</li>
				<li><a href="{{ route('upload.cloud') }}">Облако файлов</a></li>
				<li>
					<span class="opener">Документация</span>
					<ul>
						<li><a href="{{ route('page.index', 'subscription') }}">Система работы абонементов</a></li>
						<li><a href="{{ route('page.index', 'certificate') }}">Система работы сертификатов</a></li>
						<li><a href="{{ route('page.index', 'fines') }}">Штрафы</a></li>
						<li><a href="{{ route('page.index', 'fines-audit') }}">Штрафы по аудиту</a></li>
						<li><a href="{{ route('page.index', 'mango-audit') }}">Балльная система по манго-аудиту </a></li>
					</ul>
				</li>
				<li>
					<span class="opener">Инструкции</span>
					<ul>
						<li><a href="{{ route('page.index', 'mango-forwarding') }}">Настройка переадресации Mango</a></li>
						<li><a href="{{ route('page.index', 'yclients-alerts') }}">Всплывающие уведомления Yclients</a></li>
						<li><a target="_blank" href="https://youtube.com/playlist?list=PLhU6BmCA9pdCwapNa44e3BWdPe0EnvJJ2">Настройка Wahelp</a></li>
						<li><a target="_blank" href="https://youtu.be/6cy9sEyNDXU?si=3x0R0pmKIRHM08YO">Настройка измнения цен ЭВОТОР</a></li>
					</ul>
				</li>
				<li>
					<span class="opener">Дополнительные услуги</span>
					<ul>
						<li><a href="upload/co-price.pdf" target="_blank">Платные услуги ЦО</a></li>
						<li><a href="{{ route('page.index', 'subscription-yandex-2gis') }}" target="_blank">Подписка Яндекс.Карты и 2GIS</a></li>
						<li><a href="{{ route('page.index', 'service-bot') }}">Сервис пропущенных звонков</a></li>
						<li><a href="{{ route('p.analytics.index') }}">Сервис аналитики показателей</a></li>
					</ul>
				</li>
				<li><a href="{{ route('sheet.index', 'find-certificate') }}" >Поиск по сертификатам</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/1Y9zxXV-NZZvFHJNnESGBDiipCMNM1jjqQEzHqj8KRg0" target="_blank">Таблица оплаты телефонии</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/1ka8_eTE18gQNr-LCCiKYKd_u0rG6dJ5R7oGFTQg6pdM/" target="_blank">Таблица маркет.сбора</a></li>
				<li>
					<span class="opener">Видео на ТВ</span>
					<ul>
    					<li><a href="{{ route('upload.cloud', 'video') }}">Файлы для флешки</a></li>
						<li><a href="https://rutube.ru/channel/44286383/videos/" target="_blank">Смотреть на Rutube</a></li>
						<li><a href="https://vk.com/video/@britvabarber" target="_blank">Смотреть на VK Video</a></li>

					</ul>
				</li>
				<li>
					<span class="opener">Заявки</span>
					<ul>
						<li><a href="{{ route('p.tickets.create-template', 'maket') }}">ЗАЯВКА НА ДИЗАЙН</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'cert') }}">ЗАЯВКА НА СЕРТИФИКАТ</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'top') }}">ЗАЯВКА НА ТОП-БАРБЕРА</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'brand') }}">ЗАЯВКА НА БРЕНД-БАРБЕРА</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'brandplus') }}">ЗАЯВКА НА БРЕНД-БАРБЕРА+</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'expert') }}">ЗАЯВКА НА ЭКСПЕРТА</a></li>
						<li><a href="{{ route('p.tickets.create-template', 'blacklist') }}">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
						<li><a href="{{ route('p.tickets.create-template') }}">ЗАЯВКА НА ИНДИВИДУАЛЬНОЕ СОГЛАСОВАНИЕ</a></li>
						<li><a href="{{ route('p.tickets.create-template') }}">ПОДАТЬ ИДЕЮ</a></li>
					</ul>
				</li>
				<li><a href="https://docs.google.com/forms/d/e/1FAIpQLSeqcM5AKcoTWECLfA_kDPZEHGpUQv_iFIx1uCVtros447ubHQ/viewform">KPI франшизы (регионы)</a></li>
				<li><a href="{{ route('page.index', 'discounts') }}">Корпоративные скидки</a></li>
				<li><a href="https://britva.tech/britva/" target="_blank">Страница для администраторов</a></li>
				<li>
					<span class="opener">Контакты</span>
					<ul>
						<li><a href="{{ route('page.index', 'contact-office') }}">Сотрудников ЦО</a></li>
						<li><a href="{{ route('s.contact-franchise') }}">Владельцев франшиз</a></li>
						<li><a href="{{ route('page.index', 'contact-partner') }}">Партнеров</a></li>
                        <li><a href="{{ route('sheet.index', 'contact-outstaff') }}">Полезные контакты</a></li>
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
			<p>Больше информации на странице<br /><a href="{{ route('page.index', 'contact-office') }}">контакты сотрудников ЦО</a></p>
		</section>

        <!-- Footer -->
            <footer id="footer">
                <p class="copyright">{{ date("Y") }} | BRITVA</p>
            </footer>

    </div>
</div>
