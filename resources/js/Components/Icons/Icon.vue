<script setup lang="ts">
import { computed } from 'vue';

const props = defineProps({
    type: String
})
</script>
<template>
    <span class="tim-icons" :class="type"></span>
</template>
