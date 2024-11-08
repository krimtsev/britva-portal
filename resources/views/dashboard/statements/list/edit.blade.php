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
                    <div class="col-6 col-12-xsmall">
                        <h5>Тема запроса</h5>
                        <input
                            id="title"
                            type="text"
                            name="title"
                            value="{{ $statement->title }}"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Статус</h5>

                        <select name="state" id="state">
                            @foreach($stateList as $id => $value)
                                <option {{ $id == $statement->state ? 'selected' : '' }} value="{{ $id }}">
                                    {{ $value }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Отдел</h5>

                        <select name="category_id" id="category_id">
                            @foreach($categories as $category)
                                <option {{ $category->id == $statement->category_id ? 'selected' : '' }} value="{{ $category->id }}">
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Филиал</h5>

                        <select name="partner_id" id="partner_id">
                            @foreach($partners as $partner)
                                <option {{ $partner->id == $statement->partner_id ? 'selected' : '' }} value="{{ $partner->id }}">
                                    {{ $partner->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <input
                            type="submit"
                            value="Обновить данные"
                            class="primary"
                        />
                    </div>
                </div>
            </div>
        </form>

        <hr class="major">

        <form action="{{ route('d.statements.update-message', $statement->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="row gtr-uniform">
                    @if(count($messages))
                        <div class="col-12">
                            <h5>Сообщения: </h5>
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

                    <div class="col-12">
                        <input
                            type="submit"
                            value="Отправить сообщение"
                            class="primary"
                        />
                    </div>
                </div>
            </div>


        </form>
    </section>
</x-admin-layout>
