<x-dashboard-layout>
    <x-header-section title="Посты" />

    <section>
        <div class="mb-2 flex justify-content-end">
            <a href="{{ route('d.digest.create') }}" class="button">{{ __('Добавить') }}</a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Заголовок</th>
                        <th>Статус</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($digests as $digest)
                    <tr>
                        <td> {{ $digest->id }}</td>
                        <td> <a href="{{ route('d.digest.show', $digest->id) }}"> {{ $digest->title }} </a></td>
                        <td> {{ $digest->status() }} </td>
                        <td> {{ $digest->created_at }}</td>
                        <td>
                            @if (Route::has('d.digest.edit'))
                            <a href="{{ route('d.digest.edit', $digest->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('d.digest.delete'))
                            <form action="{{ route('d.digest.delete', $digest->id) }}" method="post" class="inline-block ma-0">
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
        {{ $digests->links() }}
    </div>
</x-dashboard-layout>
