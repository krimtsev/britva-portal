<x-main-layout>
    <x-header-section title="{{ $page->title }}" />
    <section>
        <!-- <h2>{{ $page->title }}</h2> -->
        <div class="page-content">
            {!! $page->description !!}
        </div>
    </section>
</x-main-layout>
