<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        <div class="mb-2 flex justify-between">
            @if (Route::has('d.tickets.create'))
                <a href="{{ route('d.tickets.create') }}" class="button"> Добавить заявку  </a>
            @endif
            @if (Route::has('d.tickets-categories.index'))
                <a href="{{ route('d.tickets-categories.index') }}" class="button ml-2"> Отделы </a>
            @endif
        </div>

        <div>
            <form action="{{ route('d.tickets.index') }}" method="get">
                <div class="row gtr-uniform">
                    <div class="col-3">
                        <h5>Отделы</h5>
                        <select name="filter_category" id="filter_category">
                            <option value=""> Все </option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{ $filter['category'] == $category->id ? 'selected' : '' }}> {{ $category->title }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <h5>Филиалы</h5>
                        <select name="filter_partner" id="filter_partner">
                            <option value=""> Все </option>
                            @foreach($partners as $partner)
                                <option value="{{$partner->id}}" {{ $filter['partner'] == $partner->id ? 'selected' : '' }}> {{ $partner->name }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <h5>Статусы</h5>
                        <select name="filter_state" id="filter_state">
                            <option value=""> Все </option>
                            @foreach($stateList as $id => $value)
                                <option {{ $id == $filter['state'] ? 'selected' : '' }} value="{{ $id }}">
                                    {{ $value['title'] }}
                                </option>
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

        @if(!count($tickets))
            <x-data-empty description="Заявок на данный момент нет" />
        @else
            <div class="table-wrapper">
                <table>
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>Тема запроса</th>
                        <th>Отдел</th>
                        <th>Филиал</th>
                        <th>Статус</th>
                        <th>Посл. сообщение</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tickets as $ticket)
                        <tr>
                            <td> {{ $ticket->id }}</td>
                            <td> <a href="{{ route('d.tickets.edit', $ticket->id) }}">{{ $ticket->title }} </a></td>
                            <td> {{ $ticket->category->title }}</td>
                            <td> {{ $ticket->partner->name }}</td>
                            <td>
                                <span class="state {{ $stateList[$ticket->state]["key"] }}">{{ $ticket->stateName() }}</span>
                            </td>
                            <td
                                @if ($ticket->daysInWork == 0)
                                    style="color: rgb(71, 219, 4)"
                                @elseif ($ticket->daysInWork < 7)
                                    style="color: rgb(248, 139, 37)"
                                @elseif ($ticket->daysInWork > 7)
                                    style="color: rgb(219, 4, 68)"
                                @endif
                            >
                                {{ $ticket->dayName }}
                            </td>
                            <td> {{ $ticket->created_at }} </td>
                            <td>
                                @if (Route::has('d.tickets.edit'))
                                    <a href="{{ route('d.tickets.edit', $ticket->id) }}" class="button primary icon small solid fa-edit">Открыть</a>
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
