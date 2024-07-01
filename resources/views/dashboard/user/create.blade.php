<x-admin-layout>
    <x-header-section title="Добавить пользователя" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.user.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.user.store') }}" class="auth-form">
                @csrf
                <div class="row gtr-uniform">
                    <!-- Login -->
                    <div class="col-12">
                        <input
                            id="login"
                            type="text"
                            name="login"
                            :value="old('login')"
                            required
                            autofocus
                            placeholder="Логин"
                        />
                    </div>

                    <!-- Password -->
                    <div class="col-12">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Пароль"
                        />
                    </div>

                    <!-- Confirm Password -->
                    <div class="col-12">
                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            placeholder="Подтвердить пароль"
                        />
                    </div>

                    <div class="col-12">
                        <select name="partner_id" id="partner_id">
                            <option value="" disabled selected> --- </option>
                            @foreach($partners as $partner)
                                <option value="{{ $partner->id }}">
                                    @if ($partner->name)
                                        {{ $partner->name }}
                                    @endif
                                    ({{ $partner->organization }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="fit primary ">Зарегистрировать</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
