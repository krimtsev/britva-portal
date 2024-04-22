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
                    <h5>ИНН</h5>
                    <input
                        id="inn"
                        type="text"
                        name="inn"
                        value="{{ $partner->inn }}"
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

                <div class="col-6 col-12-xsmall">
                    <h5>Mango телефон</h5>
                    <input
                        id="mango_telnum"
                        type="text"
                        name="mango_telnum"
                        value="{{ $partner->mango_telnum }}"
                        placeholder=""
                    />
                </div>

                <div class="col-12">
                    <h5>Номера телефонов</h5>
                    <div class="row gtr-uniform">
                        @foreach ($partner->telnums as $key => $telnum)
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="number_{{ $key  }}"
                                    type="text"
                                    name="telnums[{{ $key  }}][number]"
                                    value="{{ $telnum["number"] }}"
                                    placeholder="79991234567"
                                />
                            </div>
                            <div class="col-6 col-12-xsmall">
                                <input
                                    id="name_{{ $key }}"
                                    type="text"
                                    name="telnums[{{ $key }}][name]"
                                    value="{{ $telnum["name"] }}"
                                    placeholder="Дмитрий"
                                />
                            </div>
                        @endforeach
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
                    <h5>Статус партнера</h5>
                    <input type="checkbox" id="disabled" name="disabled"  {{ $partner->disabled ? 'checked' : ''}}>
                    <label for="disabled">Партнер заблокирован</label>
                </div>

                <div class="col-12">
                    <button type="submit" class="fit primary ">Обновить</button>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
