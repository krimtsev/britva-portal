<x-admin-layout>
    <x-header-section title="Список файлов" />

    <section>
        <div class="mb-2 flex justify-between gap-2">
            <a href="{{ route('d.upload.index') }}" class="button"> Файлы </a>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Название</th>
                        <th>Группа</th>
                        <th>Кол-во загрузок</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($files as $file)
                    <tr>
                        <td> {{ $file->title }}.{{$file->ext}} </td>
                        <td> {{ $file->folder->name }} </td>
                        <td> {{ $file->downloads }} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
    <div class="align-center">
        {{ $files->links() }}
    </div>
</x-admin-layout>
