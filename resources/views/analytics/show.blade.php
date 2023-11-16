<x-dashboard-layout>
    <x-header-section title="Аналитика" />

    <section>
        <div class="flex justify-content-start mb-2">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.analytics.show') }}" class="w-full">
                @csrf

                <x-analytics-form
                    :months="$months"
                    :selectedMonth="$selected_month"
                    :users="$users"
                    :selectedUser="$selected_user"
                />
            </form>
        </div>
	
	<style>
		
	</style>
	
        <div class="table-wrapper" style="font-size: 0.8em;">
            <table>
                <thead>
                <tr>
                    <th>Имя</th>
                    <th class="text-center">Лояльность</th>
                    <th class="text-center">Оборот</th>
                    <th class="text-center">Средний чек</th>
                    <th class="text-center">Доп. услуги</th>
                    <th class="text-center">Продажи</th>
                    <th class="text-center">Сумма продаж</th>
                    <th class="text-center">Возвращаемость</th>
                    <th class="text-center">Всего отзывов<br>(из них 5)</th>
                    <th class="text-center">Заполняемость</th>
                    <th class="text-center">Новые клиенты</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($table as $one)
                    <tr>
                        <td>
                            <div>{{ $one["name"] }} </div>
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
                        <td class="text-center">{{ $one["fullnesss"] }}%</td>
                        <td class="text-center">{{ $one["new_client"] }}</td>
                    </tr>
                @endforeach
                @if(!empty($total))
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
                        <td class="text-center"><b>{{ $total["fullnesss"] }}% </b></td>
                        <td class="text-center"><b>{{ $total["new_client"] }} </b></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </section>

</x-dashboard-layout>
