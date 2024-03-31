<script lang="ts" setup>
import { iInvoice } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { formatDate, formatCurrency } from '../../helpers';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps<{
    invoice: iInvoice
    show: boolean
}>()

const emits = defineEmits(['close'])

const close = () => {
    emits('close')
}

const downloadInvoice = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('invoices-download', props.invoice.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

</script>
<template>
    <Modal :show="show" max-width="3xl">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="`Invoice Number #${invoice.id}`"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <div class="px-5 py-6">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-2xl text-center uppercase mb-5">
                            Invoice
                        </h3>
                        <div class="text-center">
                            <div>
                                <h6 class="uppercase text-blue-500 mb-0 text-sm font-semibold">Invoice Number</h6>
                                <div class="mb-2">#{{ invoice.id }}</div>
                                <div class="inline-block">
                                    <img class="h-full w-full object-contain" :src="invoice.barcode" />
                                </div>
                            </div>
                            <div>
                                <h6 class="uppercase text-blue-500 mb-0 text-sm font-semibold">Date Issued:</h6>
                                <p class="mb-0">{{ formatDate(invoice.date) }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4 py-6">
                            <div class="col-md-5">
                                <h4 class="uppercase text-blue-500 mb-1 font-normal">
                                    Invoice For:
                                </h4>
                                <p class="text-sm font-light text-gray-700">
                                    <template v-if="invoice.client.name">
                                        {{ invoice.client.name }},<br />
                                    </template>
                                    <template v-if="invoice.client.address">
                                        {{ invoice.client.address }},<br />
                                    </template>
                                    <template v-if="invoice.client.email">
                                        {{ invoice.client.email }},<br>
                                    </template>
                                    <template v-if="invoice.client.phone">
                                        {{ invoice.client.phone }}
                                    </template>
                                </p>
                            </div>
                            <div class="col-md-7">
                                <div>
                                    <h4 class="uppercase text-blue-500 mb-1">
                                        FOR:
                                    </h4>
                                    <p class="text-sm font-light text-gray-700">{{ invoice.name }}</p>
                                </div>
                                <div v-if="invoice.ref">
                                    <h4 class="uppercase text-blue-500 mb-1">
                                        LSO/LPO/PO Number:
                                    </h4>
                                    <p class="text-sm font-light text-gray-700">{{ invoice.ref }}</p>
                                </div>
                            </div>
                            <div class="col-span-2">
                                <table class="border w-full">
                                    <thead
                                        class="bg-gradient-to-b from-blue-900 to-blue-950 text-gray-100 text-xs font-medium">
                                        <tr>
                                            <th class="px-4 py-3 w-8">#</th>
                                            <th class="px-4 py-3 text-left">PARTICULARS</th>
                                            <th class="px-4 py-3 text-right">PRICE</th>
                                            <th class="px-4 py-3 text-right">QUANTITY</th>
                                            <th class="px-4 py-3 text-right w-16">AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, i) in invoice.items" :key="i"
                                            class="text-sm font-light odd:bg-blue-50">
                                            <td class="px-4 py-3 border" valign="top">{{ i + 1 }}.</td>
                                            <td class="px-4 py-3 border">{{ item.particulars }}</td>
                                            <td class="text-right px-4 py-3 border" valign="top">
                                                {{ formatCurrency(item.price) }}
                                            </td>
                                            <td class="text-right px-4 py-3 border" valign="top">
                                                {{ item.quantity }}
                                            </td>
                                            <td class="text-right px-4 py-3 border" valign="top">
                                                {{ formatCurrency(parseFloat(item.price) * parseFloat(item.quantity)) }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot
                                        class="bg-gradient-to-b from-blue-900 to-blue-950 text-gray-100 font-semibold text-sm">
                                        <tr>
                                            <td class="text-right px-4 py-3" colspan="4">
                                                TOTAL
                                            </td>
                                            <td class="text-right px-4 py-3">
                                                {{ formatCurrency(invoice.amount) }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between items-center py-2">
                        <SecondaryButton @click.prevent="close" class="flex items-center gap-1">
                            <Icon type="icon-simple-remove" />
                            <span>Close</span>
                        </SecondaryButton>
                        <div class="flex gap-1 items-center">
                            <PrimaryButton @click.prevent="downloadInvoice" class="flex gap-1 items-center">
                                <Icon type="icon-cloud-download-93" />
                                <span>Delivery</span>
                            </PrimaryButton>
                            <PrimaryButton @click.prevent="downloadInvoice" class="flex items-center gap-1">
                                <Icon type="icon-cloud-download-93" />
                                <span>Invoice</span>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
