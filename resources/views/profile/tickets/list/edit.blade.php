<x-admin-layout>
    <x-header-section title="Редактировать заявку" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('p.tickets.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('p.tickets.update-message', $ticket->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')

            <div class="mb-2">
                <div class="mb-2">
                    <div> <b>Тема запроса:</b> {{ $ticket->title }}</div>
                    <div> <b>Отдел:</b> {{ $ticket->category->title }}</div>
                    <div> <b>Статус:</b> {{ $stateList[$ticket->state]['title'] }}</div>
                </div>

                <hr>

                <div class="ticket-wrapper">
                    <div class="row gtr-uniform">
                        @if(count($messages))
                            @foreach($messages as $message)
                                <div class="col-12">
                                    <div class="ticket-box {{ $message->user_id != $ticket->user_id ? 'other' : '' }}">
                                        <div class="ticket">
                                            <div class="ticket-user"> {{ $message->user->name }} ({{ $message->created_at }})</div>
                                            <div class="ticket-content">
                                                <div class="ticket-text">{{ $message->text }}</div>
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
            <form action="{{ route('p.ticket.state', $ticket->id) }}" method="post" enctype="multipart/form-data">
                @csrf

                <input
                    type="submit"
                    value="{{ $ticket->state == 1 || $ticket->state == 2 || $ticket->state == 3 ? 'Закрыть заявку' : 'Открыть заявку' }}"
                    class="danger"
                />
            </form>
        </div>
    </section>
</x-admin-layout>
