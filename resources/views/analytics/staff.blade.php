<x-admin-layout>
    <x-header-section title="Аналитика - график по сотруднику" />

    <section>
        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        @if (empty($selected_partner) && !Auth::user()->isAccessRightAdminOrHigher())
            <x-data-empty description="Не указан индентификатор филиала" />
        @else
            <x-wrapper-content-loader>
            <x-slot name="header">
                <div style="display: flex; gap: 1em">
                    <form method="POST" action="{{ $isDashboard ? route('d.analytics.staff') : route('p.analytics.staff') }}">
                        @csrf

                        <x-analytics-form
                            :months="$months"
                            :selectedMonth="$selected_month"
                            :partners="$partners"
                            :selectedPartner="$selected_partner"
                            :isDashboard="$isDashboard"
                            :staffId="$staff_id"
                        />
                    </form>

                    <form method="POST" action="{{ $isDashboard ? route('d.analytics.show') : route('p.analytics.show') }}" class="w-full">
                        @csrf

                        <input type="text" style="display: none;" value="{{ $selected_month }}" name="month" />
                        <input type="text" style="display: none;" value="{{ $selected_partner }}" name="company_id" />

                        <div class="flex justify-content-start mb-2">
                            <div class="col-3 ">
                                <button type="submit" class="primary icon solid fa-th-list button-icon-fix"  name="company" data-id="analytics-back" />
                            </div>
                        </div>
                    </form>
                </div>
            </x-slot>

            @if(empty($table_list) || empty($total))
                <x-data-empty />
            @else
                <div class="row chart-wrapper">
                <div class="col-3">
                     <div class="">
                        <div class="header-section month text-center">
                            {{ $total["month"] }}
                        </div>
                        <div class="title-section">
                            <div class="text-center price">{{ $total["income_total"] }} ₽</div>
                            <div class="text-center title">Валовая выручка</div>
                        </div>
                        <div class="staff-section user">
                            <div class="text-center name">{{ $total["name"] }}</div>
                            <div class="text-center specialization">{{ $total["specialization"] }}</div>
                        </div>
                        <div class="staff-section fullness-section">
                            <div class="text-center fullness">{{ $total["fullness"] }} %</div>
                            <x-progress-bar :total="$total['fullness']" />
                            <div class="text-center fullness">Заполняемость</div>
                        </div>
                        {{-- <div class="staff-section fullness-section">
                            <div class="text-center fullness">(пока не работает)</div>
                            <x-progress-bar :total="100" />
                            <div class="text-center fullness">Возвращаемость</div>
                        </div>--}}
                    </div>
                    <div></div>
                </div>

                <div class="col-3">
                    <div class="header-section">
                        <span class="icon solid fas fa-users"></span>
                        <span class="header-section_icon">{{ $total["total_client"] }} - Всего клиентов</span>
                    </div>
                    <div class="title-section">
                        <div class="text-center price">{{ $total["additional_services"] }} ₽</div>
                        <div class="text-center title">Сумма доп. услуг</div>
                    </div>
                    <div class="chart-section">
                        <div id="chartjs_1" class="chart"></div>
                    </div>
                    <div class="legenda">
                        <div class="sqrt prev"> </div> - Прошлый период
                    </div>
                </div>

                <div class="col-3">
                    <div class="header-section">
                        <span class="icon solid fas fa-user-friends"></span>
                        <span class="header-section_icon">{{ $total["return_client"] }} - Постоянные клиенты</span>
                    </div>
                    <div class="title-section-other">
                        <div class="text-center price">{{ $total["average_sum"] }} ₽</div>
                        <div class="text-center title">Средний чек</div>
                    </div>
                    <div class="chart-section">
                        <div id="chartjs_2" class="chart"></div>
                    </div>
                    <div class="legenda">
                        <div class="sqrt current"> </div> - Отчетный месяц
                    </div>
                </div>

                <div class="col-3">
                    <div class="header-section">
                        <span class="icon solid fas fa-user-plus"></span>
                        <span class="header-section_icon">{{ $total["new_client"] }} - Новые клиенты</span>
                    </div>
                    <div class="title-section">
                        <div class="text-center price">{{ $total["sales"] }} ₽</div>
                        <div class="text-center title">Продаж за месяц</div>
                    </div>
                    <div class="chart-section">
                        <div id="chartjs_3" class="chart"></div>
                    </div>
                    <div class="legenda">

                    </div>
                </div>
            </div>
            @endif
        </x-wrapper-content-loader>
        @endif
    </section>
</x-admin-layout>

<style>
.canvas {
    width: 100%;
}
.price {
    font-size: 42px;
    font-weight: bold;
    color: white;
}
.title {
    font-size: 21px;
    color: white;
	font-style: italic;
}
.chart-wrapper {
    width: 1200px;
    height: 612px;
    background: #222;
    padding: 5px 5px 5px 0;
    font-family: "Roboto", serif;
    margin: 0 !important;
}
.chart-wrapper.row > * {
    padding: 0 0 0 5px !important;
}

