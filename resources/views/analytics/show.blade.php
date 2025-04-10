<x-admin-layout>
    <x-header-section title="Аналитика" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if (empty($selected_partner) && !Auth::user()->isAccessRightAdminOrHigher())
            <x-data-empty description="Не указан индентификатор филиала" />
        @else
            <x-wrapper-content-loader>
            <x-slot name="header">
                <div style="display: flex; gap: 1em">
                    <form method="POST" action="{{ $isDashboard ? route('d.analytics.show') : route('p.analytics.show') }}">
                        @csrf

                        <x-analytics-form
                            :months="$months"
                            :selectedMonth="$selected_month"
                            :partners="$partners"
                            :selectedPartner="$selected_partner"
                            :isDashboard="$isDashboard"
                        />
                    </form>

                    <form method="POST" action="{{ $isDashboard ? route('d.analytics.company') : route('p.analytics.company') }}" class="w-full">
                        @csrf

                        <input type="text" style="display: none;" value="{{ $selected_month }}" name="month" />
                        <input type="text" style="display: none;" value="{{ $selected_partner }}" name="company_id" />

                        <div class="flex justify-content-start mb-2">
                            <div class="col-3 ">
                                <button type="submit" class="primary icon solid fa-chart-area button-icon-fix" name="company" data-id="analytics-company" />
                            </div>
                        </div>
                    </form>
                </div>
            </x-slot>

            @if(empty($table) || empty($total))
                <x-data-empty />
            @else
                <div class="table-wrapper" style="font-size: 0.8em;">
                    <table>
                        <thead>
                            <tr>
                                <th>Имя</th>
                                <th class="text-center">Продажи<br>абонементов и сертификатов</th>
                                <th class="text-center">Валовая выручка</th>
                                <th class="text-center">Средний чек</th>
                                <th class="text-center">Доп.<br>услуги</th>
                                <th class="text-center">Продажи<br>(без сертов)</th>
                                <th class="text-center">Сумма<br>доп.услуг и продаж</th>
                                <th class="text-center">Возвращаемость<br>(будет позже)</th>
                                <th class="text-center">Всего отзывов<br>(из них 5)</th>
                                <th class="text-center">Заполняемость</th>
                                <th class="text-center">Новые клиенты</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach ($table as $one)
                            <tr>
                                <td>
                                    @if(empty($one["staff_id"]) || empty($one["company_id"]))
                                        {{ $one["name"] }}
                                    @else
                                        <a href="#" onclick="goToStaff({{ $one["staff_id"] }}, {{ $one["company_id"] }})" style="cursor: pointer">
                                            {{ $one["name"] }}
                                        </a>
                                    @endif

                                    <div class="small">
                                        @if(Str::lower($one["name"]) !== 'лист ожидания' )
                                            {{ $one["specialization"] }}
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ $one["loyalty"] }}</td>
                                <td class="text-center">{{ $one["income_total"] }}</td>
                                <td class="text-center">{{ $one["average_sum"] }}</td>
                                <td class="text-center">{{ $one["additional_services"] }}</td>
                                <td class="text-center">{{ $one["sales"] }}</td>
                                <td class="text-center">{{ $one["sum"] }}</td>
                                <td class="text-center"> - </td>
                                <td class="text-center">{{ $one["comments_total"] }} ({{ $one["comments_best"] }})</td>
                                <td class="text-center">{{ $one["fullness"] }}%</td>
                                <td class="text-center">{{ $one["new_client"] }}</td>
                            </tr>
                        @endforeach
                         <tr>
                            <td> </td>
                            <td class="text-center"><b>{{ $total["loyalty"] }} </b></td>
                            <td class="text-center"><b>{{ $total["income_total"] }} </b></td>
                            <td class="text-center"><b>{{ $total["average_sum"] }} </b></td>
                            <td class="text-center"><b>{{ $total["additional_services"] }} </b></td>
                            <td class="text-center"><b>{{ $total["income_goods"] }} </b></td>
                            <td class="text-center"> - </td>
                            <td class="text-center"> - </td>
                            <td class="text-center"><b>{{ $total["comments_total"] }} ({{ $total["comments_best"] }}) </b></td>
                            <td class="text-center"><b>{{ $total["fullness"] }}% </b></td>
                            <td class="text-center"><b>{{ $total["new_client"] }} </b></td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </x-wrapper-content-loader>
        @endif
    </section>

</x-admin-layout>

<script>
function goToStaff(staff_id, company_id) {
    const form = document.createElement('form');
    document.body.appendChild(form);

    form.target = "_blank"
    form.method = "post"
    form.action = `staff/chart`

    const data = [
        {
            name:  "_token",
            value: `<?php echo csrf_token(); ?>`
        },
        {
            name:  "staff_id",
            value: staff_id
        },
        {
            name:  "company_id",
            value: company_id
        },
        {
            name:  "month",
            value: `<?php echo $selected_month; ?>`
        },
    ]

    for(let val of data) {
        let input = document.createElement('input')
        input.type = "hidden"
        input.name = val.name
        input.value = val.value
        form.appendChild(input);
    }

    form.submit()
}
</script>
