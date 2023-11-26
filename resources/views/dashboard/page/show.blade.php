<x-admin-layout>
    <x-header-section title="{{$page->title}}" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.page.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="box">
            <h2>{{ $page->title }}</h2>
            <div class="page-content">
                {!! $page->description !!}
            </div>
        </div>
    </section>
</x-admin-layout>
