<x-admin-layout>
    <x-header-section title="Создать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.statements.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.statements.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Название</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            :value="old('name')"
                            placeholder="Название"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Категория заявления</h5>
                        <select name="category_id" id="category_id">
                            <option value=""> Выберите категорию заявления </option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->title }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-12">
                        <h5>Партнер</h5>

                        @if(count($partners) == 1)
                            <select name="partner_id" id="partner_id" disabled>
                                <option selected value="{{ $partners[0]->id }}"> {{ $partners[0]->name }} </option>
                            </select>
                        @else
                            <div> Для вашего профиля партнер не указан, обратитесь к администратору</div>
                        @endif
                    </div>

                    <div class="col-12">
                        <h5>Сообщение</h5>
                        <textarea name="text" id="text" rows="5">{{ old('text') }}</textarea>
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
                <input type="submit" value="Отправить" class="primary">
            </div>
        </form>
    </section>
</x-admin-layout>
