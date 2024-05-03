<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'
import { ref, watch } from 'vue';

defineProps({
    title: String,
});

const sidebarState = ref(parseInt(localStorage.getItem('toggleSidebar')) == 1 ? true : false)

const toggleSidebar = (value) => {
    sidebarState.value = value
}

watch(() => sidebarState.value, (value) => {
    localStorage.setItem('toggleSidebar', value ? "1" : "0")
})

</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100 flex flex-col">
            <Navbar @toggle="toggleSidebar" class="flex-none">
                <!-- Page Heading -->
                <template #header>
                    <div v-if="$slots.header" class="uppercase font-thin">
                        <slot name="header" />
                    </div>
                    <div v-else v-text="title"></div>
                </template>
            </Navbar>
            <div class="relative flex-1 flex transition-all duration-500 ease-in-out">
                <Sidebar :toggle="sidebarState" />
                <!-- Page Content -->
                <main class="flex-1">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
