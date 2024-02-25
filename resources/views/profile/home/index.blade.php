<x-admin-layout>
    <x-header-section title="Профиль" />

    <section>
        <div class="flex justify-content-center flex-col">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <div>Вы авторизировались как: <b>{{ $login }}</b></div>

            @if($partner)
                <hr>

                @if($partner->organization)
                    <div class="mb-2">Организация: {{ $partner->organization }} </div>
                @endif

                @if($partner->name)
                    <div class="mb-2">Филиал: {{ $partner->name }} </div>
                @endif

                @if($partner->contract_number)
                    <div class="mb-2">Номер договора: {{ $partner->contract_number }} </div>
                @endif

                @if($partner->address)
                    <div class="mb-2">Адрес: {{ $partner->address }} </div>
                @endif

                @if($partner->telnum_1 || $partner->telnum_2 || $partner->telnum_3)
                    <div class="mb-2">Телефоны:
                        @if($partner->telnum_1)
                            <span>{{ $partner->telnum_1 }}; </span>
                        @endif
                        @if($partner->telnum_2)
                            <span>{{ $partner->telnum_2 }}; </span>
                        @endif
                        @if($partner->telnum_3)
                            <span>{{ $partner->telnum_3 }}; </span>
                        @endif
                    </div>
                @endif

                @if($partner->start_at)
                    <div class="mb-2">Договор подписан: {{ $partner->start_at }} </div>
                @endif
            @endif
        </div>
    </section>
</x-admin-layout>
