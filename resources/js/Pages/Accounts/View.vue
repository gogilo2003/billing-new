<script lang="ts" setup>
import { iAccount } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { formatDate, formatCurrency } from '../../helpers';

const props = defineProps<{
    account: iAccount
    show: boolean
}>()

const emits = defineEmits(['close'])

const close = () => {
    emits('close')
}

const downloadAccount = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('accounts-download', props.account.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const downloadReceipt = (id: number) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('accounts-transactions-download', id))
    link.setAttribute('target', '_BLANK')
    link.click()
}
</script>
<template>
    <Modal :show="show" max-width="5xl">
        <div
            class="rounded-t-lg flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg">View Account Details</div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <div class="mx-3 my-4 p-3 shadow bg-white rounded">
            <div class="flex flex-col gap-2 text-sm">
                <div class="space-y-2 text-center mb-8">
                    <div class="text-gray-800 font-medium uppercase text-lg" v-text="account.client.name"></div>
                    <div v-text="`(${account.name} Account)`" class="text-gray-600 capitalize"></div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    <div class="flex flex-col gap-2">
                        <div v-if="account.client.email" class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <Icon type="icon-email-85"
                                class="text-gray-100 font-medium uppercase h-9 w-9 flex items-center justify-center p-1 rounded-full text-lg bg-gray-700" />
                            <span v-text="account.client.email" class="text-gray-600"></span>
                        </div>
                        <div v-if="account.client.phone" class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <Icon type="icon-mobile"
                                class="text-gray-100 font-medium uppercase h-9 w-9 flex items-center justify-center p-1 rounded-full text-lg bg-gray-700" />
                            <span v-text="account.client.phone" class="text-gray-600"></span>
                        </div>
                        <div v-if="account.client.box_no" class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <Icon type="icon-mobile"
                                class="text-gray-100 font-medium uppercase h-9 w-9 flex items-center justify-center p-1 rounded-full text-lg bg-gray-700" />
                            <div class="flex gap-1">
                                <span v-text="`P.O. Box ${account.client.box_no}`" class="text-gray-600"></span>
                                <span v-if="account.client.post_code" v-text="`- ${account.client.post_code}`"
                                    class="text-gray-600"></span>
                                <span v-text="`, ${account.client.town}`" class="text-gray-600"></span>
                            </div>
                        </div>
                        <div v-if="account.client.address" class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <Icon type="icon-square-pin"
                                class="text-gray-100 font-medium uppercase h-9 w-9 flex items-center justify-center p-1 rounded-full text-lg bg-gray-700" />
                            <span v-html="account.client.address" class="text-gray-600"></span>
                        </div>
                    </div>
                    <div class="flex flex-col gap-2 items-end">
                        <div class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <span class="font-medium">Amount:</span>
                            <span v-text="formatCurrency(account.amount)" class="text-gray-600 w-32 text-right"></span>
                        </div>
                        <div class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <span class="font-medium">Paid:</span>
                            <span v-text="formatCurrency(account.paid)" class="text-gray-600 w-32 text-right"></span>
                        </div>
                        <div class="flex flex-col md:flex-row gap-1 items-center ml-3">
                            <span class="font-medium">Balance:</span>
                            <span v-text="formatCurrency(account.balance)" class="text-gray-600 w-32 text-right"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg m-3">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th class="px-6 py-3">#</th>
                        <th scope="col" class="px-6 py-3 w-96">
                            Particulars
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Type
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Date
                        </th>
                        <th scope="col" class="px-6 py-3">
                            Amount
                        </th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="({ id, particulars, type, transaction_date, amount }, index) in account.transactions"
                        class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td v-text="index + 1" class="px-6 py-3"></td>
                        <td scope="row" class="px-6 py-4 font-medium text-gray-700 dark:text-white text-sm"
                            v-text="particulars">
                        </td>
                        <td class="px-6 py-4" v-text="type"></td>
                        <td class="px-6 py-4" v-text="formatDate(transaction_date)"></td>
                        <td class="px-6 py-4" v-text="formatCurrency(amount)"></td>
                        <td class="px-6 py-4">
                            <SecondaryButton v-if="type == 'CR'" class="flex gap-1 items-center"
                                @click="downloadReceipt(id)">
                                <Icon type="icon-cloud-download-93" /><span class="hidden md:inline-flex">Receipt</span>
                            </SecondaryButton>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="text-center py-4">
            <SecondaryButton @click="downloadAccount" class="flex gap-1 items-center">
                <Icon type="icon-cloud-download-93" /><span>Download</span>
            </SecondaryButton>
        </div>
    </Modal>
</template>
