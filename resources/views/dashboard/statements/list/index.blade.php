<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        <div class="mb-2 flex justify-between">
            @if (Route::has('d.statements.create'))
                <a href="{{ route('d.statements.create') }}" class="button"> Добавить </a>
            @endif
            @if (Route::has('d.statements-categories.index'))
                <a href="{{ route('d.statements-categories.index') }}" class="button ml-2"> Отделы </a>
            @endif
        </div>

        <div>
            <form action="{{ route('d.statements.index') }}" method="get">
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
                                    {{ $value }}
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

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>#</th>
                    <th>Тема запроса</th>
                    <th>Отдел</th>
                    <th>Филиал</th>
                    <th>Статус</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($statements as $statement)
                    <tr>
                        <td> {{ $statement->id }}</td>
                        <td> <a href="{{ route('d.statements.edit', $statement->id) }}">{{ $statement->title }} </a></td>
                        <td> {{ $statement->category->title }}</td>
                        <td> {{ $statement->partner->name }}</td>
                        <td> {{ $statement->stateName() }}</td>
                        <td> {{ $statement->created_at }}</td>
                        <td>
                            @if (Route::has('d.statements.edit'))
                                <a href="{{ route('d.statements.edit', $statement->id) }}" class="button primary icon small solid fa-edit">Открыть</a>
                            @endif
                            @if (Route::has('d.statements.delete'))
                                <form action="{{ route('d.statements.delete', $statement->id) }}" method="post" class="inline-block ma-0">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="button primary icon small solid fa-trash"> Удалить </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $statements->links() }}
    </div>
</x-admin-layout>
