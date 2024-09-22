<x-main-layout>
    <x-header-section title="Облако документов" />

    <section>
        <header class="main mb-2">
            <h3>
                @if(!$folderId)
                    {{ $category->name }}
                @else
                    <a class="color-main" href="{{ route('upload.cloud', $category->id) }}"> {{ $category->name }} </a>
                @endif

                @if($folderId)
                    / {{ $folders[0]->title }}
                @endif
            </h3>
        </header>

        <div class="row">
            @foreach($folders as $folder)

                @if(!$folderId)
                    <div class="col-12 mb">
                        <a href="{{ route('upload.cloud', ["category" => $category->id, "folder" => $folder->id]) }}">
                            <div class="flex gap-2 items-center">
                                <x-icons
                                    name="folder"
                                />
                                <div> {{ $folder->title }} </div>
                            </div>
                        </a>
                    </div>
                @endif

                @if($folderId)
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
            @endforeach
        </div>
    </section>
</x-main-layout>
