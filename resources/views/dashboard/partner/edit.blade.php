<x-admin-layout>
    <x-header-section title="Редактировать партнера" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.partner.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.partner.update', $partner->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">

                <div class="col-6 col-12-xsmall">
                    <h5>Имя партнера</h5>
                    <input
                        id="organization"
                        type="text"
                        name="organization"
                        value="{{ $partner->organization }}"
                        placeholder=""
                    />
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Название филиала</h5>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ $partner->name }}"
                        required
                        placeholder=""
                    />
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Номер договора</h5>
                    <input
                        id="contract_number"
                        type="text"
                        name="contract_number"
                        value="{{ $partner->contract_number }}"
                        required
                        placeholder=""
                    />
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Email</h5>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ $partner->email }}"
                        placeholder=""
                    />
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Адрес организации</h5>
                    <input
                        id="address"
                        type="text"
                        name="address"
                        value="{{ $partner->address }}"
                        placeholder=""
                    />
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Yclients ID</h5>
                    <input
                        id="yclients_id"
                        type="text"
                        name="yclients_id"
                        value="{{ $partner->yclients_id }}"
                        placeholder=""
                    />
                </div>

                <div class="col-12">
                    <h5>Номера телефонов</h5>
                    <div class="row gtr-uniform">
                        <div class="col-6 col-12-xsmall">
                            <input
                                id="telnum_1"
                                type="text"
                                name="telnum_1"
                                value="{{ $partner->telnum_1 }}"
                                placeholder=""
                            />
                        </div>
                        <div class="col-6 col-12-xsmall">
                            <input
                                id="telnum_2"
                                type="text"
                                name="telnum_2"
                                value="{{ $partner->telnum_2 }}"
                                placeholder=""
                            />
                        </div>
                        <div class="col-6 col-12-xsmall">
                            <input
                                id="telnum_3"
                                type="text"
                                name="telnum_3"
                                value="{{ $partner->telnum_3 }}"
                                placeholder=""
                            />
                        </div>

                    </div>
                </div>

                <div class="col-6 col-12-xsmall">
                    <h5>Дата подписания</h5>
                    <input
                        id="start_at"
                        type="text"
                        name="start_at"
                        value="{{ $partner->start_at }}"
                        placeholder=""
                    />
                </div>

                <div class="col-12">
                    <button type="submit" class="fit primary ">Обновить</button>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
