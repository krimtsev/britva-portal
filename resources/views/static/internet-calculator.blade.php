<x-main-layout>
    <x-header-section title="Калькулятор интернета для филиала" />
    <section>
        <div class="">
			<form method="post">
				<label for="camera_count">Количество IP-камер:</label>
				<input type="number" id="camera_count" name="camera_count" value="0"><br>
				
				<label for="tv_count">Количество телевизоров работающих через Интернет:</label>
				<input type="number" id="tv_count" name="tv_count" value="0"><br>

				<label for="wifi_guests">Включить в расчеты WIFI для гостей?</label>
				<input type="checkbox" id="wifi_guests" name="wifi_guests" value="1" checked><br>

				<input type="submit" value="Рассчитать">
			</form>

			<?php
			if ($_SERVER["REQUEST_METHOD"] == "POST") {
				$camera_count = intval($_POST["camera_count"]);
				$tv_count = intval($_POST["tv_count"]);
				$include_wifi_guests = isset($_POST["wifi_guests"]) ? true : false;

				// Потребление трафика в мегабитах для каждого устройства
				$camera_traffic = $camera_count * 3;
				$tv_traffic = $tv_count * 6;

				// Фиксированные значения
				$phone_traffic = 1;
				$crm_traffic = 10;
				$wifi_traffic = $include_wifi_guests ? 30 : 0; // Учитываем WIFI для гостей только при включении

				// Общее потребление трафика
				$total_traffic = $camera_traffic + $tv_traffic + $phone_traffic + $crm_traffic + $wifi_traffic;

				echo "<h2>Результаты:</h2>";
				echo "<p>Телевизоры: {$tv_traffic} мбит</p>";
				echo "<p>Камеры: {$camera_traffic} мбит</p>";
				echo "<p>Телефонная трубка: {$phone_traffic} мбит</p>";
				echo "<p>Рабочий ноутбук администора (Yclients + онлайн-музыка): {$crm_traffic} мбит</p>";
				echo "<p>WIFI для гостей (6 гостей по 5 мбит на каждого): {$wifi_traffic} мбит</p>";
				echo "<p>Общее потребление трафика: {$total_traffic} мбит</p>";
			}
			?>
        </div>

    </section>
</x-main-layout>
