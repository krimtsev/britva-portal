<x-admin-layout>
    <x-header-section title="Добавить пост" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.digest.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.digest.store') }}" method="post">
            @csrf
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" :value="old('title')" placeholder="Заголовок">
                </div>
                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Описание" rows="12">{{ old('description') }}</textarea>
                </div>

                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Создать" class="primary">
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>

<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description', {
        height:['400px']
    });
</script>
