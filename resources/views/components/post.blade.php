@props(['post'])

<div class="content">
    <header class="main">
        <h2>{{ $post->title }}</h2>
    </header>
    @if($post->image)
        <span class="image main" style="display: flex; justify-content: center;">
            <img style="max-width: 600px;" src="{{ asset($post->image) }}" alt="">
        </span>
    @endif
    <div class="post-description"> {!! $post->description !!} </div>
</div>

