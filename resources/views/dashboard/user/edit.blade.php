<x-dashboard-layout>
    <x-header-section title="Редактировать пользователя" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.user.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form action="{{ route('d.user.update', $user->id) }}" method="post" class="auth-form">
                @csrf
                @method('patch')

                <div class="row gtr-uniform">
                    <div class="col-12">
                        <input
                            id="login"
                            type="text"
                            name="login"
                            value="{{ $user->login }}"
                            placeholder="Логин"
                            disabled
                        />
                    </div>

                    <div class="col-12">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Пароль"
                        />
                    </div>

                    <div class="col-12">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            placeholder="Подтвердить пароль"
                        />
                    </div>

                    <div class="col-12">
                        <select name="role_id" id="role_id">
                            @foreach($user->roleListById() as $key => $value)
                                <option
                                    {{ $key === $user->role_id ? 'selected' : '' }}
                                    value="{{ $key }}"
                                >
                                    {{ $value }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <input type="checkbox" id="is_disabled" name="is_disabled"  {{ $user->is_disabled ? 'checked' : ''}}>
                        <label for="is_disabled">Пользователь заблокирован</label>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="fit primary">Обновить</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-dashboard-layout>
