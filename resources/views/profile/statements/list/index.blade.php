<x-admin-layout>
    <x-header-section title="Список заявок" />

    <section>
        <div class="mb-2 flex justify-between">
            @if ($partnerId && Route::has('p.statements.create'))
                <a href="{{ route('p.statements.create') }}" class="button"> Добавить </a>
            @endif
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
                            @if (Route::has('p.statements.edit'))
                                <a href="{{ route('p.statements.edit', $statement->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('p.statements.delete'))
                                <form action="{{ route('p.statements.delete', $statement->id) }}" method="post" class="inline-block ma-0">
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
