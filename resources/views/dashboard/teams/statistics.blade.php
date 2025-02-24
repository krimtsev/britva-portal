<x-admin-layout>
    <x-header-section title="Статистика команды" />

    <section>
        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th style="width: 3%;"> # </th>
                    <th style="width: 22%;"> Имя </th>
                    <th style="width: 75%;"> Всего </th>
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
