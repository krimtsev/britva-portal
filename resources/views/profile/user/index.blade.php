<x-admin-layout>
    <x-header-section title="Смена пароля" />

    <section>
        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form action="{{ route('p.user.password.update') }}" method="post" class="auth-form">
                @csrf
                @method('patch')

                <div class="row gtr-uniform">
                    <div class="col-12">
                        <input
                            id="current_password"
                            type="password"
                            name="current_password"
                            autocomplete="new-password"
                            placeholder="Текущий пароль"
                        />
                    </div>

                    <div class="col-12">
                        <input
                            id="password"
                            type="password"
                            name="password"
                            autocomplete="new-password"
                            placeholder="Новый пароль"
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
                        <button type="submit" class="fit primary">Обновить пароль</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-admin-layout>
