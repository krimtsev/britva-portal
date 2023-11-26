<x-admin-layout>
    <x-header-section title="Редактировать пост" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.post.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.post.update', $post->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">
                <div class="col-12">
                    <input type="text" name="title" id="title" value="{{ $post->title }}" placeholder="Заголовок">
                </div>
                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Описание" rows="12">{{ $post->description }}</textarea>
                </div>
                <div class="col-12">
                    <select name="is_published" id="is_published">
                        @foreach($post->statusList as $key => $value)
                        <option {{ $key === $post->is_published ? 'selected' : '' }} value="{{ $key }}">
                            {{ $value }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12">
                    @if($post->image)
                    <div class="flex">
                        <div>
                            <span class="image fit">
                                <img style="max-width: 200px" src="{{ asset($post->image) }}" alt="">
                            </span>
                        </div>
                        <div class="flex items-center" style="margin-left: 50px">
                            <input type="checkbox" id="remove_image" name="remove_image">
                            <label for="remove_image">Удалить изображение</label>
                        </div>
                    </div>
                    @endif

                    <input type="file" name="image" id="image" placeholder="Изображение">
                </div>
                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Обновить" class="primary"></li>
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
