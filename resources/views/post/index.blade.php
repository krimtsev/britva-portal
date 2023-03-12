<x-main-layout>
    <x-header-section title="Новости" />
    <section>
        @foreach ($posts as $key => $post)
            <x-post :post="$post" />

            @if ($key !== ($posts->count() -1))
                <hr class="major">
            @endif

        @endforeach
    </section>
    <div class="align-center">
        {{ $posts->links() }}
    </div>
</x-main-layout>
