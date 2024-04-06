<script lang="ts" setup>
import { usePage } from '@inertiajs/vue3';

defineProps<{
    data: Object
}>()

const start = usePage().props.start
const end = usePage().props.end

</script>
<template>
    <div class="text-xl font-extralight capitalize">Monthly Transactions</div>
    <div class="text-sm font-light capitalize">Transactions for the past 12 Months</div>
    <pre v-text="data"></pre>
    <div class="text-sm font-light capitalize">Transactions for the past 12 Months. Starting {{ start }} to {{ end }}
    </div>
</template>
