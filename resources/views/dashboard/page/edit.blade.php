<x-admin-layout>
    <x-header-section title="Редактировать пост" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.page.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.page.update', $page->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" value="{{ $page->title }}" placeholder="Заголовок">
                </div>
                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Описание" rows="12">{{ $page->description }}</textarea>
                </div>
                <div class="col-12">
                    <input type="text" name="slug" id="slug" value="{{ $page->slug }}" placeholder="Уникальная ссылка">
                </div>
                <div class="col-12">
                    <select name="is_published" id="is_published">
                        @foreach($page->statusList as $key => $value)
                        <option {{ $key === $page->is_published ? 'selected' : '' }} value="{{ $key }}">
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Обновить" class="primary">
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>

<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description', {
        height: ['400px']
    });
</script>
