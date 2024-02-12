<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import Banner from '@/Components/Banner.vue';
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'

defineProps({
    title: String,
});

</script>

<template>
    <div>

        <Head :title="title" />

        <Banner />

        <div class="min-h-screen bg-gray-100 flex flex-col">
            <Navbar class="flex-none">
                <!-- Page Heading -->
                <template #header>
                    <div v-if="$slots.header" class="uppercase font-thin">
                        <slot name="header" />
                    </div>
                    <div v-else v-text="title"></div>
                </template>
            </Navbar>
            <div class="relative flex-1">
                <Sidebar />
                <!-- Page Content -->
                <main class="ml-3 md:ml-64 mr-3">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>
