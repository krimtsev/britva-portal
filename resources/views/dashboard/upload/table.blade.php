<x-admin-layout>
    <x-header-section title="Группы файлов" />

    <section>
        <div class="mb-2 flex justify-between gap-2">
            <a href="{{ route('d.upload-files.index') }}" class="button"> Список </a>
            <a href="{{ route('d.upload.create') }}" class="button"> Добавить </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Заголовок</th>
                        <th>Категория</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($uploads as $upload)
                    <tr>
                        <td> {{ $upload->id }}</td>
                        <td> {{ $upload->name }} </td>
                        <td>
                            @if (!$upload->category_id)
                                Без категории
                            @else
                                {{ $upload->category->name }}
                            @endif
                        </td>
                        <td> {{ $upload->created_at }}</td>
                        <td>
                            @if (Route::has('d.upload.edit'))
                            <a href="{{ route('d.upload.edit', $upload->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $uploads->links() }}
    </div>
</x-admin-layout>
