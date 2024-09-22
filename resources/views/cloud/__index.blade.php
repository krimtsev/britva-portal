<x-main-layout>
    <x-header-section title="Облако документов" />

    <section>
        <header class="main mb-2">
            <h3>
                @if(!$categorySlug)
                   Все документы
                @else
                    <a class="color-main" href="{{ route('upload.cloud') }}"> Все документы </a> /

                    @if(!$folderId)
                        {{ $categories->first()->name }}
                    @else
                        <a class="color-main" href="{{ route('upload.cloud', ["category" => $categories->first()->slug]) }}"> {{ $categories->first()->name }} </a> /

                        {{ $categories->first()->folders->first()->title }}
                    @endif
                @endif
            </h3>
        </header>

        <div class="row">
            @foreach($categories as $category)
                @if(!$categorySlug)
                    <div class="col-12 mb">
                        <a href="{{ route('upload.cloud', ["category" => $category->slug]) }}">
                            <div class="flex gap-2 items-center">
                                <x-icons name="folder" />
                                <div> {{ $category->name }} </div>
                            </div>
                        </a>
                    </div>
                @else
                    @foreach($category->folders as $folder)
                        @if($categorySlug && !$folderId)
                            <div class="col-12 mb">
                                <a href="{{ route('upload.cloud', ["category" => $category->slug, "folder" => $folder->folder]) }}">
                                    <div class="flex gap-2 items-center">
                                        <x-icons name="folder" />
                                        <div> {{ $folder->title }} </div>
                                    </div>
                                </a>
                            </div>
                        @else
                            @if($folder->folder == $folderId)
                                <div class="col-12">
                                    @foreach($folder->files as $file)
                                        <div class="col-12 mb">
                                            <a href="{{ route('upload.download', ["folder" => $folder->folder, "file" => $file->name]) }}">
                                                <div class="flex gap-2 items-center">
                                                    <x-icons name="{{ $file->ext }}" />
                                                    <div> {{ $file->title }}.{{ $file->ext }} </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>
    </section>
</x-main-layout>
