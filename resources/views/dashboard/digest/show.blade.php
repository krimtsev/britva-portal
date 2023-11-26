<x-admin-layout>
    <x-header-section title="Просмотр поста" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.digest.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="box">
            <x-post :post="$digest" />
        </div>
    </section>
</x-admin-layout>
