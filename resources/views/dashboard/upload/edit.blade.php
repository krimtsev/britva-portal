<x-admin-layout>
    <x-header-section title="Загрузить файлы" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.upload.index') }}" class="button">{{ __('Назад') }}</a>

            <form method="post" action="{{ route('d.upload.delete', $upload->id) }}">
                @method('delete')
                @csrf
                <button type="submit">Удалить</button>
            </form>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.upload.update', $upload->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Название</h5>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ $upload->name }}"
                            placeholder="Название"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Уникальная ссылка</h5>
                        <input
                            id="slug"
                            type="text"
                            name="slug"
                            value="{{ $upload->slug }}"
                            placeholder="Уникальная ссылка"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Категория</h5>
                        @if ($upload->category_id)
                            <input
                                type="text"
                                value="{{ $upload->category->name }}"
                                placeholder="Категория"
                                disabled
                            />
                        @else
                            <input
                                type="text"
                                value="Без категории"
                                placeholder="Категория"
                                disabled
                            />
                        @endif
                    </div>

                    <div class="col-12">
                        <h5>Загрузить файлы</h5>
                        <input
                            type="file"
                            name="files[]"
                            :value="old(files)"
                            placeholder="Файлы"
                            multiple="multiple"
                        />
                    </div>

                    @if(count($files))
                        <div class="col-12">
                            <h5>Загруженные файлы</h5>
                            <table>
                                <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Название</th>
                                    <th>Скачано</th>
                                    <th>Действия</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($files as $file)
                                    <tr>
                                        <td> {{ $file->id }} </td>
                                        <td>
                                            <a href="{{ $file->url }}">
                                                {{ $file->title }}.{{ $file->ext }}
                                            </a>
                                        </td>
                                        <td> {{ $file->downloads }}</td>
                                        <td>
                                            @if (Route::has('d.upload-files.edit'))
                                                <a href="{{ route('d.upload-files.edit', $file->id) }}" class="button primary icon small solid fa-edit button-icon-fix"></a>
                                            @endif
                                            @if (Route::has('d.upload-files.delete'))
                                                <a href="{{ route('d.upload-files.delete', $file->id) }}" class="button primary icon small solid fa-trash button-icon-fix"></a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <div class="col-12 flex justify-between">
                <input type="submit" value="Сохранить" class="primary">
            </div>
        </form>
    </section>
</x-admin-layout>
