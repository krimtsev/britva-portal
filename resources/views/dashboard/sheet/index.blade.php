<x-admin-layout>
    <x-header-section title="Google Sheets" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.sheet.create') }}" class="button"> Добавить </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Заголовок</th>
                        <th>Статус</th>
                        <th>Ссылка</th>
                        <th>Дата создания</th>
                        <th>Действия</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sheets as $sheet)
                    <tr>
                        <td> {{ $sheet->id }}</td>
                        <td> <a href="{{ route('d.sheet.show', $sheet->id) }}"> {{ $sheet->title }} </a></td>
                        <td> {{ $sheet->status() }} </td>
                        <td> <a href="{{ route('sheet.index', $sheet->slug) }}" target="_blank"> {{ $sheet->slug }}</td>
                        <td> {{ $sheet->created_at }}</td>
                        <td>
                            @if (Route::has('d.sheet.edit'))
                            <a href="{{ route('d.sheet.edit', $sheet->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('d.sheet.delete'))
                            <form action="{{ route('d.sheet.delete', $sheet->id) }}" method="post" class="inline-block ma-0">
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
        {{ $sheets->links() }}
    </div>
</x-admin-layout>
