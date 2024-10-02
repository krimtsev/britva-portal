<x-main-layout>
    <x-header-section title="Облако документов" />

    <section>
        <header class="main mb-2">
            <h3>
                @if(!$slug)
                    <span>Все документы</span>
                @else
                    <a class="color-main" href="{{ route('upload.cloud', "") }}"> Все документы </a>

                    @include('components.breadcrumbs', ['upload' => $uploads[0], 'slug' => $slug])
                @endif
            </h3>
        </header>

        <div class="mb-2">
            <input id="search" type="text" placeholder="Поиск" />
        </div>

        <div class="row">
            @foreach($uploads as $upload)
                @if($upload->slug != $slug)
                    <div class="col-12 mb link-item">
                        <a
                            class="inline-flex gap-2 items-center border-none"
                            href="{{ route('upload.cloud', ['slug' => $upload['slug']]) }}"
                        >
                            <x-icons name="folder" />
                            <span class="link-item-name"> {{ $upload['name'] }} </span>
                        </a>
                    </div>
                @else
                    @foreach($upload->children as $children)
                        <div class="col-12 mb link-item">
                            <a
                                class="inline-flex gap-2 items-center border-none"
                                href="{{ route('upload.cloud', ["slug" => $children->slug]) }}"
                            >
                                <x-icons name="folder" />
                                <span class="link-item-name"> {{ $children->name }} </span>
                            </a>
                        </div>
                    @endforeach
                @endif

                @if($slug)
                    <div class="col-12">
                        @foreach($upload->files as $file)
                            <div class="col-12 link-item" style="margin-bottom: 0.5em">
                                <a
                                    class="inline-flex gap-2 items-center border-none"
                                    href="{{ route('upload.download', ["folder" => $upload->folder, "file" => $file->name]) }}"
                                >
                                    <x-icons name="{{ $file->ext }}" />
                                    <div class="link-item-name"> {{ $file->title }}.{{ $file->ext }} </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>
    </section>
</x-main-layout>

<script>
    $(document).ready( function () {
        const $items = $(".link-item")

        $("#search").bind("input", function() {
            const search = $(this).val()
                .trim()
                .toLowerCase()

            $items.each(item => {
                const $item = $($items[item]);
                const text = $item.find(".link-item-name").text()
                    .trim()
                    .toLowerCase()

                if (!search || text.includes(search)) {
                    $item.show()
                } else {
                    $item.hide()
                }
            })
        })
    });
</script>
