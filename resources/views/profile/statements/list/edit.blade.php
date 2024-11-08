<x-admin-layout>
    <x-header-section title="Редактировать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('p.statements.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('p.statements.update-message', $statement->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <div> <b>Тема запроса:</b> {{ $statement->title }}</div>
                        <div> <b>Отдел:</b> {{ $statement->category->title }}</div>
                        <div> <b>Статус:</b> {{ $stateList[$statement->state] }}</div>
                     </div>

                    @if(count($messages))
                        <div class="col-12">
                            <h5>Сообщения: </h5>
                        </div>

                        @foreach($messages as $message)
                            <div class="col-12">
                                <div class="statement-message_user"> > {{ $message->user->name }} ({{ $message->created_at }})</div>
                                <div class="statement-message_text">{{ $message->text }}</div>

                                @if(count($message->files))
                                    <div class="statement-message_files">
                                        <div class="ma-0">Прикрепленные файлы:</div>
                                        <ul class="ma-0">
                                            @foreach($message->files as $file)
                                                <li>
                                                    <a href="{{ route('statement.download', ["folder" => $statement->id, "file" => $file->name]) }}">
                                                        {{ $file->origin }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    @endif

                    <div class="col-12">
                        <h5>Добавить сообщение</h5>
                        <textarea name="text" id="text" rows="5">{{ old('text') }}</textarea>
                    </div>

                    <div class="col-12">
                        <h5>Загрузить файлы</h5>
                        <input
                            type="file"
                            name="files[]"
                            :value="old(files)"
                            placeholder="Файлы"
                            multiple="multiple"
                        />
                    </div>
                </div>
            </div>

            <div style="float: left">
                <input
                    type="submit"
                    value="Отправить сообщение"
                    class="primary"
                />
            </div>
        </form>

        <div style="float: right">
            <form action="{{ route('p.statement.state', $statement->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <input
                    type="submit"
                    value="{{ $statement->state == 1 ? 'Закрыть заявку' : 'Открыть заявку' }}"
                    class="danger"
                />
            </form>
        </div>
    </section>
</x-admin-layout>
