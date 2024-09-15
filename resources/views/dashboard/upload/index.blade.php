<x-admin-layout>
    <x-header-section title="Список файлов" />

    <section>
        <div class="mb-2 flex justify-content-end gap-2">
            <a href="{{ route('d.upload-categories.index') }}" class="button">{{ __('Категории') }}</a>
            <a href="{{ route('d.upload.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
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
                        <td> {{ $upload->title }} </td>
                        <td> {{ $upload->category->name }} </td>
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
