<x-admin-layout>
    <x-header-section title="Статистика команды" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.teams.index') }}" class="button"> Список </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th> # </th>
                    <th> Имя </th>
                    <th> Всего </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($teams as $team)
                    <tr>
                        <td> {{ $loop->iteration }} </td>
                        <td> {{ $team->name }} </td>
                        <td> {{ $team->total ?? '-' }} </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>

</x-admin-layout>
