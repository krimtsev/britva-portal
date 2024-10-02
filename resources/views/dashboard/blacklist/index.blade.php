<x-admin-layout>
    <x-header-section title="Черный список" />

    <section>
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Номер</th>
                        <th>Статус</th>
                        <th>Коментарий</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($blacklist as $one)
                    <tr>
                        <td> {{ $one->number_id }}</td>
                        <td> {{ $one->number }} </td>
                        <td>
                            @if($one->is_disabled)
                                <span>Отключен</span>
                            @else
                                <span>Активен</span>
                            @endif
                        </td>
                        <td> {{ $one->comment }}</td>
                        <td>
                            @if (Route::has('d.blacklist.edit'))
                                <a href="{{ route('d.blacklist.edit', $one->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $blacklist->links() }}
    </div>
</x-admin-layout>
