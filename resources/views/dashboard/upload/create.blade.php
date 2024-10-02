<x-admin-layout>
    <x-header-section title="Загрузить файлы" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.upload.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.upload.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <div class="row gtr-uniform">

                    <div class="col-12">
                        <h5>Название</h5>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            :value="old('name')"
                            placeholder="Название"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Уникальная ссылка</h5>
                        <input
                            id="slug"
                            type="text"
                            name="slug"
                            :value="old('slug')"
                            placeholder="Уникальная ссылка"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Категория</h5>
                        <select id="category_id" name="category_id">
                            <option value=""> Без категории </option>
                            @foreach($categories as $category)
                                <option
                                    value="{{ $category->id }}"
                                    @if($category->id == old('category'))
                                        selected="selected"
                                    @endif
                                >
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
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
                </div>
            </div>

            <div class="col-12">
                <input type="submit" value="Сохранить" class="primary">
            </div>
        </form>
    </section>
</x-admin-layout>
