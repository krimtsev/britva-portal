<x-main-layout>
    <div class="flex w-full h-screen items-center justify-content-center flex-col">
        <form method="POST" action="{{ route('login') }}" class="auth-form">
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
                    autocomplete="current-password"
                    placeholder="Пароль"
                />
            </div>

            <!-- Remember Me -->
            <div class="mb">
                <input id="remember_me" type="checkbox" name="remember" checked>
                <label for="remember_me">{{ __('Запомнить') }}</label>
            </div>

            <div>
                <button class="fit primary">
                    {{ __('Войти') }}
                </button>
            </div>
        </form>
    </div>
</x-main-layout>
