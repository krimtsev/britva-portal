<x-admin-layout>
    <x-header-section title="Создать категорию" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.tickets-categories.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.tickets-categories.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <div class="row gtr-uniform">

                    <div class="col-12">
                        <h5>Название категории</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            :value="old('title')"
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
