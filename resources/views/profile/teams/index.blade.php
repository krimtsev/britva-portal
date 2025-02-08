<x-admin-layout>
    <x-header-section title="Команды" />

    <section>
        @if($isAllowedEditInProfile)
            <div class="mb-2 flex justify-between">
                <a href="{{ route('p.teams.create') }}" class="button"> Добавить </a>
            </div>
        @endif

        @if(!$isAllowedEditInProfile)
            <x-data-empty description="Не указан индентификатор филиала" />
        @elseif(!count($teams))
            <x-data-empty description="Сотрудников на данный момент нет" />
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th> # </th>
                        <th> Имя </th>
                        <th> Парнер </th>
                        <th> Градация </th>
                        <th> Действия </th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(isset($teams))
                        @foreach ($teams as $team)
                            <tr>
                                <td> {{ $loop->iteration }} </td>
                                <td> {{ $team->name }} </td>
                                <td> {{ $partners[$team->partner_id] }} </td>
                                <td> {{ $team->role() }} </td>
                                <td>
                                    @if (Route::has('p.teams.edit'))
                                        <a href="{{ route('p.teams.edit', $team->id) }}" class="button primary icon small solid fa-edit button-icon-fix"></a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>
            </div>
        @endif
    </section>

    @if(isset($teams))
        <div class="align-center">
            {{ $teams->links() }}
        </div>
    @endif
</x-admin-layout>
