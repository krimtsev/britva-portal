<x-admin-layout>
    <x-header-section title="Партнеры" />

    <section>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ИП</th>
                        <th>Название филиала</th>
                        <th>Номер договора</th>
                        <th>ID Yclients</th>
                        <th>Адрес</th>
                        <th>Дата подписания</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($partners as $partner)
                        <tr>
                            <td> {{ $partner->organization }}</td>
                            <td> {{ $partner->name }}</td>
                            <td> {{ $partner->contract_number }}</td>
                            <td>
                                @if (empty($partner->yclients_id))
                                    Не указан
                                @else
                                    <a href="https://yclients.com/timetable/{{ $partner->yclients_id }}">{{ $partner->yclients_id }}</a>
                                @endif
                            </td>
                            <td> {{ $partner->address }}</td>
                            <td> {{ $partner->start_at }}</td>
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