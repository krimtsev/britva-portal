<x-dashboard-layout>
    <x-header-section title="Добавить группу пользователя" />

    <section>

        <div class="mb-2 flex justify-content-start">
            <a href="{{ route('d.user.index') }}" class="button">{{ __('Назад') }}</a>
        </div>

        <div class="flex justify-content-center flex-col items-center">
            <!-- Validation Errors -->
            @if($userErrors)
                <ul>
                    @foreach (json_decode($userErrors) as $item)
                        <li> {{ $item->user }} | {{ $item->errors }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('d.user.create-group.store') }}" class="auth-form">
                @csrf
                <div class="row gtr-uniform">
                    <!-- Login -->
                    <div class="col-12">
                        <textarea
                            id="login"
                            type="text"
                            name="list"
                            rows="8"
                            required
                            autofocus
                            placeholder="login:password"
                        >{{ old('list') }}</textarea>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="fit primary ">Зарегистрировать</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</x-dashboard-layout>
