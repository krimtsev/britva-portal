<x-dashboard-layout>
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
                        <th>Id</th>
                        <th>Логин</th>
                        <th>Роль</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td> {{ $user->id }}</td>
                            <td> <a href="{{ route('d.user.show', $user->id) }}"> {{ $user->login }} </a></td>
                            <td> {{ $user->userRole() }}</td>
                            <td> {{ $user->is_disabled ? 'Заблокирован' : 'Активен' }}</td>
                            <td> {{ $user->created_at }}</td>
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
</x-dashboard-layout>
