<ul style="margin-bottom: 1em">
    <li style="margin-bottom: 0.5em">
        <a href="{{ route('d.upload.edit', $upload->id) }}">
            {{ $upload->name }}
        </a>
        @if (Route::has('d.upload.edit'))
            <a href="{{ route('upload.cloud', $upload->slug) }}" class="ml-1 icon small solid fa-external-link-square-alt"> Просмотр </a>
        @endif
    </li>

    @if($upload->children)
        @foreach($upload->children as $children)
            @include('components.categories', ['upload' => $children])
        @endforeach
    @endif
</ul>
