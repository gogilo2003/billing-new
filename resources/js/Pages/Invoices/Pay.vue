<script lang="ts" setup>
import { iInvoice } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { formatDate, formatCurrency } from '../../helpers';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import TextInput from '../../Components/TextInput.vue';
import vSelect from 'vue-select';
import Swal from 'sweetalert2';
import { iReceipt } from '../../types';

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

const submit = () => {
    form.transform((data) => {
        return {
            invoice: props.invoice.id,
            ...data
        }
    }).post(route('invoices-pay', props.invoice.id), {
        only: ['invoices', 'notification', 'errors', 'receiptId'],
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => {
            close()
            swal.fire({
                icon: 'success',
                text: 'Payment received',
                didClose: () => {
                    downloadReceipt(usePage().props.receiptId)
                }
            })
        }
    })
}
</script>
<template>
    <Modal :show="show" max-width="lg">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="`Payment for Invoice Number #${invoice.id}`"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <div class="px-5 py-6">
            <form @submit.prevent="submit">
                <div class="mb-6 flex flex-col gap-1">
                    <InputLabel value="Description" />
                    <TextInput v-model="form.particulars" class="w-full" />
                    <InputError :message="form.errors.particulars" />
                </div>
                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-2">
                    <div>
                        <InputLabel value="Method" />
                        <vSelect :options="['Cash', 'MPesa', 'Cheque', 'Airtel Money', 'T-Cash', 'EFT']" type="number"
                            v-model="form.method" class="w-full border-none p-0" />
                        <InputError :message="form.errors.method" />
                    </div>
                    <div>
                        <InputLabel value="Transaction Reference" />
                        <TextInput v-model="form.transaction_ref" class="w-full" />
                        <InputError :message="form.errors.transaction_ref" />
                    </div>
                </div>
                <div class="mb-6 flex flex-col gap-1">
                    <InputLabel value="Amount" />
                    <TextInput type="number" v-model="form.amount" class="w-full" />
                    <InputError :message="form.errors.amount" />
                </div>
                <div class="flex justify-between">
                    <PrimaryButton>Submit</PrimaryButton>
                    <SecondaryButton type="button" @click="close">Cancel</SecondaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
