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
                        <h5>ИНН</h5>
                        <input
                            id="inn"
                            type="text"
                            name="inn"
                            :value="old('inn')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>ОГРНИП</h5>
                        <input
                            id="ogrnip"
                            type="text"
                            name="ogrnip"
                            :value="old('ogrnip')"
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

                    <div class="col-6 col-12-xsmall">
                        <h5>Mango телефон</h5>
                        <input
                            id="mango_telnum"
                            type="text"
                            name="mango_telnum"
                            :value="old('mango_telnum')"
                            placeholder=""
                        />
                    </div>

                    <div class="col-12">
                        <h5>Номера телефонов</h5>
                        <div class="row gtr-uniform">
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="number_0"
                                    type="text"
                                    name="telnums[0][number]"
                                    value=""
                                    placeholder="79991234567"
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="name_0"
                                    type="text"
                                    name="telnums[0][name]"
                                    value=""
                                    placeholder="Дмитрий"
                                />
                            </div>

                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="number_1"
                                    type="text"
                                    name="telnums[1][number]"
                                    value=""
                                    placeholder="79991234567"
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="name_1"
                                    type="text"
                                    name="telnums[1][name]"
                                    value=""
                                    placeholder="Дмитрий"
                                />
                            </div>

                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="number_2"
                                    type="text"
                                    name="telnums[2][number]"
                                    value=""
                                    placeholder="79991234567"
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="name_2"
                                    type="text"
                                    name="telnums[2][name]"
                                    value=""
                                    placeholder="Дмитрий"
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
