<x-admin-layout>
    <x-header-section title="Партнеры" />

    <section>
        <div class="mb-2 flex justify-content-end">
            @if ($is_disabled)
                <a href="{{ route('d.partner.index') }}" class="button mr-2">{{ __('Показать активные') }}</a>
            @else
                <a href="{{ route('d.partner.index', ["disabled" => true]) }}" class="button mr-2">{{ __('Показать отключенные') }}</a>
            @endif
            <a href="{{ route('d.partner.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table style="font-size: 0.8em;">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название филиала</th>
                        <th>Организация</th>
                        <th class="text-center">ИНН</th>
                        <th class="text-center">Номер<br>договора</th>
                        <th class="text-center">Телефон<br>филиала</th>
                        <th class="text-center">ID филиала</th>
                        <th class="text-center">Дата<br>открытия</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                        <tr
                            @if ($partner->disabled)
                                style="background: rgba(141, 60, 173, 0.1)"
                            @endif
                        >
                            <td> {{ $loop->iteration }} </td>
                            <td> {{ $partner->name }}</td>
                            <td> {{ $partner->organization }}</td>
                            <td class="text-center"> {{ $partner->inn }}</td>
                            <td class="text-center"> {{ $partner->contract_number }}</td>
                            <td class="text-center"> {{ $partner->mango_telnum }}</td>
                            <td class="text-center"> {{ $partner->yclients_id }}</td>
                            <td class="text-center"> {{ $partner->start_at }}</td>
                            <td>
                                @if (Route::has('d.partner.edit'))
                                    <a href="{{ route('d.partner.edit', $partner->id) }}" class="button primary icon small solid fa-edit button-icon-fix"></a>
                                @endif

                                {{--
                                @if (Route::has('d.partner.delete'))
                                    <form action="{{ route('d.user.delete', $partner->id) }}" method="post" class="inline-block ma-0">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="button primary icon small solid fa-trash"> Удалить </button>
                                    </form>
                                @endif
                                --}}
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
