<x-admin-layout>
    <x-header-section title="Аналитика" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if (isset($company_id_not_found) || (empty($selected_partner) && Auth::user()->isUser()))
            <x-data-empty description="Не указан индентификатор филиала" />
        @else
            <x-wrapper-content-loader>
                <x-slot name="header">
                    <form method="POST" action="{{ $isDashboard ? route('d.analytics.show') : route('p.analytics.show') }}" class="w-full">
                        @csrf

                        <x-analytics-form
                            :months="$months"
                            :selectedMonth="$selected_month"
                            :partners="$partners"
                            :selectedPartner="$selected_partner"
                            :isDashboard="$isDashboard"
                        />
                    </form>
                </x-slot>
            </x-wrapper-content-loader>
        @endif
    </section>
</x-admin-layout>
