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
import Pay from "./Pay.vue";
import Receipts from "./Receipts.vue";

const props = defineProps({
    invoices: Object,
    client: Object,
    searchVal: String,
    notification: Object
})

const search = ref(props.searchVal)
const showInvoiceDialog = ref(false)
const showViewDialog = ref(false)
const showPayDialog = ref(false)
const showReceiptsDialog = ref(false)
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
    router.get(route('invoices'), { search: value }, {
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

const payInvoice = (INVOICE: iInvoice) => {
    showPayDialog.value = true
    invoice.value = INVOICE
}

const showReceipts = (INVOICE: iInvoice) => {
    showReceiptsDialog.value = true
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

const closePay = () => {
    showPayDialog.value = false
}

const closeReceipts = () => {
    showReceiptsDialog.value = false
}
</script>
<template>
    <Invoice :show="showInvoiceDialog" :invoice="invoice" :edit="edit" @close="close" />
    <View :show="showViewDialog" :invoice="invoice" @close="closeView" />
    <Pay :show="showPayDialog" :invoice="invoice" @close="closePay" />
    <Receipts :show="showReceiptsDialog" :invoice="invoice" @close="closeReceipts" />
    <AppLayout title="Invoices">
        <div class="py-2">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-white shadow-xl sm:rounded-lg">
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
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Paid</span>
                                        <span v-text="formatCurrency(invoice?.paid)"></span>
                                    </div>
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Balance</span>
                                        <span v-text="formatCurrency(invoice?.balance)"></span>
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
                            <div class="group relative">
                                <SecondaryButton class="flex items-center gap-2">
                                    <span>Tasks</span>
                                    <Icon type="icon-minimal-down" />
                                </SecondaryButton>
                                <div
                                    class="absolute w-40 top-[100%] right-0 z-10 bg-gray-50 grid grid-rows-[0] group-hover:grid-rows-[1fr] transition-[grid-template-rows] duration-300">
                                    <div class="overflow-hidden">
                                        <div class="flex-none flex flex-col gap-1">
                                            <div class="overflow-hidden">
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="showReceipts(invoice)">
                                                    <Icon type="icon-tag" />
                                                    <span class="hidden md:inline">Receipts</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="downloadInvoice(invoice)">
                                                    <Icon type="icon-cloud-download-93" />
                                                    <span class="hidden md:inline">Download</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="downloadDelivery(invoice)">
                                                    <Icon type="icon-delivery-fast" />
                                                    <span class="hidden md:inline">Delivery</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="payInvoice(invoice)">
                                                    <Icon type="icon-money-coins" />
                                                    <span class="hidden md:inline">Pay</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="viewInvoice(invoice)">
                                                    <Icon type="icon-paper" />
                                                    <span class="hidden md:inline">View</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="editInvoice(invoice)">
                                                    <Icon type="icon-pencil" />
                                                    <span class="hidden md:inline">Edit</span>
                                                </SecondaryButton>
                                                <SecondaryButton class="flex items-center gap-2 w-full justify-start"
                                                    @click="deleteInvoice(invoice.id)">
                                                    <Icon type="icon-trash-simple" />
                                                    <span class="hidden md:inline">Delete</span>
                                                </SecondaryButton>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <Paginator :items="invoices" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
