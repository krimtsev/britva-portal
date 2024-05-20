<x-admin-layout>
    <x-header-section title="Просмотр пользователя" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.user.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="box">
            <p>Логин: {{ $user->login }}</p>
            <p>Имя: {{ $user->name }}</p>
            <p>Роль: {{ $user->userRole() }}</p>
            <p>Дата регистрации: {{ $user->created_at }}</p>
            <p>Последняя активность: {{ $user->last_activity }}</p>
        </div>
    </section>
</x-admin-layout>
