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
            <canvas id="chartjs" class="canvas"></canvas>
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
        const ctx = document.getElementById('chartjs');
        const total_list = JSON.parse(`<?php echo $total_list; ?>`);

        const datasets = []
        const labels = []

        const income_total = []
        const income_goods = []

        console.log(total_list)

        total_list.forEach(total => {
            labels.push(`${total.start_date} - ${total.end_date}`)

            income_total.push(total.income_total)
            income_goods.push(total.income_goods)
        })

        new Chart(ctx, {
            type: 'line',
            data: {
                labels,
                datasets: [
                    {
                        label: 'Оборот',
                        data: income_total,
                        borderWidth: 1,
                        fill: true,
                    },
                    {
                        label: 'Средний чек',
                        data: income_goods,
                        borderWidth: 1,
                        fill: true,
                    },
                ]
            },
            options: {
                responsive: true,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                stacked: false,
                plugins: {
                    title: {
                        display: true,
                        text: 'Chart.js Line Chart - Multi Axis'
                    }
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',

                        // grid line settings
                        grid: {
                            drawOnChartArea: false, // only want the grid lines for one axis to show up
                        },
                    },
                }
            },
        });
    })
</script>
