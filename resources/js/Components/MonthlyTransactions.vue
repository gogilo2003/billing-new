<script lang="ts" setup>
import Chart, { ChartDataset } from "chart.js/auto"
import { usePage } from '@inertiajs/vue3';
import { onMounted, ref } from "vue";

const props = defineProps<{
    data: {
        labels: string[],
        series: ChartDataset[]
    }
}>()

const start = usePage().props.start
const end = usePage().props.end

const summary = ref()

const initChart = () => {
    new Chart(
        summary.value,
        {
            type: 'bar',
            data: {
                labels: props.data.labels,
                datasets: props.data.series
            }
        }
    );
}

onMounted(() => {
    initChart()
})

</script>
<template>
    <div class="text-xl font-extralight capitalize">Monthly Transactions</div>
    <div class="text-sm font-light capitalize">Transactions for the past 12 Months</div>
    <div class="w-full"><canvas ref="summary"></canvas></div>
    <div class="text-sm font-light capitalize">Transactions for the past 12 Months. Starting {{ start }} to {{ end }}
    </div>
</template>
