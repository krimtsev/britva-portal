<x-admin-layout>
    <x-header-section title="Панель администратора" />
    <section>
		<table class="table">
			<tr>
				<td>Всего записей</td>
				<td>{{ $counts->post }}</td>
			</tr>
			<tr>
				<td>Всего страниц</td>
				<td>{{ $counts->page }}</td>
			</tr>
			<tr>
				<td>Интеграций с Google-документами	</td>
				<td>{{ $counts->sheet }}</td>
			</tr>
			<tr>
				<td>Новостных дайджестов</td>
				<td>{{ $counts->digest }}</td>
			</tr>
			<tr>
				<td>Пользователей</td>
				<td>{{ $counts->user }}</td>
			</tr>
		</table>
    </section>
</x-admin-layout>
