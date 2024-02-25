<x-admin-layout>
    <x-header-section title="Партнеры" />

    <section>
        <div class="mb-2 flex justify-content-end">
            <a href="{{ route('d.partner.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Название филиала</th>
                        <th>Номер договора</th>
                        <th>ID филиала</th>
                        <th>Дата подписания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                        <tr>
                            <td> {{ $partner->id }}</td>
                            <td> {{ $partner->name }}</td>
                            <td> {{ $partner->contract_number }}</td>
                            <td> {{ $partner->yclients_id }}</td>
                            <td> {{ $partner->start_at }}</td>
                            <td>
                                @if (Route::has('d.partner.edit'))
                                    <a href="{{ route('d.partner.edit', $partner->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
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
