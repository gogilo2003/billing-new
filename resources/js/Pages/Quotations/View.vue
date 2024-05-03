<script lang="ts" setup>
import { iQuotation } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { formatDate, formatCurrency } from '../../helpers';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps<{
    quotation: iQuotation
    show: boolean
}>()

const emits = defineEmits(['close'])

const close = () => {
    emits('close')
}

const downloadQuotation = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('quotations-download', props.quotation.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

</script>
<template>
    <Modal :show="show" max-width="3xl">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg"
                v-text="`Quotation Number #${String(quotation.id).padStart(6, '0')}`"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <div class="px-5 py-6">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <h3 class="text-2xl text-center uppercase mb-5">
                            Quotation
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3">
                            <div class="text-center md:text-left">
                                <h6 class="uppercase text-blue-500 mb-0 text-sm font-semibold">Quotation Number</h6>
                                <div class="mb-2 text-sm font-light text-gray-700"
                                    v-text="`#${String(quotation.id).padStart(6, '0')}`"></div>
                            </div>
                            <div class="text-center">
                                <div class="inline-block h-16">
                                    <img class="h-full w-full object-contain" :src="quotation.qrcode" />
                                </div>
                            </div>
                            <div class="text-center md:text-right">
                                <h6 class="uppercase text-blue-500 mb-0 text-sm font-semibold">Date Issued:</h6>
                                <p class="mb-0">{{ formatDate(quotation.date) }}</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 py-6">
                            <div class="md:col-span-2">
                                <h4 class="uppercase text-blue-500 mb-1 font-normal">
                                    Quotation For:
                                </h4>
                                <p class="text-sm font-light text-gray-700">
                                    <template v-if="quotation.client.name">
                                        <p class="font-normal uppercase">{{ quotation.client.name }},</p>
                                    </template>
                                    <template v-if="quotation.client.address">
                                        {{ quotation.client.address }},<br />
                                    </template>
                                    <template v-if="quotation.client.email">
                                        {{ quotation.client.email }},<br>
                                    </template>
                                    <template v-if="quotation.client.phone">
                                        {{ quotation.client.phone }}
                                    </template>
                                </p>
                            </div>
                            <div class="">
                                <div>
                                    <h4 class="uppercase text-blue-500 mb-1">
                                        Prepared By:
                                    </h4>
                                    <p class="text-sm font-normal uppercase text-gray-700">{{ quotation.user.name }},
                                    </p>
                                    <p class="text-sm font-light text-gray-700">{{ quotation.user.phone }},</p>
                                    <p class="text-sm font-light text-gray-700">{{ quotation.user.email }}</p>
                                </div>
                                <div v-if="quotation.ref">
                                    <h4 class="uppercase text-blue-500 mb-1">
                                        LSO/LPO/PO Number:
                                    </h4>
                                    <p class="text-sm font-light text-gray-700">{{ quotation.ref }}</p>
                                </div>
                            </div>
                            <div class="md:col-span-3">
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
                                        <tr v-for="(item, i) in quotation.items" :key="i"
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
                                                {{ formatCurrency(quotation.amount) }}
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
                            <PrimaryButton @click.prevent="downloadQuotation" class="flex items-center gap-1">
                                <Icon type="icon-cloud-download-93" />
                                <span>Download</span>
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
