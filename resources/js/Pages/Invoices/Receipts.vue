<script lang="ts" setup>
import { iInvoice } from '../../types';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import { formatCurrency } from '../../helpers';
import SecondaryButton from '../../Components/SecondaryButton.vue';

const props = defineProps<{
    invoice: iInvoice
    show: boolean
}>()

const form = useForm<{
    id: number | null;
    particulars: string;
    method: string;
    amount: number | null;
    transaction_ref: string | null;
}>({
    id: null,
    particulars: "",
    method: "",
    amount: null,
    transaction_ref: null,

})

const swal = Swal.mixin({
    customClass: {
        confirmButton: "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150",
        cancelButton: "inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    },
    buttonsStyling: false
});

const emits = defineEmits(['close'])

const close = () => {
    form.reset()
    form.clearErrors()
    emits('close')
}

const downloadReceipt = (receiptId) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('invoices-receipt', receiptId))
    link.setAttribute('target', '_BLANK')
    link.click()
}

</script>
<template>
    <Modal :show="show">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="`Payment for Invoice Number #${invoice.id}`"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <pre v-text="invoice.receipts[0]"></pre>
        <div class="px-5 py-6 flex flex-col gap-2">
            <div v-for="{ id, particulars, amount, method, date, transaction_ref } in invoice.receipts"
                class="shadow rounded p-2 bg-gray-50">
                <div>
                    <div class="uppercase text-sm font-light">
                        <span v-text="id"></span>#
                        <span v-text="particulars"></span>
                    </div>
                    <div class="flex gap-1 text-xs font-medium text-gray-600">
                        <span v-text="formatCurrency(amount)"></span>
                        <span v-text="method"></span>
                        <span v-text="transaction_ref"></span>
                        <span v-text="date"></span>
                    </div>
                </div>
                <div>
                    <SecondaryButton @click="downloadReceipt(id)">Download</SecondaryButton>
                </div>
            </div>
        </div>
    </Modal>
</template>
