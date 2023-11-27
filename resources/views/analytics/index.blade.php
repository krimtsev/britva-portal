<x-admin-layout>
    <x-header-section title="Аналитика" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <x-wrapper-content-loader>
            <x-slot name="header">
                <form method="POST" action="{{ $isDashboard ? route('d.analytics.show') : route('p.analytics.show') }}" class="w-full">
                    @csrf

                    <x-analytics-form
                        :months="$months"
                        :selectedMonth="$selected_month"
                        :users="$users"
                        :selectedUser="$selected_user"
                        :isDashboard="$isDashboard"
                    />
                </form>
            </x-slot>
        </x-wrapper-content-loader>
    </section>
</x-admin-layout>
