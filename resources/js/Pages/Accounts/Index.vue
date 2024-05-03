<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
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
import { iAccount } from '../../types';
import Account from './Account.vue';
import View from './View.vue';

const props = defineProps({
    accounts: Object,
    searchVal: { type: String, default: "" },
    notification: Object,
    errors: Object,
    client: Object
})

const search = ref(props.searchVal)
const showAccountDialog = ref(false)
const showViewDialog = ref(false)
const edit = ref(false)
const account = ref<iAccount>()

const swal = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

watch(() => search.value, debounce(value => {
    router.get(route('accounts'), { page: props?.accounts?.current_page, search: value }, {
        preserveScroll: true,
        preserveState: true,
        only: ['searchVal', 'accounts']
    })
}, 500))


const addAccount = () => {
    showAccountDialog.value = true
    edit.value = false
}

const editAccount = (ACCOUNT: iAccount) => {
    showAccountDialog.value = true
    edit.value = true
    account.value = ACCOUNT
}

const viewAccount = (ACCOUNT: iAccount) => {
    showViewDialog.value = true
    account.value = ACCOUNT
}

const downloadAccounts = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('accounts-download'))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const downloadAccount = (account) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('accounts-download', account.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const deleteAccount = (id) => {
    router.delete(route('accounts-delete', id), {
        only: ['accounts', 'notification', 'errors'],
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
    showAccountDialog.value = false
}

const closeView = () => {
    showViewDialog.value = false
}

</script>

<template>
    <Account :show="showAccountDialog" :account="account" :edit="edit" @close="close" />
    <View :show="showViewDialog" :account="account" @close="closeView" />
    <AppLayout title="Accounts">
        <div class="py-2">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-3 px-6 flex gap-3 md:gap-6 items-center">
                        <div class="flex-1">
                            <TextInput type="search" aria-placeholder="Search..." placeholder="Search..."
                                v-model="search" class="w-full max-w-96" />
                        </div>
                        <div class="flex-none flex gap-2">
                            <SecondaryButton @click="downloadAccounts"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-cloud-download-93" /><span class="hidden md:inline pl-2">Download
                                    Accounts</span>
                            </SecondaryButton>
                            <SecondaryButton @click="addAccount"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-simple-add" /><span class="hidden md:inline pl-2">New Account</span>
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 p-6">
                        <div class="shadow border px-4 py-2 rounded flex flex-col md:flex-row gap-1 md:gap-6 md:items-center justify-between"
                            v-for="account in accounts?.data">
                            <div class="flex-1">
                                <div class="font-semibold uppercase" v-text="account.name"></div>
                                <div class="flex flex-col md:flex-row md:divide-x text-xs text-slate-600">
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Balance</span>
                                        <span v-text="formatCurrency(account?.balance)"></span>
                                    </div>
                                    <div v-if="account.phone"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Phone:</span>
                                        <span v-text="account?.phone"></span>
                                    </div>
                                    <div v-if="account.client.name"
                                        class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Client:</span>
                                        <span v-text="account?.client.name" class="capitalize"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-none flex gap-2">
                                <SecondaryButton class="flex items-center gap-2" @click="downloadAccount(account)">
                                    <Icon type="icon-cloud-download-93" />
                                    <span class="hidden md:inline">Download</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="viewAccount(account)">
                                    <Icon type="icon-paper" />
                                    <span class="hidden md:inline">View</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="editAccount(account)">
                                    <Icon type="icon-pencil" />
                                    <span class="hidden md:inline">Edit</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="deleteAccount(account.id)">
                                    <Icon type="icon-trash-simple" />
                                    <span class="hidden md:inline">Delete</span>
                                </SecondaryButton>
                            </div>
                        </div>
                        <Paginator :items="accounts" />
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
