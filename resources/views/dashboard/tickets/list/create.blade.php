<x-admin-layout>
    <x-header-section title="Создать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.tickets.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.tickets.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Тема запроса</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            :value="old('title')"
                            placeholder="Тема запроса"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Отдел</h5>
                        <select name="category_id" id="category_id">
                            <option value=""> - </option>

                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->title }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="col-12">
                        <h5>Филиал</h5>

                        @if(count($partners) == 1)
                            <select name="partner_id" id="partner_id" disabled>
                                <option selected value="{{ $partners[0]->id }}"> {{ $partners[0]->name }} </option>
                            </select>
                        @else
                            <select name="partner_id" id="partner_id">
                                <option value="" selected> - </option>

                                @foreach($partners as $partner)
                                    <option value="{{ $partner->id }}">
                                        {{ $partner->name }}
                                    </option>
                                @endforeach
                            </select>
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
