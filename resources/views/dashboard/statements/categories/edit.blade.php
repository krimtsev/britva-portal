<x-admin-layout>
    <x-header-section title="Редактировать категорию" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.statements-categories.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.statements-categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">

                    <div class="col-12">
                        <h5>Название категории</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ $category->title }}"
                            placeholder=""
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
