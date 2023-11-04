<x-profile-layout>
    <x-header-section title="Аналитика" />

    <section>
        <div class="table-wrapper" style="font-size: 0.8em">
            <table>
                <thead>
                <tr>
                    <!-- <th>ID</th> -->
                    <th>Сотрудник</th>
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
                            <div class="small">{{ $one["specialization"] }}</div>
                        </td>
                        <td>
                            <div class="center"> {{ $one["loyalty"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["income_total"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["average_sum"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["add_services"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["sales"] }} </div>
                        </td>
                        <td>
                            <div class="center">{{ $one["sum"] }} </div>
                        </td>
                        <td>
                            <div class="center"> -</div>
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
                <tr>
                    <!-- <td> </td> -->
                    <td> </td>
                    <td> <div class="center"></div></td>
                    <!-- <td> <div class="center"><b>{{ $total["loyalty"] }} </b></div></td> -->
                    <td> <div class="center"><b>{{ $total["income_total"] }} </b></div></td>
                    <td> <div class="center"><b>{{ $total["average_sum"] }} </b></div></td>
                    <td> <div class="center"><b>{{ $total["add_services"] }} </b></div></td>
                    <!-- <td> <div class="center"><b>{{ $total["sales"] }} </b></div></td> -->
                    <td> <div class="center"><b>{{ $total["income_goods"] }} </b></div></td>
                    <td> <div class="center"></div></td>
                    <td> <div class="center"></div></td>
                    <td> <div class="center"><b>{{ $total["comments_total"] }} ({{ $total["comments_best"] }}) </b></div></td>
                    <td> <div class="center"><b>{{ $total["fullnesss"] }}% </b></div></td>
                    <td> <div class="center"><b>{{ $total["new_client"] }} </b></div></td>
                </tr>
                </tbody>
            </table>
        </div>
    </section>

</x-profile-layout>
