<x-admin-layout>
    <x-header-section title="Добавить в команду" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('p.teams.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <!-- Validation Errors -->
        <div id="auth-validation-errors" style="display: none;">
            <div class="font-medium text-red-600">
                Упс! Что-то пошло не так.
            </div>
            <ul class="mt-3 list-disc list-inside text-sm text-red-600"></ul>
        </div>

        <form
            id="form"
            method="POST"
            action="{{ route('p.teams.store') }}"
            enctype="multipart/form-data"
        >
            @csrf

            <div class="mb-2">
                <div class="teams-wrapper">
                    <div class="row gtr-uniform">
                        <div class="col-6">
                            <div class="row gtr-uniform">
                                <div class="col-12">
                                    <input
                                        id="name"
                                        type="text"
                                        name="name"
                                        :value="old('name')"
                                        placeholder="Имя"
                                    />
                                </div>

                                <div class="col-12">
                                    <select name="role_id" id="role_id">
                                        <option value="" disabled selected> Специализация </option>
                                        @foreach($rolesList as $key => $value)
                                            <option value="{{ $key }}">
                                                {{ $value['name'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <textarea
                                        id="description"
                                        name="description"
                                        placeholder="Описание"
                                        rows="9"
                                    >{{ old('description') }}</textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <img
                                id="image"
                                src="{{ asset('assets/teams/default.jpeg') }}"
                                alt="photo"
                                style="max-width: 400px; max-height: 400px; width: auto;"
                            />

                            <input
                                type="file"
                                name="photo"
                                :value="old(photo)"
                                placeholder="Фото"
                                accept="image/png, image/jpg, image/jpeg"
                                onchange="loadFileEvent(event)"
                                style="max-width: 400px; max-height: 400px"
                            />
                        </div>
                        <div class="col-12">
                            <button type="submit" class="primary ">Добавить</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </section>
</x-admin-layout>

<script>
    $(document).ready(function () {
        $("#form").on('submit', function (e) {
            e.preventDefault();
            CreateTeams.call(this)
        });
    });
</script>

