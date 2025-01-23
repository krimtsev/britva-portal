<x-admin-layout>
    <x-header-section title="Добавить в команду" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.teams.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.teams.update', $team->id) }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="row gtr-uniform">

                    <div class="col-12">
                        <h5>Фото</h5>
                        <img
                            id="image"
                            src="{{ $team->photo ? asset('storage/' . $team->photo) : asset('assets/teams/default.jpeg') }}"
                            alt="photo"
                            style="max-width: 250px; max-height: 250px"
                        />

                        <input
                            type="file"
                            name="photo"
                            :value="old(photo)"
                            placeholder="Фото"
                            accept="image/png, image/jpg, image/jpeg"
                            onchange="loadFileEvent(event)"
                        />
                    </div>

                    <div class="col-12">
                        <h5>Имя</h5>
                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ $team->name }}"
                            placeholder=""
                        />
                    </div>

                    <div class="col-12">
                        <h5>Партнер</h5>
                        <select name="partner_id" id="partner_id">
                            <option value="" disabled selected> --- </option>
                            @foreach($partners as $id => $name)
                                <option {{ $id == $team->partner_id ? 'selected' : '' }} value="{{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <h5>Роль</h5>
                        <select name="role_id" id="role_id">
                            <option value="" disabled selected> --- </option>
                            @foreach($rolesList as $key => $value)
                                <option {{ $key == $team->role_id ? 'selected' : '' }} value="{{ $key }}">
                                    {{ $value['name'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="primary ">Сохранить</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>

