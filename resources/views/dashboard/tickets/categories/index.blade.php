<x-admin-layout>
    <x-header-section title="Категории заявок" />

    <section>
        <div class="mb-2 flex justify-content-end">
            @if (Route::has('d.tickets-categories.create'))
                <a href="{{ route('d.tickets-categories.create') }}" class="button"> Добавить </a>
            @endif
            @if (Route::has('d.tickets.index'))
                <a href="{{ route('d.tickets.index') }}" class="button ml-2"> Заявки </a>
            @endif
        </div>

        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Заголовок</th>
                    <th>Дата создания</th>
                    <th>Действия</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td> {{ $category->id }}</td>
                        <td> {{ $category->title }} </td>
                        <td> {{ $category->created_at }}</td>
                        <td>
                            @if (Route::has('d.tickets-categories.edit'))
                                <a href="{{ route('d.tickets-categories.edit', $category->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('d.tickets-categories.delete'))
                                <form action="{{ route('d.tickets-categories.delete', $category->id) }}" method="post" class="inline-block ma-0">
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
        {{ $categories->links() }}
    </div>
</x-admin-layout>
