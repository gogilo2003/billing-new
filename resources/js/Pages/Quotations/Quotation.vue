<script lang="ts" setup>
import { iQuotation, iQuotationItem, iClient } from '../../types';
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
import { computed, watch, ref, onMounted, watchEffect } from 'vue';
import QuotationItems from './QuotationItems.vue';

const props = defineProps<{
    quotation: iQuotation
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

const form = useForm<{
    id: number | null
    client: number | null
    validity: number | null
    description: string | null
    notes: string | null
    items: iQuotationItem[]
}>({
    id: null,
    client: null,
    description: null,
    validity: null,
    notes: null,
    items: [],
})

const quotationDialogTitle = computed(() => props.edit ? "Edit Quotation" : "New Quotation")
const clients = computed(() => usePage().props.clients)

const close = () => {
    emits('close')
}


const submit = () => {
    if (props.edit) {
        form.patch(route('quotations-update', form.id), {
            only: ['quotations', 'notification', 'errors'],
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
        form.post(route('quotations-store'), {
            only: ['quotations', 'notification', 'errors'],
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
    }
}

const cancel = () => {
    emits("close")
    form.id = null
    form.client = null
    form.description = null
    form.notes = null
    form.items = null
}

watchEffect(() => {
    () => props.quotation

    form.id = props.quotation?.id
    form.client = props.quotation?.client?.id
    form.description = props.quotation?.description
    form.notes = props.quotation?.notes
    form.validity = props.quotation?.validity
    form.items = props.quotation?.items ?? []
})

</script>
<template>
    <Modal :show="show" max-width="4xl">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="quotationDialogTitle"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <form @submit.prevent="submit">
            <div class="px-6 py-3">
                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="mb-4 md:col-span-2">
                        <InputLabel value="Client" />
                        <vSelect class="h-[2.6rem]" v-model="form.client" label="name" :options="clients"
                            :reduce="client => client.id"></vSelect>
                        <InputError :message="form.errors.client" />
                    </div>
                    <div class="mb-4">
                        <InputLabel value="Validity" />
                        <TextInput class="w-full" v-model="form.validity" type="number" />
                        <InputError :message="form.errors.validity" />
                    </div>
                </div>
                <div class="mb-4">
                    <InputLabel value="Description" />
                    <TextInput class="w-full" v-model="form.description" />
                    <InputError :message="form.errors.description" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Quotation Details" />
                    <QuotationItems v-model:value="form.items" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Additional Notes" />
                    <textarea
                        class="border-gray-300 focus:border-primary-500 focus:ring-primary-500 rounded-md shadow-sm w-full"
                        v-model="form.notes"></textarea>
                    <InputError :message="form.errors.notes" />
                </div>
            </div>
            <div class="px-6 py-3 flex justify-between items-center">
                <SecondaryButton class="flex items-center gap-2" @click="close">
                    <Icon type="icon-simple-remove" /><span>Cancel</span>
                </SecondaryButton>
                <PrimaryButton v-if="form.items.length" class="flex items-center gap-2"
                    :class="{ 'opacity-30': form.processing }" :disabled="form.processing">
                    <Icon type="icon-check-2" /><span>Save</span>
                </PrimaryButton>
            </div>
        </form>
    </Modal>
</template>
