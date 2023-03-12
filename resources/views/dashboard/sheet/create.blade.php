<x-dashboard-layout>
    <x-header-section title="Добавить Google Sheet" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.sheet.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.sheet.store') }}" method="post">
            @csrf
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" :value="old('title')" placeholder="Заголовок">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_id" id="spreadsheet_id" :value="old('spreadsheet_id')" placeholder="ID таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_name" id="spreadsheet_name" :value="old('spreadsheet_name')" placeholder="Название таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_range" id="spreadsheet_range" :value="old('spreadsheet_range')" placeholder="Диапазон таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="table_header" id="table_header" :value="old('table_header')" placeholder="Заголовки таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="slug" id="slug" :value="old('slug')" placeholder="Уникальная ссылка">
                </div>

                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Создать" class="primary"></li>
                </div>
            </div>
        </form>
    </section>
</x-dashboard-layout>