.user {
	border-top: 1px solid white;
}
.title-section-other,
.title-section-other .price,
.title-section-other .title{
    background: #b7d900;
    color: #222;
}
.chart {
    display: flex;
    align-items: flex-start;
    margin-top: 5px;
}

.staff-section {
    display: flex;
    flex-direction: column;
    justify-content: center;
    color: white;
}
.name {
    font-size: 42px;
	font-family: "Roboto", serif;
	color: #b7d900;
}
.specialization {
    font-size: 18px;
	font-weight: lighter;
}
.header-section {
    padding: 5px;
    margin-bottom: 5px;
    background: white;
    color: #222;
}
.chart-section {
    height: 400px;
    overflow: hidden;
}

.fullness-section {
    margin-bottom: 5px;
	padding-top: 20px;
}

.fullness {
	font-size: 21px;
	font-weight: lighter;
	font-style: italic;
}

.month {
    background: #222;
    color: white;
    border-bottom: 1px solid white;
}

.header-section_icon {
	padding-left: 10px;
}

.legenda {
    color: #222;
    display: flex;
    align-items: center;
    padding: 14px 14px 14px 28px;
    background: white;
    height: 54px;
}
.legenda > .sqrt {
    width: 20px;
    height: 20px;
    margin-right: 5px;
}

.legenda > .sqrt.current {
    background: #222;
}

.legenda > .sqrt.prev {
    background: #aaa;
}

</style>

<script>
    window.addEventListener('load', function () {
        const table_list = JSON.parse(`<?php echo $table_list; ?>`);
        const selected_period = JSON.parse(`<?php echo $selected_period; ?>`);

        const additional_services = []
        const average_sum = []
        const sales = []

        table_list.forEach(staff => {
            additional_services.push(staff.additional_services)
            average_sum.push(staff.average_sum)
            sales.push(staff.sales)
        })

        const colors = additional_services.map((val) => "#aaa")
        colors[additional_services.length-1] = "#222"

        const options = {
            series: [],
            chart: {
                height: 400,
                type: "bar",
                fontFamily: "Roboto, Arial, sans-serif",
                background: "#fff",
                selection: {
                    enabled: false
                },
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: false
                }
            },
            dataLabels: {
                enabled: true,
                formatter: (val) => {
                    const str = "" + val + ""
                    return str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 ")
                },
                textAnchor: 'middle',
                style: {
                    fontSize: "16px",
                    fontFamily: "Roboto, sans-serif",
                    fontWeight: "bold",
                    colors
                },
                background: {
                    enabled: false
                },
                dropShadow: {
                    enabled: false
                },
                offsetY: -40,
                //offsetX: 0
            },
            colors: colors,
            plotOptions: {
                bar: {
                    horizontal: false,
                    columnWidth: '55%',
                    distributed: true,
                    dataLabels: {
                        position: 'top'
                    }
                }
            },
            stroke: {
                curve: "smooth",
                colors,
                width: 0
            },
            fill: {
                colors,
                opacity: 1,
            },
            yaxis: {
                show: false,
                tickAmount: 6,
            },
            xaxis: {
                type: "categtxory",
                categories: selected_period,
                axisBorder: {
                    show: false,
                    color: "#222",
                    height: 1,
                    width: "100%",
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: false,
                    offsetX: 3,
                    style: {
                    colors: "#111",
                        fontSize: "14px",
                        fontFamily: "Roboto, Arial, sans-serif",
                        fontWeight: 400,
                    }
                }
            },
            bar: {
                dataLabels: {
                    position: "top"
                }
            },
            markers: {
                size: 7,
                colors: "#ffffff",
                strokeColors: colors,
                strokeWidth: 2,
                strokeOpacity: 1,
                fillOpacity: 1,
                shape: "circle",
                hover: {
                    size: undefined,
                    sizeOffset: 0
                }
            },
            tooltip: {
                enabled: false
            },
            grid: {
                show: false,
            },
            legend: {
                show: false,
            },
            states: {
                normal: {
                    filter: {
                        type: 'none',
                    }
                },
                hover: {
                    filter: {
                        type: 'none',
                    }
                },
                active: {
                    filter: {
                        type: 'none',
                    }
                }
            }
        }

        options.series = [{
            name: "Доп. услуги",
            data: additional_services
        }]
        options.xaxis.categories = ["Доп. услуги"]

        const chart_1 = new ApexCharts(document.querySelector("#chartjs_1"), options);
        chart_1.render();

        options.series = [{
            name: "Средний чек",
            data: average_sum
        }]
        options.xaxis.categories = ["Средний чек"]

        const chart_2 = new ApexCharts(document.querySelector("#chartjs_2"), options);
        chart_2.render();

        options.series = [{
            name: "Продажи",
            data: sales
        }]
        options.xaxis.categories = ["Продажи"]

        const chart_3 = new ApexCharts(document.querySelector("#chartjs_3"), options);
        chart_3.render();
    })
</script>
