<x-admin-layout>
    <x-header-section title="Пропущенные звонки" />

    <section>
        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>Название филиала</th>
                        <th class="text-center">ID филиала</th>
                        <th class="text-center">ID чата</th>
                        <th class="text-center">Оплачено до</th>
                        <th class="text-center">Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                        <tr>
                            <td> {{ $partner->name }}</td>
                            <td class="text-center"> {{ $partner->yclients_id }}</td>
                            <td class="text-center"> {{ $partner->tg_chat_id }}</td>
                            <td class="text-center"> {{ $partner->tg_pay_end }}</td>
                            <td class="text-center">
                                {{ $partner->tg_active ? 'Активен' : 'Отключен' }}
                            </td>
                            <td>
                                @if (Route::has('d.missed-calls.edit'))
                                    <a href="{{ route('d.missed-calls.edit', $partner->id) }}" class="button primary icon small solid fa-edit button-icon-fix"></a>
                                @endif
                            </td>
                        </tr>

                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $partners->links() }}
    </div>
</x-admin-layout>
