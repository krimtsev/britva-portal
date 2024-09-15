<x-admin-layout>
    <x-header-section title="Создать категорию файлов" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.upload-categories.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.upload-categories.store') }}" method="post">
            @csrf

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Название категории</h5>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            :value="old('name')"
                            placeholder="Категория"
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
