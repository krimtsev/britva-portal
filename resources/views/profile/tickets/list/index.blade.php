<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        @if ($isAllowedEditInProfile && Route::has('p.tickets.create'))
            <div class="mb-2 flex justify-between">
                <a href="{{ route('p.tickets.create') }}" class="button"> Создать заявку </a>
            </div>
        @endif

        @if(!$isAllowedEditInProfile)
            <x-data-empty description="Не указан индентификатор филиала" />
        @elseif(!count($tickets))
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
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td> {{ $ticket->id }}</td>
                            <td> <a href="{{ route('p.tickets.edit', $ticket->id) }}">{{ $ticket->title }} </a></td>
                            <td> {{ $ticket->category->title }}</td>
                            <td> {{ $ticket->stateName() }}</td>
                            <td> {{ $ticket->created_at }} </td>
                            <td>
                                @if (Route::has('p.tickets.edit'))
                                    <a href="{{ route('p.tickets.edit', $ticket->id) }}" class="button primary icon small solid fa-edit">Открыть</a>
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
        {{ $tickets->links() }}
    </div>
</x-admin-layout>
