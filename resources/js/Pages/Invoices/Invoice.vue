<script lang="ts" setup>
import { iInvoice, iInvoiceItem } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue';
import TextInput from '../../Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Modal from '../../Components/Modal.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import Swal from 'sweetalert2';
import PrimaryButton from '../../Components/PrimaryButton.vue';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css';
import { computed, watch, ref, onMounted } from 'vue';
import InvoiceItems from './InvoiceItems.vue';

const props = defineProps<{
    invoice: iInvoice
    show: boolean
    edit: boolean
}>()

const emits = defineEmits(['close'])

const swal = Swal.mixin({
    customClass: {
        confirmButton: "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150",
        cancelButton: "inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    },
    buttonsStyling: false
});

const form = useForm({
    id: null,
    account: null,
    ref: null,
    name: null,
    items: Array<iInvoiceItem>,
})

const accounts = computed(() => usePage().props.accounts)
const invoiceDialogTitle = computed(() => props.edit ? "Edit Invoice" : "New Invoice")
// const clients = computed(() => usePage().props.clients)

const close = () => {
    form.id = null
    form.name = null
    form.account = null
    form.items = null
    emits('close')
}


const submit = () => {
    if (props.edit) {
        form.patch(route('invoices-update', form.id), {
            only: ['invoices', 'notification', 'errors'],
            onSuccess: () => {
                swal.fire({
                    icon: 'success',
                    text: usePage().props?.notification?.success
                })
                cancel()
            },
            onError: () => {
                swal.fire({
                    icon: 'error',
                    text: usePage().props?.notification?.success ?? 'An error ocurred! Please check and try again'
                })
            }
        })
    } else {
        form.post(route('invoices-store'), {
            only: ['invoices', 'notification', 'errors'],
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: usePage().props?.notification?.success
                })
                cancel()
            },
            onError: () => {
                Swal.fire({
                    icon: 'error',
                    text: usePage().props?.notification?.success ?? 'An error ocurred! Please check and try again'
                })
            }
        })
    }
}

const cancel = () => {
    emits("close")
    form.id = null
    form.account = null
    form.name = null
    form.items = null
}

onMounted(() => {
    form.id = props.invoice?.id
    form.account = props.invoice?.account
    form.name = props.invoice?.name
    form.items = props.invoice?.items ?? []

})

watch(() => props.invoice, (value) => {
    form.id = props.invoice?.id
    form.account = props.invoice?.account
    form.name = props.invoice?.name
    form.items = props.invoice?.items ?? []
})

</script>
<template>
    <Modal :show="show" max-width="4xl">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="invoiceDialogTitle"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <form @submit.prevent="submit">
            <div class="px-6 py-3">
                <div class="mb-4 grid grid-cols-1 md:grid-cols-5 gap-4 md:gap-8">
                    <div class="md:col-span-3">
                        <InputLabel value="Account" />
                        <vSelect class="h-[2.6rem]" v-model="form.account" label="client" :options="accounts"
                            :reduce="account => account.id">
                            <template v-slot:option="{ client, name }">
                                <div class="py-2">
                                    <div class="uppercase" v-text="client"></div>
                                    <div class="text-sm font-light text-gray-700" v-text="name"></div>
                                </div>
                            </template>
                        </vSelect>
                        <InputError :message="form.errors.account" />
                    </div>
                    <div class="col-span-2">
                        <InputLabel value="REF(LPO Number/LSO Number/PO Number)" />
                        <TextInput class="w-full" v-model="form.ref" />
                        <InputError :message="form.errors.ref" />
                    </div>
                </div>
                <div class="mb-4">
                    <InputLabel value="Invoice Name" />
                    <TextInput class="w-full" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Invoice Details" />
                    <InvoiceItems v-model:value="form.items" />
                </div>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <PrimaryButton class="flex items-center gap-2">
                    <Icon type="icon-check-2" /><span>Save</span>
                </PrimaryButton>
                <SecondaryButton class="flex items-center gap-2" @click="close">
                    <Icon type="icon-simple-remove" /><span>Cancel</span>
                </SecondaryButton>
            </div>
        </form>
    </Modal>
</template>
<style scoped></style>
