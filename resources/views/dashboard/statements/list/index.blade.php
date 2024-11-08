<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        <div class="mb-2 flex justify-between">
            @if (Route::has('d.statements.create'))
                <a href="{{ route('d.statements.create') }}" class="button"> Добавить </a>
            @endif
            @if (Route::has('d.statements-categories.index'))
                <a href="{{ route('d.statements-categories.index') }}" class="button ml-2"> Категории </a>
            @endif
        </div>

        <div>
            <form action="{{ route('d.statements.index') }}" method="get">
                <div class="row gtr-uniform">
                    <div class="col-3">
                        <h5>Категории</h5>
                        <select name="filter_category" id="filter_category">
                            <option value=""> Все </option>
                            @foreach($categories as $category)
                                <option value="{{$category->id}}" {{ $filter['category'] == $category->id ? 'selected' : '' }}> {{ $category->title }} </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-3">
                        <h5>Партнеры</h5>
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
                            <option value="1" {{ $filter['state'] == 1 ? 'selected' : '' }}> Выполняется </option>
                            <option value="2" {{ $filter['state'] == 2 ? 'selected' : '' }}> Готово </option>
                        </select>
                    </div>

                    <div class="col-3">
                        <h5> &emsp; </h5>
                        <input type="submit" value="Фильтр" class="primary" />
                    </div>
                </div>
            </form>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Заголовок</th>
                    <th>Категория</th>
                    <th>Партнер</th>
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
                        <td> {{ $statement->partner->name }}</td>
                        <td> {{ $statement->stateName() }}</td>
                        <td>
                            @if (Route::has('d.statements.edit'))
                                <a href="{{ route('d.statements.edit', $statement->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
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
