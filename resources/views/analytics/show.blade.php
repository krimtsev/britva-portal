<x-profile-layout>
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

        <div class="table-wrapper" style="font-size: 0.8em">
            <table>
                <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th style="width: 160px;">Сотрудник</th>
                    <th>Лояльность</th>
                    <th>Оборот</th>
                    <th>Средний чек</th>
                    <th>Доп. услуги</th>
                    <th>Продажи</th>
                    <th>Сумма</th>
                    <th>Возвращаемость</th>
                    <th>Всего отзывов (из них 5)</th>
                    <th>Заполняемость</th>
                    <th>Новые клиенты</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($table as $one)
                    <tr>
                        <!-- <td> {{ $one["id"] }} </td> -->
                        <td>
                            <div>{{ $one["name"] }} </div>
                            <div class="small">
                                @if(Str::lower($one["name"]) !== 'лист ожидания' )
                                    {{ $one["specialization"] }}
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["loyalty"] }}</div>
                        </td>
                        <td>
                            <div class="center">{{ $one["income_total"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["average_sum"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["additional_services"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["sales"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["sum"] }} </div>
                        </td>
                        <td>
                            <div class="center"> - </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["comments_total"] }} ({{ $one["comments_best"] }}) </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["fullnesss"] }}% </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["new_client"] }} </div>
                        </td>
                    </tr>
                @endforeach
                @if(!empty($total))
                    <tr>
                        <td> </td>
                        <td> <div class="center"><b>{{ $total["loyalty"] }} </b></div></td>
                        <td> <div class="center"><b>{{ $total["income_total"] }} </b></div></td>
                        <td> <div class="center"><b>{{ $total["average_sum"] }} </b></div></td>
                        <td> <div class="center"><b>{{ $total["additional_services"] }} </b></div></td>
                        <td> <div class="center"><b>{{ $total["income_goods"] }} </b></div></td>
                        <td> <div class="center"> - </div></td>
                        <td> <div class="center"> - </div></td>
                        <td> <div class="center"><b>{{ $total["comments_total"] }} ({{ $total["comments_best"] }}) </b></div></td>
                        <td> <div class="center"><b>{{ $total["fullnesss"] }}% </b></div></td>
                        <td> <div class="center"><b>{{ $total["new_client"] }} </b></div></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </section>

</x-profile-layout>
