<x-dashboard-layout>
    <x-header-section title="Просмотр пользователя" />

    <section>
        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.user.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="box">
            <p>Логин: {{ $user->login }}</p>
            <p>Роль: {{ $user->userRole() }}</p>
            <!-- <p>Имя: {{ $user->name }}</p>
            <p>Email: {{ $user->email }}</p> -->
        </div>
    </section>
</x-dashboard-layout>
