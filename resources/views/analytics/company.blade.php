<x-dashboard-layout>
    <x-header-section title="Аналитика - график компании" />

    <section>
        <div class="flex justify-content-start mb-2">
            <!-- Validation Errors -->
            <x-auth-validation-errors class="mb-4" :errors="$errors" />

            <form method="POST" action="{{ route('d.analytics.company') }}" class="w-full">
                @csrf

                <x-analytics-form
                    :months="$months"
                    :selectedMonth="$selected_month"
                    :users="$users"
                    :selectedUser="$selected_user"
                />
            </form>
        </div>

        <div>
            <div id="chartjs" class="canvas"></div>
        </div>
    </section>

</x-dashboard-layout>

<style>
.canvas {
    width: 100%;
}
</style>

<script>
    window.addEventListener('load', function () {
        const total_list = JSON.parse(`<?php echo $total_list; ?>`);
        const selected_period = JSON.parse(`<?php echo $selected_period; ?>`);

        const income_total = []
        const income_goods = []

        console.log(total_list)

        total_list.forEach(total => {
            income_total.push(total.income_total)
            income_goods.push(total.income_goods)
        })

        const colors = ["#B0CB1F","#8D3CAD", "#FEB019"];

        const options = {
            series: [
                {
                    name: "Оборот",
                    data: income_total
                },
                {
                    name: "Продажи",
                    data: income_goods
                }
            ],
            chart: {
                height: 400,
                type: "area",
                fontFamily: "Roboto, Arial, sans-serif",
                background: "#fff",
                selection: {
                    enabled: false
                },
                zoom: {
                    enabled: false
                },
                toolbar: {
                    show: true
                }
            },
            dataLabels: {
                enabled: true,
                    formatter: (val) => {
                    const str = "" + val + ""
                    return str.replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, "$1 ")
                },
                style: {
                    fontSize: "16px",
                    fontFamily: "Roboto, Arial, sans-serif",
                    fontWeight: "bold",
                    colors: ["#3D3D3D"]
                },
                background: {
                    enabled: false
                },
                dropShadow: {
                    enabled: false
                },
                offsetY: -13,
                offsetX: 5
            },
            stroke: {
                curve: "smooth",
                colors,
                width: 2
            },
            fill: {
                colors,
                opacity: 1,
                type: "gradient",
                gradient: {
                    shade: "light",
                    type: "vertical",
                    shadeIntensity: 0.5,
                    gradientToColors: ["#fff","#fff"],
                    inverseColors: false,
                    opacityFrom: 0.5,
                    opacityTo: 0.5
                }
            },
            yaxis: {
                show: false
            },
            xaxis: {
                type: "categtxory",
                categories: selected_period,
                axisBorder: {
                    show: true,
                    color: "#EBEBEB",
                    height: 1,
                    width: "100%",
                    offsetX: 0,
                    offsetY: 0
                },
                axisTicks: {
                    show: false
                },
                labels: {
                    show: true,
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
                show: true,
                    borderColor: "#D1D1D1",
                    strokeDashArray: 3,
                    position: "back",
                    xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: false
                    }
                },
                padding: {
                    right: 60,
                        left: 60
                }
            },
            legend: {
                labels: {
                    colors: undefined,
                    useSeriesColors: false
                },
                markers: {
                    fillColors: colors,
                }
            }
        }

        const chart = new ApexCharts(document.querySelector("#chartjs"), options);

        chart.render();
    })
</script>
