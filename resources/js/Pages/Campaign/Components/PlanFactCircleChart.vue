<template>
    <div class="chart h-full w-full">
        <canvas ref="canvas"/>
        <div class="inner">
            <p class="percent mb-2" :style="`color: ${deltaColorPercent}`">
                {{ percents }}%
            </p>
            <p class="amount">
                {{ $u.separateNumber($u.r(fact, 2)) }}{{ units }}
            </p>
        </div>
    </div>
</template>

<script>
import Chart from 'chart.js/auto';
import {n, r} from '@/utils';

export default {
    name: "PlanFactCircleChart",

    props: {
        data: {
            type: Object,
            required: true,
        },
        units: {
            type: String,
            required: false,
            default: '',
        },
    },

    data() {
        return {
            chart: null,
        };
    },

    computed: {
        plan() {
            let plan = r(n(this.data.summary.plan), 2);
            return plan !== 0 ? plan : this.fact;
        },
        fact() {
            return n(this.data.summary.fact);
        },
        percents() {
            return this.plan !== 0
                ? r(Math.abs(this.fact / this.plan) * 100, 0)
                : 100;
        },
        chartData() {
            let delta = Math.abs(this.plan - this.fact);
            return {
                labels: ['Факт', 'Разница', '-'],
                datasets: [
                    {
                        data: !(this.fact === 0 && this.plan === 0) ? [
                            this.isOver ? this.fact - delta : this.fact,
                            Math.min(delta, this.plan),
                            Math.max(this.isOver ? this.plan - delta : this.plan, 0),
                        ] : [
                            1, 0, 1
                        ],
                        backgroundColor: [
                            '#339AF0',
                            this.deltaColorChart,
                            '#DCE4EA73',
                        ],
                        borderWidth: 0,
                    },
                ],
            };
        },
        isOver() {
            return (this.percents >= 100);
        },
        deltaColorType() {
            let reverse = (this.data.reverse && this.fact > 0);
            return (reverse ? !this.isOver : this.isOver);
        },
        deltaColorChart() {
            return this.deltaColorType ? '#76D276' : '#FE7964';
        },
        deltaColorPercent() {
            return this.deltaColorType ? '#63BA79' : '#FE7964';
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

        Chart.overrides['doughnut'].plugins.legend = {display: false};
        Chart.overrides['doughnut'].plugins.tooltip = {enabled: false};

        this.chart = new Chart(ctx1, {
            type: 'doughnut',
            data: this.chartData,

            legend: {display: false},
            tooltip: {enabled: false},

            options: {
                rotation: 270,
                circumference: 180,
                cutout: 52,
                maintainAspectRatio: false,
            }
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
