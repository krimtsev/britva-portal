<x-admin-layout>
    <x-header-section title="Редактировать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.tickets.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.tickets.update', $ticket->id) }}" method="post" enctype="multipart/form-data">
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
                            value="{{ $ticket->title }}"
                            placeholder=""
                        />
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Статус</h5>

                        <select name="state" id="state">
                            @foreach($stateList as $id => $value)
                                <option {{ $id == $ticket->state ? 'selected' : '' }} value="{{ $id }}">
                                    {{ $value['title'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Отдел</h5>

                        <select name="category_id" id="category_id">
                            @foreach($categories as $category)
                                <option {{ $category->id == $ticket->category_id ? 'selected' : '' }} value="{{ $category->id }}">
                                    {{ $category->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-6 col-12-xsmall">
                        <h5>Филиал</h5>

                        <select name="partner_id" id="partner_id">
                            @foreach($partners as $partner)
                                <option {{ $partner->id == $ticket->partner_id ? 'selected' : '' }} value="{{ $partner->id }}">
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

        <hr>

        <form action="{{ route('d.tickets.update-message', $ticket->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="ticket-wrapper">
                    <div class="row gtr-uniform">
                        @if(count($messages))
                            @foreach($messages as $message)
                                <div class="col-12">
                                    <div class="ticket-box {{ $message->user_id != $ticket->user_id ? 'other' : '' }}">
                                        <div class="ticket">
                                            <div class="ticket-user"> {{ $message->user->nameOrLogin() }} ({{ $message->created_at }})</div>
                                            <div class="ticket-content">
                                                <div class="ticket-text">{!! $message->text !!}</div>
                                                @if(count($message->files))
                                                    <br/>
                                                    <div class="ticket-files">
                                                        <div>Прикрепленные файлы:</div>
                                                        <ul class="ma-0">
                                                            @foreach($message->files as $file)
                                                                <li>
                                                                    <a href="{{ route('ticket.download', ["folder" => $ticket->id, "file" => $file->name]) }}">
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
            <form action="{{ route('d.tickets.delete', $ticket->id) }}" method="post">
                @csrf
                @method('delete')

                <button type="submit" class="danger"> Добавить в архив </button>
            </form>
        </div>
    </section>
</x-admin-layout>
