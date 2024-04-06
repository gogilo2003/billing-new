<script lang="ts" setup>
import AppLayout from '../Layouts/AppLayout.vue';
import TransactionsSummary from '../Components/TransactionsSummary.vue'
import MonthlyTransactions from '../Components/MonthlyTransactions.vue'

defineProps<{
    months: Object,
    summary: Object,
}>()
</script>

<template>
    <AppLayout title="Dashboard">
        <div class="py-2">
            <div class="sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-4 md:p-6">
                    <TransactionsSummary :data="summary" />
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg md:col-span-2 p-4 md:p-6">
                    <MonthlyTransactions :data="months" />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
