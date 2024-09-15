<x-admin-layout>
    <x-header-section title="Список категорий файлов" />

    <section>
        <div class="mb-2 flex justify-content-end gap-2">
            <a href="{{ route('d.upload.index') }}" class="button">{{ __('Файлы') }}</a>
            <a href="{{ route('d.upload-categories.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Категория</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td> {{ $category->id }}</td>
                        <td> {{ $category->name }} </td>
                        <td> {{ $category->created_at }}</td>
                        <td>
                            @if (Route::has('d.upload-categories.edit'))
                            <a href="{{ route('d.upload-categories.edit', $category->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
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
