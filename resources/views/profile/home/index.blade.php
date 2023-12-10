<x-admin-layout>
    <x-header-section title="Профиль" />

    <section>
        <div class="flex justify-content-center flex-col">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div>Вы авторизировались как: <b>{{ $data["login"] }}</b></div>
            @if(!empty($data["name"]))
                <div>Ваш филиал {{ $data["name"] }}</div>
            @endif
        </div>
    </section>
</x-admin-layout>
