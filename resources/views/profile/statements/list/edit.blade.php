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
                <div class="mb-2">
                    <div> <b>Тема запроса:</b> {{ $statement->title }}</div>
                    <div> <b>Отдел:</b> {{ $statement->category->title }}</div>
                    <div> <b>Статус:</b> {{ $stateList[$statement->state]['title'] }}</div>
                </div>

                <hr>

                <div class="statement-wrapper">
                    <div class="row gtr-uniform">
                        @if(count($messages))
                            @foreach($messages as $message)
                                <div class="col-12">
                                    <div class="statement-box {{ $message->user_id != $statement->user_id ? 'other' : '' }}">
                                        <div class="statement">
                                            <div class="statement-user"> {{ $message->user->name }} ({{ $message->created_at }})</div>
                                            <div class="statement-content">
                                                <div class="statement-text">{{ $message->text }}</div>
                                                @if(count($message->files))
                                                    <br/>
                                                    <div class="statement-files">
                                                        <div>Прикрепленные файлы:</div>
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
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>

                <div class="row gtr-uniform">
                    <div class="col-12">
                        <textarea name="text" id="text" rows="5" placeholder="Новое соощбение">{{ old('text') }}</textarea>
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
                    value="{{ $statement->state == 1 || $statement->state == 2 || $statement->state == 3 ? 'Закрыть заявку' : 'Открыть заявку' }}"
                    class="danger"
                />
            </form>
        </div>
    </section>
</x-admin-layout>
