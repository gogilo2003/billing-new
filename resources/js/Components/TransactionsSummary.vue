<script lang="ts" setup>
defineProps<{
    data: Object
}>()
</script>
<template>
    <div class="text-xl font-extralight capitalize">Transactions Summary</div>
    <div class="text-sm font-light capitalize">The combined all time total transactions</div>
    <pre v-text="data"></pre>
    <div class="text-sm font-light capitalize">All time transactions</div>
</template>
