<x-admin-layout>
    <x-header-section title="Посты" />

    <section>
        <div class="mb-2 flex justify-between">
            <a href="{{ route('d.post.create') }}" class="button"> Добавить </a>
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
                    @foreach ($posts as $post)
                    <tr>
                        <td> {{ $post->id }}</td>
                        <td> <a href="{{ route('d.post.show', $post->id) }}"> {{ $post->title }} </a></td>
                        <td> {{ $post->status() }} </td>
                        <td> {{ $post->created_at }}</td>
                        <td>
                            @if (Route::has('d.post.edit'))
                            <a href="{{ route('d.post.edit', $post->id) }}" class="button primary icon small solid fa-edit">Редактировать</a>
                            @endif
                            @if (Route::has('d.post.delete'))
                            <form action="{{ route('d.post.delete', $post->id) }}" method="post" class="inline-block ma-0">
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
        {{ $posts->links() }}
    </div>
</x-admin-layout>
