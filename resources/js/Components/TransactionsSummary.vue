<script lang="ts" setup>
import Chart, { ChartDataset } from 'chart.js/auto';
import { onMounted, ref } from 'vue';
const props = defineProps<{
    data: {
        labels: string[],
        series: ChartDataset[],
    }
}>()

const summary = ref()

const initChart = () => {
    new Chart(
        summary.value,
        {
            type: 'pie',
            data: {
                labels: props.data.labels,
                datasets: [
                    {
                        label: 'Transactions Summary',
                        data: props.data.series
                    }
                ]
            }
        }
    );
}

onMounted(() => {
    initChart()
})
</script>
<template>
    <div class="text-xl font-extralight capitalize">Transactions Summary</div>
    <div class="text-sm font-light capitalize">The combined all time total transactions</div>
    <div class="w-full"><canvas ref="summary"></canvas></div>
    <pre v-text="data"></pre>
    <div class="text-sm font-light capitalize">All time transactions</div>
</template>
