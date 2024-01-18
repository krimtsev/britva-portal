<x-admin-layout>
    <x-header-section title="Роялти" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if (empty($selected_user) && !Auth::user()->isAccessRightAdminOrHigher())
            <x-data-empty description="Не указан индентификатор филиала" />
        @else
            <x-wrapper-content-loader>
            <x-slot name="header">
                <div style="display: flex; gap: 1em">
                    <form method="POST" action="{{ route('d.royalty.show') }}">
                        @csrf

                        <x-analytics-form
                            :months="$months"
                            :selectedMonth="$selected_month"
                            :users="$users"
                            :selectedUser="$selected_user"
                        />
                    </form>
                </div>
            </x-slot>

            @if(empty($table))
                <x-data-empty />
            @else
                <div class="table-wrapper" style="font-size: 0.8em;">
                    <table class="alt">
                        <thead>
                            <tr>
                                <th>Сотрудник</th>
                                @foreach ($daysList as $date => $day)
                                    <th> {{ $day }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($table as $one)
                            <tr>
                                <td>
                                    @if(!empty($one["name"]) || empty($one["company_id"]))
                                        {{ $one["name"] }}
                                    @endif
                                </td>
                                @foreach ($daysList as $date => $day)
                                    <td style="text-align: center;">
                                        @if(!empty($one["data"]) && array_key_exists($date, $one["data"]))
                                            {{ $one["data"][$date] }}
                                        @endif
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach

                            <tr>
                                <td> <b>Всего:</b> </td>
                                @foreach ($total as $key => $value)
                                    <td style="text-align: center;">
                                        {{ $value }}
                                    </td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </x-wrapper-content-loader>
        @endif
    </section>

</x-admin-layout>
