<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import Icon from '../Components/Icons/Icon.vue'
import { watch, onMounted } from 'vue';

const props = defineProps({
    toggle: Boolean
})


const links = [
    {
        name: 'dashboard',
        caption: 'Dashboard',
        icon: 'icon-chart-pie-36'
    },
    {
        name: 'clients',
        caption: 'Clients',
        icon: 'icon-single-02'
    },
    {
        name: 'accounts',
        caption: 'Accounts',
        icon: 'icon-bank'
    },
    {
        name: 'quotations',
        caption: 'Quotations',
        icon: 'icon-notes'
    },
    {
        name: 'invoices',
        caption: 'Invoices',
        icon: 'icon-paper'
    },
    // {
    //     name: 'messages',
    //     caption: 'Messaging',
    //     icon: 'icon-chat-33'
    // },
    // {
    //     name: 'products',
    //     caption: 'Products',
    //     icon: 'icon-bag-16'
    // },
    // {
    //     name: 'domains',
    //     caption: 'Domains',
    //     icon: 'icon-world'
    // },
    // {
    //     name: 'setup',
    //     caption: 'Setup',
    //     icon: 'icon-settings'
    // },
].map((item) => {
    return { ...item, active: route().current(item.name) }
})

</script>
<template>
    <div class="relative block flex-none transition[width] duration-500" :class="{ 'w-64': toggle, 'w-0': !toggle }">
        <div
            class="sticky top-[4.6rem] overflow-hidden min-h-[calc(100svh_-_8rem)] ml-6 my-2 bg-gradient-to-b to-secondary-default from-primary-default rounded-lg">
            <div class="px-3 py-1">
                <div class="flex items-center border-b border-gray-50/50 py-1">
                    <div class="flex-none h-12 w-12 p-1">
                        <img class="h-full w-full object-contain" src="/favicon.png" alt="">
                    </div>
                    <div class="text-white font-semibold uppercase">Billing</div>
                </div>
                <div class="pl-4 p-3 ">
                    <Link v-for="{ name, caption, icon, active } in links" :href="route(name)"
                        class="relative group flex gap-4 text-xs items-center text-gray-100 hover:text-white font-extralight uppercase transition duration-300"
                        :class="{ 'before:h-[6px] before:w-[6px] before:bg-gray-50 before:rounded-full before:absolute before:-left-5': active }">
                    <Icon style="font-size:20px; line-height: 30px;" :type="icon" />
                    <span class=" group-hover:text-white py-6">{{ caption }}</span>
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>
