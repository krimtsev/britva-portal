<x-admin-layout>
    <x-header-section title="Команды" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.teams.create') }}" class="button"> Добавить </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th> Id </th>
                    <th> Имя </th>
                    <th> Парнер </th>
                    <th> Роль </th>
                    <th> Дата создания </th>
                    <th> Действия </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($teams as $team)
                    <tr>
                        <td> {{ $team->id }} </td>
                        <td style="position: relative">
                            <img
                                id="image"
                                src="{{
                                    $team->photo
                                        ? asset('storage/' . $team->photo)
                                        : asset('assets/teams/default.jpeg')
                                 }}"
                                alt="photo"
                                style="max-height: 50px; position: absolute; bottom: 5px;"
                            />
                        </td>
                        <td> {{ $team->name }} </td>
                        <td> {{ $partners[$team->partner_id] }} </td>
                        <td> {{ $team->role() }} </td>
                        <td> {{ $team->created_at }} </td>
                        <td>
                            @if (Route::has('d.teams.edit'))
                                <a href="{{ route('d.teams.edit', $team->id) }}" class="button primary icon small solid fa-edit button-icon-fix"></a>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $teams->links() }}
    </div>
</x-admin-layout>
