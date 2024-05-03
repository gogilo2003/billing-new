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
import { iQuotation } from '../../types'
import Quotation from "./Quotation.vue";
import View from "./View.vue";

const props = defineProps({
    quotations: Object,
    client: Object,
    searchVal: String,
    notification: Object
})

const search = ref(props.searchVal)
const showQuotationDialog = ref(false)
const showViewDialog = ref(false)
const edit = ref(false)
const quotation = ref<iQuotation>({
    id: null,
    description: "",
    validity: 30,
    sub_total: 0,
    tax: 0,
    amount: 0,
    barcode: "",
    qrcode: "",
    client: null,
    user: null,
    date: null,
    items: [],
})


const swal = Swal.mixin({
    customClass: {
        confirmButton: "inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 transition ease-in-out duration-150",
        cancelButton: "inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150"
    },
    buttonsStyling: false
});

watch(() => search.value, debounce(value => {
    router.get(route('quotations'), { page: props?.quotations?.current_page, search: value }, {
        preserveScroll: true,
        preserveState: true,
        only: ['searchVal', 'quotations']
    })
}, 500))


const addQuotation = () => {
    quotation.value = {
        id: null,
        description: "",
        validity: 30,
        sub_total: 0,
        tax: 0,
        amount: 0,
        barcode: "",
        qrcode: "",
        client: null,
        user: null,
        date: null,
        items: [],
    }
    showQuotationDialog.value = true
    edit.value = false
}

const editQuotation = (QUOTATION: iQuotation) => {
    console.log(QUOTATION.client);

    quotation.value = QUOTATION
    showQuotationDialog.value = true
    edit.value = true
}

const viewQuotation = (QUOTATION: iQuotation) => {
    showViewDialog.value = true
    quotation.value = QUOTATION
}

const downloadQuotation = (quotation) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('quotations-download', quotation.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const deleteQuotation = (id) => {
    router.delete(route('quotations-destroy', id), {
        only: ['quotations', 'notification', 'errors'],
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
    showQuotationDialog.value = false
}

const closeView = () => {
    showViewDialog.value = false
}
</script>
<template>
    <Quotation :show="showQuotationDialog" :quotation="quotation" :edit="edit" @close="close" />
    <View :show="showViewDialog" :quotation="quotation" @close="closeView" />
    <AppLayout title="Quotations">
        <div class="py-2">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-3 px-6 flex gap-3 md:gap-6 items-center">
                        <div class="flex-1">
                            <TextInput type="search" aria-placeholder="Search..." placeholder="Search..."
                                v-model="search" class="w-full max-w-96" />
                        </div>
                        <div class="flex-none flex gap-2">
                            <SecondaryButton @click="addQuotation"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-simple-add" /><span class="hidden md:inline pl-2">New Quotation</span>
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 p-6">
                        <div class="shadow border px-4 py-2 rounded flex flex-col 2xl:flex-row gap-1 2xl:gap-6 2xl:items-center justify-between"
                            v-for="quotation in quotations?.data">
                            <div class="flex-1">
                                <div class="font-semibold uppercase" v-text="quotation.client?.name"></div>
                                <div v-text="quotation?.description" class="capitalize text-xs text-slate-600 mb-2">
                                </div>
                                <div class="flex flex-col md:flex-row md:divide-x text-xs text-slate-600">
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Amount</span>
                                        <span v-text="formatCurrency(quotation?.amount)"></span>
                                    </div>
                                    <div v-if="quotation?.client?.phone"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Phone:</span>
                                        <span v-text="quotation?.client.phone"></span>
                                    </div>
                                    <div v-if="quotation?.client?.email"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Email:</span>
                                        <span v-text="quotation?.client.email"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-none flex gap-2">
                                <SecondaryButton class="flex items-center gap-2" @click="downloadQuotation(quotation)">
                                    <Icon type="icon-cloud-download-93" />
                                    <span class="hidden md:inline">Download</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="viewQuotation(quotation)">
                                    <Icon type="icon-paper" />
                                    <span class="hidden md:inline">View</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="editQuotation(quotation)">
                                    <Icon type="icon-pencil" />
                                    <span class="hidden md:inline">Edit</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="deleteQuotation(quotation.id)">
                                    <Icon type="icon-trash-simple" />
                                    <span class="hidden md:inline">Delete</span>
                                </SecondaryButton>
                            </div>
                        </div>
                        <Paginator :items="quotations" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
