<x-dashboard-layout>
    <x-header-section title="Редактировать Google Sheet" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.sheet.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.sheet.update', $sheet->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" value="{{ $sheet->title }}" placeholder="Заголовок">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_id" id="spreadsheet_id" value="{{ $sheet->spreadsheet_id }}" placeholder="ID таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_name" id="spreadsheet_name" value="{{ $sheet->spreadsheet_name }}" placeholder="Название таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="spreadsheet_range" id="spreadsheet_range" value="{{ $sheet->spreadsheet_range }}" placeholder="Диапазон таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="table_header" id="table_header" value="{{ $sheet->table_header }}" placeholder="Заголовки таблицы">
                </div>

                <div class="col-12">
                    <input type="text" name="slug" id="slug" value="{{ $sheet->slug }}" placeholder="Уникальная ссылка">
                </div>

                <div class="col-12">
                    <select name="is_published" id="is_published">
                        @foreach($sheet->statusList as $key => $value)
                        <option {{ $key === $sheet->is_published ? 'selected' : '' }} value="{{ $key }}">
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Обновить" class="primary"></li>
                </div>
            </div>
        </form>
    </section>
</x-dashboard-layout>