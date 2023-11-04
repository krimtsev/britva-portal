<x-dashboard-layout>
    <x-header-section title="Аналитика" />

    <section>
        <div class="flex justify-content-start ">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.analytics.show') }}" class="w-full">
                @csrf

                <x-analytics-form
                    :months="$months"
                    :users="$users"
                />
            </form>
        </div>
    </section>
</x-dashboard-layout>
