<x-admin-layout>
    <x-header-section title="Пользователи" />

    <section >
        <div class="mb-2 flex justify-content-end">
            <a href="{{ route('d.user.create-group.index') }}" class="button mr-2">{{ __('Добавить группу') }}</a>
            <a href="{{ route('d.user.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Логин</th>
                        <th>Роль</th>
                        <th>Статус</th>
                        <th>ID филиала</th>
                        <th>Последняя активность</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr
                            @if ($user->is_disabled)
                                style="background: rgba(141, 60, 173, 0.1)"
                            @elseif($user->isAdmin())
                                style="background: rgba(83, 173, 60, 0.1)"
                            @endif
                        >
                            <td> {{ $user->id }}</td>
                            <td> <a href="{{ route('d.user.show', $user->id) }}"> {{ $user->login }} </a></td>
                            <td> {{ $user->userRole() }}</td>
                            <td> {{ $user->is_disabled ? 'Заблокирован' : 'Активен' }}</td>

                            <td>
                                <div>
                                    @if (empty($user->yclients_id))
                                        Не указан
                                    @else
                                        <a href="https://yclients.com/timetable/{{ $user->yclients_id }}">{{ $user->yclients_id }}</a>
                                    @endif
                                </div>
                                <div style="color: gray;">
                                    @if ($user->partner_yclients_id)
                                        {{ $user->partner_yclients_id }}
                                    @endif
                                </div>
                            </td>

                            <td> {{ $user->last_activity }}</td>
                            <td>
                                @if (Route::has('d.user.edit'))
                                    <a href="{{ route('d.user.edit', $user->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                                @endif
                                @if (Route::has('d.user.delete'))
                                    <form action="{{ route('d.user.delete', $user->id) }}" method="post" class="inline-block ma-0">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="button primary icon small solid fa-trash"> Удалить </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $users->links() }}
    </div>
</x-admin-layout>
