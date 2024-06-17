<x-admin-layout>
    <x-header-section title="Пропущенные звонки" />

    <section>
        <div class="mb-2 flex justify-content-end">
            <a href="{{ route('d.messages.index') }}" class="button mr-2">{{ __('Отправка сообщения') }}</a>
        </div>

        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название филиала</th>
                        <th class="text-center">ID филиала</th>
                        <th class="text-center">ID чата</th>
                        <th class="text-center">Новые</th>
                        <th class="text-center">Повторные</th>
                        <th class="text-center">Потерянные</th>
                        <th class="text-center">Оплачено до</th>
                        <th class="text-center">Статус</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                        <tr>
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $partner->name }}</td>
                            <td class="text-center"> {{ $partner->yclients_id }}</td>
                            <td class="text-center"> {{ $partner->tg_chat_id }}</td>
                            <td class="text-center">
                                @if ($partner->new_client_days <= 0)
                                    -
                                @else
                                    {{ $partner->new_client_days }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($partner->repeat_client_days <= 0)
                                    -
                                @else
                                    {{ $partner->repeat_client_days }}
                                @endif
                            </td>
                            <td class="text-center">
                                @if ($partner->lost_client_days <= 0)
                                    -
                                @else
                                    {{ $partner->lost_client_days }}
                                @endif
                            </td>
                            <td class="text-center"> {{ $partner->tg_pay_end }}</td>
                            <td class="text-center">
                                @if ($partner->tg_active)
                                    <i class="fa fa-check color-success"></i>
                                @else
                                    <i class="fa fa-ban color-danger"></i>
                                @endif
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
