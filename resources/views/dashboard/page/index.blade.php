<x-admin-layout>
    <x-header-section title="Страницы" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.page.create') }}" class="button"> Добавить </a>
        </div>

        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Заголовок</th>
                        <th>Статус</th>
                        <th>Ссылка</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pages as $page)
                    <tr>
                        <td> {{ $page->id }}</td>
                        <td> <a href="{{ route('d.page.show', $page->id) }}"> {{ $page->title }} </a></td>
                        <td> {{ $page->status() }} </td>
                        <td> <a href="{{ route('page.index', $page->slug) }}" target="_blank"> {{ $page->slug }} </a></td>
                        <!-- <td> <a href="{{ $page->slug }}">{{ $page->slug }}</a> </td> -->
                        <td> {{ $page->created_at }}</td>
                        <td>
                            @if (Route::has('d.page.edit'))
                            <a href="{{ route('d.page.edit', $page->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('d.page.delete'))
                            <form action="{{ route('d.page.delete', $page->id) }}" method="page" class="inline-block ma-0">
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
        {{ $pages->links() }}
    </div>
</x-admin-layout>
