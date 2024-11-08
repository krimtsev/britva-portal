<x-admin-layout>
    <x-header-section title="Редактировать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.statements.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.statements.update', $statement->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">
                    <div class="col-12">
                        <h5>Название</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ $statement->title }}"
                            placeholder=""
                            disabled
                        />
                    </div>

                    <div class="col-12">
                        <h5>Категория заявления</h5>
                        <input
                            id="category_id"
                            type="text"
                            value="{{ $statement->category->title }}"
                            placeholder=""
                            disabled
                        />
                    </div>

                    <div class="col-12">
                        <h5>Партнер</h5>

                        <input
                            id="category_id"
                            type="text"
                            value="{{ $statement->partner->name }}"
                            placeholder=""
                            disabled
                        />
                    </div>

                    @if(count($messages))
                        <div class="col-12">
                            <h5>Предыдущие сообщения: </h5>
                        </div>

                        @foreach($messages as $message)
                            <div class="col-12">
                                <div class="statement-message_user"> > {{ $message->user->login }} ({{ $message->created_at }})</div>
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
                    value="Отправить"
                    class="primary"
                />
            </div>
        </form>

        <div style="float: right">
            <form action="{{ route('d.statement.state', $statement->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <button
                    type="submit"
                    class="danger"
                >
                    {{ $statement->state == 1 ? 'Закрыть заявление' : 'Открыть заявление' }}
                </button>
            </form>
        </div>
    </section>
</x-admin-layout>
