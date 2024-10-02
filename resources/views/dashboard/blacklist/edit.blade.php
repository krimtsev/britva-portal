<x-admin-layout>
    <x-header-section title="Редактировать пост" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.blacklist.index') }}" class="button"> Назад </a>
        </div>

        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form action="{{ route('d.blacklist.update', $blacklist->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('patch')
            <div class="row gtr-uniform">
                <div class="col-12">
                    <h5>Номер</h5>
                    <input
                        disabled
                        type="text"
                        name="title"
                        id="title"
                        value="{{ $blacklist->number }}"
                        placeholder="Номер"
                    >
                </div>

                <div class="col-12">
                    <h5>Статус</h5>
                    <select name="is_disabled" id="is_disabled">
                        <option {{ $blacklist->is_disabled == 0 ? 'selected' : '' }} value="0">
                            Активен
                        </option>
                        <option {{ $blacklist->is_disabled == 1 ? 'selected' : '' }} value="1">
                            Отключен
                        </option>
                    </select>
                </div>

                <!-- Break -->
                <div class="col-12">
                    <input type="submit" value="Обновить" class="primary">
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>
