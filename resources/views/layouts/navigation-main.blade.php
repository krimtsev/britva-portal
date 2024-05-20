<!-- Sidebar -->
<div id="sidebar">
    <div class="inner">
		<div class="logo">
			@if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
				<img src="{{ asset('images/static/logo-white.png') }}" />
			@else
				<img src="{{ asset('images/static/logo.png') }}" />
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
						<li><a href="http://learn.mybritva.ru/" target="_blank">Перейти на портал обучения</a></li>
						<li><a href="http://pay.mybritva.ru/buy/kurs-administratorov-britva" target="_blank">Курс для администратора</a></li>
						<li><a href="http://pay.mybritva.ru/buy/kurs-dlya-barberov-britva" target="_blank">Курс для барбера</a></li>
						<li style="font-size: 10px; color: black;"><br>Если вам необходимо передать ссылку на курс сотруднику, нажмите на ссылку ПКМ (правая кнопка мыши) и "Копировать ссылку". А после отправьте своему сотруднику.</li>
					</ul>
				</li>
				<li>
					<span class="opener">Документация</span>
					<ul>
						<li><a href="https://disk.yandex.ru/d/y3CMh7Hi8A3pyA" target="_blank">Облако документов</a></li>
						<li><a href="/page/subscription">Абонементы</a></li>
						<li><a href="/page/certificate">Сертификаты</a></li>
						<li><a href="/page/rospotrebnadzor">Роспотребнадзор</a></li>
						<li><a href="/page/fines">Штрафы</a></li>
						<li><a href="/page/fines-audit">Штрафы по аудиту</a></li>
						<li><a href="page/mango-audit">Балльная система по манго-аудиту </a></li>
					</ul>
				</li>
				<li>
					<span class="opener">Инструкции</span>
					<ul>
						<li><a href="/page/mango-forwarding">Настройка переадресации Mango</a></li>
						<li><a href="/page/yclients-alerts">Всплывающие уведомления Yclients</a></li>
						<li><a target="_blank" href="https://www.youtube.com/playlist?list=PLhU6BmCA9pdCwapNa44e3BWdPe0EnvJJ2">Настройка Wahelp</a></li>
						<li><a target="_blank" href="https://youtu.be/6cy9sEyNDXU?si=3x0R0pmKIRHM08YO">Настройка измнения цен ЭВОТОР</a></li>
					</ul>
				</li>
				<li>
					<span class="opener">Дополнительные услуги</span>
					<ul>
						<li><a href="/upload/co-price.pdf" target="_blank">Платные услуги ЦО</a></li>
						<li><a href="https://mybritva.ru/page/service-bot">Сервис пропущенных звонков</a></li>
					</ul>
				</li>
				<li><a href="/sheet/find-certificate" >Поиск по сертификатам</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/1ka8_eTE18gQNr-LCCiKYKd_u0rG6dJ5R7oGFTQg6pdM/" target="_blank">Таблица маркет.сбора</a></li>
				<li><a href="https://docs.google.com/spreadsheets/d/1Y9zxXV-NZZvFHJNnESGBDiipCMNM1jjqQEzHqj8KRg0" target="_blank">Таблица оплаты телефонии</a></li>
				<li>
					<span class="opener">Макеты</span>
					<ul>
						<li><a href="https://disk.yandex.ru/d/_MsaQHBq6RLC4A" target="_blank">Макеты печатные</a></li>
						<li><a href="https://disk.yandex.ru/d/_G5bIHExwxfUlw" target="_blank">Макеты для инсты</a></li>
						<li><a href="https://disk.yandex.ru/d/gYdjTyCWu_bU3A" target="_blank">Макеты на НГ 2023-2024</a></li>
					</ul>
				</li>
				<li><a href="https://disk.yandex.ru/d/ODcgMSK8CtAh9A" target="_blank">Видео на ТВ</a></li>
				<li>
					<span class="opener">Заявки</span>
					<ul>
						<li><a href="https://forms.gle/vStrvEx5uw9NqicR6">Заявка на дизайн</a></li>
						<li><a href="https://docs.google.com/forms/d/1730Zt5Ect9gdMhOjjRvZBHc-cz3cG5WHzoPhp8Ep_24/viewform?edit_requested=true" target="_blank">Заявка на сертификат</a></li>
						<li><a href="https://docs.google.com/forms/d/e/1FAIpQLSdEPHKl637KqNBoeRJM70SG4RAa_Fkq7Kz24DEZe18yDtdTwQ/viewform?usp=sf_link" target="_blank">ЗАЯВКА НА ТОП-БАРБЕРА</a></li>
						<li><a href="https://docs.google.com/forms/d/e/1FAIpQLScvCdbQqAQ9XF6I1fftLZ0Fuc3F_Kqe-dfNFkpOuLhfv2A_hw/viewform" target="_blank">ЗАЯВКА НА БРЕНД-БАРБЕРА</a></li>
						<li><a href="https://docs.google.com/forms/d/1c5WIiAyJhyThcNNofdQpuwTz_LE_xaI5JiFws1ZvWDs/viewform?edit_requested=true#responses" target="_blank">ЗАЯВКА НА БРЕНД-БАРБЕРА+</a></li>
						<li><a href="https://docs.google.com/forms/d/e/1FAIpQLSerYr5kXeZs6XahEbxZMTdMLNJy71row8M3Bw1xx1jsTqr_bw/viewform" target="_blank">ЗАЯВКА НА ЭКСПЕРТА</a></li>
						<li><a href="https://docs.google.com/forms/d/e/1FAIpQLSdxA6Q4fpN85PEVsIrI3HCFu_-XLuVV2_XLBU3bXouA7SQeXg/viewform" target="_blank">ЗАЯВКА НА ЧЕРНЫЙ СПИСОК</a></li>
						<li><a href="https://docs.google.com/forms/d/1xlCBolWdb4HEmWJD-7vowf2GDpU_ywi1PW6cT1W6Pys/viewform" target="_blank">ЗАЯВКА НА ИНДИВИДУАЛЬНОЕ СОГЛАСОВАНИЕ</a></li>

					</ul>
				</li>
				<li><a href="/page/discounts">Корпоративные скидки</a></li>
				<li><a href="https://britva.tech/britva/" target="_blank">Страница для администраторов</a></li>
				<li>
					<span class="opener">Контакты</span>
					<ul>
						<li><a href="/page/contact-office">Сотрудников ЦО</a></li>
						<li><a href="/contact-franchise">Владельцев франшиз</a></li>
						<li><a href="/page/contact-partner">Партнеров</a></li>
						<!-- <li><a href="/sheet/contact-outstaff">Полезные контакты</a></li> -->
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

                <!-- @if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
                    <button onclick="toogleTheme()" class="button primary icon small solid fa-sun"> сменить тему </button>
                @else
                    <button onclick="toogleTheme()" class="button primary icon small solid fa-moon"> сменить тему </button>
                @endif -->

            </footer>

    </div>
</div>
