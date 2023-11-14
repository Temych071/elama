<template>
    <div class="chart h-full w-full">
        <canvas ref="canvas"/>
    </div>
</template>

<script>
import {Chart} from 'chart.js';
import {periodFormat, r} from '@/utils';

export default {
    name: "PlanFactLinearChart",

    props: {
        data: {
            type: Object,
            required: true,
        },
    },

    data() {
        return {
            chart: null,
        };
    },

    computed: {
        chartData() {
            return {
                labels: this.data.linear.map((item) => periodFormat(item.date)),
                datasets: [
                    {
                        label: this.$t('campaigns.planfact.fieldsShort.' + this.data.title),
                        data: this.data.linear.map((item) => r(item.fact, 2)),
                        borderColor: this.data.color ?? '#A9ABBF',
                        fill: true,
                        tension: 0.2,
                        pointRadius: 2,
                        pointBackgroundColor: '#A9ABBF',
                    },
                    {
                        label: this.$t('campaigns.planfact.block.charts.plan-label'),
                        data: this.data.linear.map((item) => r(item.plan, 2)),
                        borderColor: this.data.color ?? '#DCE4EA',
                        fill: false,
                        tension: 0.1,
                        pointRadius: 2,
                        pointBackgroundColor: '#DCE4EA',
                        borderDash: [5, 5],
                    },
                ],
            };
        },
    },

    watch: {
        data: {
            handler() {
                this.chart.data = this.chartData;
                this.chart.update();
            },
            deep: true,
        },
    },

    mounted() {
        let ctx1 = this.$refs.canvas.getContext('2d');

        this.chart = new Chart(ctx1, {
            type: 'line',
            data: this.chartData,

            options: {
                maintainAspectRatio: false,
                borderWidth: 1,

                plugins: {
                    legend: {
                        display: false,
                    },
                },
                interaction: {
                    intersect: false,
                    mode: 'index',
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            display: false,
                        },
                        grid: {
                            drawBorder: false,
                            drawTicks: false,
                            color: '#F9F9F9',
                        },
                    },
                    x: {
                        ticks: {
                            display: false,
                        },
                        grid: {
                            drawTicks: false,
                            color: '#F9F9F9',
                        },
                    },
                },
            },
        });
    },
}
</script>

<style scoped lang="scss">
.chart {
    position: relative;

    canvas {
        position: absolute;
    }

    .inner {
        position: absolute;
        left: 50%;
        transform: translate(-50%, 0);
        bottom: .25rem;
        text-align: center;

        .percent {
            font-weight: bold;
            font-size: 1.5rem;
            line-height: 1.25rem;
        }

        .amount {
            @apply text-sm;
            color: #657188;
        }
    }
}
</style>
