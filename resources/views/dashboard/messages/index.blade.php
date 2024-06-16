<x-admin-layout>
    <x-header-section title="Отправить сообщение в TG" />

    <section>
        <div class="mb-2 flex justify-content-end">
            <a href="{{ route('d.missed-calls.index') }}" class="button mr-2">{{ __('Пропущенные звонки') }}</a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.messages.send') }}" method="post">
            @csrf
            <div class="row gtr-uniform">
                <div class="col-12">
                    <select name="selected_partners" id="selected_partners">
                        <option value="test"> --- Тестовое сообщение --- </option>
                        <option value="all"> --- Отправить всем --- </option>

                        @foreach($partners as $partner)
                            <option value="{{ $partner->tg_chat_id }}">
                                {{ $partner->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12">
                    <textarea name="description" id="description" placeholder="Описание" rows="12"></textarea>
                </div>

                <div class="col-12">
                    <input type="submit" value="Отправить" class="primary">
                </div>

            </div>
        </form>

    </section>
</x-admin-layout>
