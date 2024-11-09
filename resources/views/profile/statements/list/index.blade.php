<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        @if ($partnerId && Route::has('p.statements.create'))
            <div class="mb-2 flex justify-between">
                <a href="{{ route('p.statements.create') }}" class="button"> Создать заявку </a>
            </div>
        @endif

        @if(!$partnerId)
            <x-data-empty description="Не указан индентификатор филиала" />
        @elseif(!count($statements))
            <x-data-empty description="Заявок на данный момент нет" />
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Тема запроса</th>
                        <th>Отдел</th>
                        <th>Статус</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($statements as $statement)
                        <tr>
                            <td> {{ $statement->id }}</td>
                            <td> {{ $statement->title }} </td>
                            <td> {{ $statement->category->title }}</td>
                            <td> {{ $statement->stateName() }}</td>
                            <td>
                                @if (Route::has('p.statements.edit'))
                                    <a href="{{ route('p.statements.edit', $statement->id) }}" class="button primary icon small solid fa-edit">Открыть</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
    <div class="align-center">
        {{ $statements->links() }}
    </div>
</x-admin-layout>
