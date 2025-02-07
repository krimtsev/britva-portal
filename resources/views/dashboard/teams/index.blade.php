<x-admin-layout>
    <x-header-section title="Команды" />

    <section>
        <div class="mb-2 flex gap-2">
            <a href="{{ route('d.teams.create') }}" class="button"> Добавить </a>
            <a href="{{ route('d.teams.statistics') }}" class="button"> Статистика </a>
        </div>

        <div>
            <form action="{{ route('d.teams.index') }}" method="GET">
                <div class="row gtr-uniform">
                    <div class="col-3">
                        <h5>Филиалы</h5>
                        <select name="filter_partner" id="filter_partner">
                            <option value=""> Все </option>
                            @foreach($partners as $id => $name)
                                <option value="{{$id}}" {{ $filter['partner'] == $id ? 'selected' : '' }}> {{ $name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <h5> &emsp; </h5>
                        <input type="submit" value="Показать" class="primary" />
                    </div>
                </div>
            </form>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th> Id </th>
                    <th> Имя </th>
                    <th> Парнер </th>
                    <th> Градация </th>
                    <th> Дата создания </th>
                    <th> Действия </th>
                </tr>
                </thead>
                <tbody>
                @foreach ($teams as $team)
                    <tr>
                        <td> {{ $team->id }} </td>
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
