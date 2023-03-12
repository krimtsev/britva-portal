<x-dashboard-layout>
    <x-header-section title="Добавить пост" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.post.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.post.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" value="" placeholder="Заголовок">
                </div>
                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Описание" rows="12"></textarea>
                </div>
                <div class="col-12">
                    <input type="file" name="image" id="image" placeholder="Изображение">
                </div>
                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Создать" class="primary"></li>
                </div>
            </div>
        </form>
    </section>
</x-dashboard-layout>

<script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
<script>
    CKEDITOR.replace('description', {
        height:['400px']
    });
</script>
