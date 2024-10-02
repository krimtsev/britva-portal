<x-admin-layout>
    <x-header-section title="Группы файлов" />

    <section>
        <div class="mb-2 flex justify-between gap-2">
            <a href="{{ route('d.upload-files.index') }}" class="button"> Список </a>
            <a href="{{ route('d.upload.create') }}" class="button"> Добавить </a>
        </div>

        <header class="main mb-2">
            <h3> Файлы и папки </h3>
        </header>

        <div>
            @foreach ($uploads as $upload)
                @include('components.categories', ['upload' => $upload])
            @endforeach
        </div>
    </section>

</x-admin-layout>
