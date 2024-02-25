<x-admin-layout>
    <x-header-section title="Добавить партнера" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.partner.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.partner.store') }}" >
                @csrf
                <div class="row gtr-uniform">

                    <div class="col-6 col-12-xsmall">
                        <h5>Имя партнера</h5>
                        <input
                            id="organization"
                            type="text"
                            name="organization"
                            :value="old('organization')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Название филиала</h5>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            :value="old('name')"
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
                            :value="old('contract_number')"
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
                            :value="old('contract_number')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Адрес организации</h5>
                        <input
                            id="address"
                            type="text"
                            name="address"
                            :value="old('address')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Yclients ID</h5>
                        <input
                            id="yclients_id"
                            type="text"
                            name="yclients_id"
                            :value="old('yclients_id')"
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
                                    :value="old('telnum_1')"
                                    placeholder=""
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="telnum_2"
                                    type="text"
                                    name="telnum_2"
                                    :value="old('telnum_2')"
                                    placeholder=""
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="telnum_3"
                                    type="text"
                                    name="telnum_3"
                                    :value="old('telnum_3')"
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
                            :value="old('start_at')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-12">
                        <button type="submit" class="fit primary ">Создать</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
