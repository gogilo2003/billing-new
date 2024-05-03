<script setup lang="ts">

defineProps({
    type: String
})


</script>
<template>
    <span class="tim-icons" :class="type"></span>
</template>
