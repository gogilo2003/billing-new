<script lang="ts" setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Paginator from '../../Components/Paginator.vue'
import { formatCurrency } from '../../helpers'
import TextInput from '../../Components/TextInput.vue';
import { ref, watch } from 'vue';
import { debounce } from "lodash"
import { router } from '@inertiajs/vue3';
import Swal from 'sweetalert2'
import 'vue-select/dist/vue-select.css';
import { iInvoice } from '../../types'
import Invoice from "./Invoice.vue";
import View from "./View.vue";

const props = defineProps({
    invoices: Object,
    client: Object,
    searchVal: String,
    notification: Object
})

const search = ref(props.searchVal)
const showInvoiceDialog = ref(false)
const showViewDialog = ref(false)
const edit = ref(false)
const invoice = ref()


const swal = Swal.mixin({
    customClass: {
        confirmButton: "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150",
        cancelButton: "inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    },
    buttonsStyling: false
});

watch(() => search.value, debounce(value => {
    router.get(route('invoices'), { page: props?.invoices?.current_page, search: value }, {
        preserveScroll: true,
        preserveState: true,
        only: ['searchVal', 'invoices']
    })
}, 500))


const addInvoice = () => {
    showInvoiceDialog.value = true
    edit.value = false
}

const editInvoice = (INVOICE: iInvoice) => {
    showInvoiceDialog.value = true
    edit.value = true
    invoice.value = INVOICE
}

const viewInvoice = (INVOICE: iInvoice) => {
    showViewDialog.value = true
    invoice.value = INVOICE
}

const downloadInvoice = (invoice) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('invoices-download', invoice.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const downloadDelivery = (invoice) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('invoices-delivery', invoice.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const deleteInvoice = (id) => {
    router.delete(route('invoices-delete', id), {
        only: ['invoices', 'notification', 'errors'],
        onSuccess: () => {
            swal.fire({
                icon: 'success',
                text: props.notification.success
            })
        },
        onError: () => {
            swal.fire({
                icon: 'error',
                text: props.notification.danger || 'An error ocurred! please check your fields and try again'
            })
        }
    })
}

const close = () => {
    showInvoiceDialog.value = false
}

const closeView = () => {
    showViewDialog.value = false
}
</script>
<template>
    <Invoice :show="showInvoiceDialog" :invoice="invoice" :edit="edit" @close="close" />
    <View :show="showViewDialog" :invoice="invoice" @close="closeView" />
    <AppLayout title="Invoices">
        <div class="py-2">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-3 px-6 flex gap-3 md:gap-6 items-center">
                        <div class="flex-1">
                            <TextInput type="search" aria-placeholder="Search..." placeholder="Search..."
                                v-model="search" class="w-full max-w-96" />
                        </div>
                        <div class="flex-none flex gap-2">
                            <SecondaryButton @click="addInvoice"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-simple-add" /><span class="hidden md:inline pl-2">New Invoice</span>
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 p-6">
                        <div class="shadow border px-4 py-2 rounded flex flex-col 2xl:flex-row gap-1 2xl:gap-6 2xl:items-center justify-between"
                            v-for="invoice in invoices?.data">
                            <div class="flex-1">
                                <div class="font-semibold uppercase" v-text="invoice.name"></div>
                                <div class="flex flex-col md:flex-row md:divide-x text-xs text-slate-600">
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Amount</span>
                                        <span v-text="formatCurrency(invoice?.amount)"></span>
                                    </div>
                                    <div v-if="invoice.phone"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Phone:</span>
                                        <span v-text="invoice?.phone"></span>
                                    </div>
                                    <div v-if="invoice?.client?.name"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Client:</span>
                                        <span v-text="invoice?.client?.name" class="capitalize"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-none flex gap-2">
                                <SecondaryButton class="flex items-center gap-2" @click="downloadInvoice(invoice)">
                                    <Icon type="icon-cloud-download-93" />
                                    <span class="hidden md:inline">Download</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="downloadDelivery(invoice)">
                                    <Icon type="icon-cloud-download-93" />
                                    <span class="hidden md:inline">Delivery</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="viewInvoice(invoice)">
                                    <Icon type="icon-paper" />
                                    <span class="hidden md:inline">View</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="editInvoice(invoice)">
                                    <Icon type="icon-pencil" />
                                    <span class="hidden md:inline">Edit</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="deleteInvoice(invoice.id)">
                                    <Icon type="icon-trash-simple" />
                                    <span class="hidden md:inline">Delete</span>
                                </SecondaryButton>
                            </div>
                        </div>
                        <Paginator :items="invoices" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
