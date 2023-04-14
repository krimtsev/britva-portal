<x-main-layout>
    <div class="flex w-full h-screen items-center justify-content-center flex-col">
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('login') }}" class="auth-form">
            @csrf
			
			<!-- Alert -->
			<div class="mb">
				<p class="text-center">Коллеги, не пугайтесь!<br />У нас обновился портал.<br />За доступами к Диме Крымцеву.</p>	
            </div>
			
			<!-- Logo -->
			<div class="logo">
				@if(isset($_COOKIE['theme']) && $_COOKIE['theme'] == 'dark')
					<img src="{{ asset('images/static/logo-white.png') }}" />
				@else
					<img src="{{ asset('images/static/logo.png') }}" />
				@endif
			</div>
			
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
