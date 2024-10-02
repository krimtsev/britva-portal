<x-admin-layout>
    <x-header-section title="Редактировать название файла" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.upload.edit', $upload->id) }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.upload-files.update', $file->id) }}" method="post">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Группа файлов</h5>
                        <input
                            type="text"
                            value="{{ $upload->name }}"
                            disabled
                        />
                    </div>

                    <div class="col-12">
                        <h5>Название файла</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ $file->title }}"
                            placeholder="Название файла"
                        />
                    </div>
                </div>
            </div>

            <div class="col-12">
                <input type="submit" value="Сохранить" class="primary">
            </div>
        </form>
    </section>
</x-admin-layout>
