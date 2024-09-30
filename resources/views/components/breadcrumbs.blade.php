<span>
    @if($upload->parent)
        @include('components.breadcrumbs', ['upload' => $upload->parent])
    @endif

    <span class="breadcrumbs-item">
        @if($upload->slug == $slug)
            {{ $upload->name }}
        @else
            <a class="color-main" href="{{ route('upload.cloud', $upload->slug) }}">{{ $upload->name }}</a>
        @endif
    </span>
</span>
