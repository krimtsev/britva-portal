<x-admin-layout>
    <x-header-section title="Редактировать пропущенные звонки" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.missed-calls.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.missed-calls.update', $partner->id) }}" method="post">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">

                <div class="col-12">
                    <h5>Статус</h5>
                    <select name="tg_active" id="tg_active">
                        <option value="---">---</option>
                        <option {{ 1 === $partner->tg_active ? 'selected="selected"' : '' }} value="{{ 1 }}">Активен</option>
                        <option {{ 0 === $partner->tg_active ? 'selected="selected"' : '' }} value="{{ 0 }}">Отключен</option>
                    </select>
                </div>

                <div class="col-12">
                    <h5>ID чата</h5>
                    <input
                        id="tg_chat_id"
                        type="text"
                        name="tg_chat_id"
                        value="{{ $partner->tg_chat_id }}"
                        placeholder=""
                    />
                </div>

                <div class="col-12">
                    <h5>Оплачено до</h5>
                    <input
                        id="tg_pay_end"
                        type="text"
                        name="tg_pay_end"
                        value="{{ $partner->tg_pay_end }}"
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
