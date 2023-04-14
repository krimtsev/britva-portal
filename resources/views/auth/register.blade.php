<x-main-layout>
    <div class="flex w-full h-screen items-center justify-content-center flex-col">
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('register') }}" class="auth-form">
            @csrf
						
            <!-- Login -->
            <div class="mb">
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
            <div class="mb">
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
            <div class="mb">
                <input
                    id="password_confirmation"
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="Подтвердить пароль"
                />
            </div>

            <div>
                <button type="submit" class="fit primary ">
                    {{ __('Зарегистрироваться') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
