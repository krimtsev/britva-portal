<x-admin-layout>
    <x-header-section title="Роялти" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <x-wrapper-content-loader>
                <x-slot name="header">
                    <div style="display: flex; gap: 1em">
                        <form method="POST" action="{{ route('d.royalty.show') }}">
                            @csrf

                            <x-analytics-form
                                :months="$months"
                                :selectedMonth="$selected_month"
                            />
                        </form>
                    </div>
                </x-slot>

                @if(empty($table))
                    <x-data-empty />
                @else
                    <div class="table-wrapper" style="font-size: 0.8em;">
                        <table class="alt royalty">
                            <thead>
                                <tr>
                                    <th>Сотрудник</th>
                                    <th>Валовая выручка</th>
                                    <th>Роялти</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($table as $one)
                                <tr>
                                    @if ($loop->last)
                                        <td> <b> Итого </b> </td>
                                        <td> <b> {{ $one["income_total"] }} </b> </td>
                                        <td> <b> {{ $one["sum"] }} </b> </td>
                                    @else
                                        <td> {{ $one["name"] }} </td>
                                        <td> {{ $one["income_total"] }} </td>
                                        <td> {{ $one["sum"] }} </td>
                                    @endif
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </x-wrapper-content-loader>
    </section>

</x-admin-layout>
