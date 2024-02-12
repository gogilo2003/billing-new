<script setup lang="ts">
import AppLayout from '../../Layouts/AppLayout.vue';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Paginator from '../../Components/Paginator.vue'
import { formatCurrency } from '../../helpers'
import TextInput from '../../Components/TextInput.vue';
import { ref, watch } from 'vue';
import { debounce } from "lodash"
import { router, useForm } from '@inertiajs/vue3';
import Modal from '../../Components/Modal.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import Swal from 'sweetalert2'
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    clients: Object,
    searchVal: { type: String, default: "" },
    notification: Object,
    errors: Object
})

const form = useForm({
    "id": null,
    "name": "",
    "phone": "",
    "email": "",
    "box_no": "",
    "post_code": "",
    "town": "",
    "address": "",
})

const search = ref(props.searchVal)
const showClientDialog = ref(false)
const edit = ref(false)
const clientDialogTitle = ref("New Client")

watch(() => search.value, debounce(value => {
    router.get(route('clients'), { page: props.clients.current_page, search: search.value })
}, 500))


const addClient = () => {
    showClientDialog.value = true
    clientDialogTitle.value = "New Client"
    edit.value = false
}

const editClient = (client) => {
    console.log('edit');

    showClientDialog.value = true
    clientDialogTitle.value = "Edit Client"
    edit.value = true
    form.id = client.id
    form.name = client.name
    form.phone = client.phone
    form.email = client.email
    form.box_no = client.box_no
    form.post_code = client.post_code
    form.town = client.town
    form.address = client.address
}

const close = () => {
    showClientDialog.value = false
    clientDialogTitle.value = "New Client"
    edit.value = false
    form.id = null
    form.name = ""
    form.phone = ""
    form.email = ""
    form.box_no = ""
    form.post_code = ""
    form.town = ""
    form.address = ""
    form.errors.id = null
    form.errors.name = ""
    form.errors.phone = ""
    form.errors.email = ""
    form.errors.box_no = ""
    form.errors.post_code = ""
    form.errors.town = ""
    form.errors.address = ""
}

const submit = () => {
    if (edit.value) {
        form.patch(route('clients-update'), {
            only: ['clients', 'notification', 'errors'],
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: props.notification.success
                })
                close()
            },
            onError: () => {
                Swal.fire({
                    icon: 'error',
                    text: props.notification.danger || 'An error ocurred! please check your fields and try again'
                })
            }
        })
    } else {
        form.post(route('clients-store'), {
            only: ['clients', 'notification', 'errors'],
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: props.notification.success
                })
                close()
            },
            onError: () => {
                Swal.fire({
                    icon: 'error',
                    text: props.notification.danger || 'An error ocurred! please check your fields and try again'
                })
            }
        })

    }
}

const downloadClients = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('clients-clients-download'))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const downloadClient = (client) => {
    let link = document.createElement('a')
    link.setAttribute('href', route('clients-download', client.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

const deleteClient = (id) => {
    router.delete(route('clients-delete', id), {
        only: ['clients', 'notification', 'errors'],
        onSuccess: () => {
            Swal.fire({
                icon: 'success',
                text: props.notification.success
            })
            close()
        },
        onError: () => {
            Swal.fire({
                icon: 'error',
                text: props.notification.danger || 'An error ocurred! please check your fields and try again'
            })
        }
    })
}
</script>

<template>
    <Modal :show="showClientDialog">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="clientDialogTitle"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <form @submit.prevent="submit">
            <div class="px-6 py-3">
                <div class="mb-4">
                    <InputLabel value="Client Name" />
                    <TextInput class="w-full" v-model="form.name" />
                    <InputError :message="form.errors.name" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Phone Number" />
                    <TextInput class="w-full" v-model="form.phone" />
                    <InputError :message="form.errors.phone" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Email" />
                    <TextInput class="w-full" v-model="form.email" />
                    <InputError :message="form.errors.email" />
                </div>
                <div class="mb-4 grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <InputLabel value="Box No" />
                        <TextInput class="w-full" v-model="form.box_no" />
                        <InputError :message="form.errors.box_no" />
                    </div>
                    <div>
                        <InputLabel value="Post Code" />
                        <TextInput class="w-full" v-model="form.post_code" />
                        <InputError :message="form.errors.post_code" />
                    </div>
                    <div>
                        <InputLabel value="Town" />
                        <TextInput class="w-full" v-model="form.town" />
                        <InputError :message="form.errors.town" />
                    </div>
                </div>
                <div class="mb-4">
                    <InputLabel value="Physical Address" />
                    <TextInput class="w-full" v-model="form.address" />
                    <InputError :message="form.errors.address" />
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
    <AppLayout title="Clients">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Clients
            </h2>
        </template>

        <div class="py-2">
            <div class="sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <div class="py-3 px-6 flex gap-3 md:gap-6 items-center">
                        <div class="flex-1">
                            <TextInput type="search" aria-placeholder="Search..." placeholder="Search..." v-model="search"
                                class="w-full max-w-96" />
                        </div>
                        <div class="flex-none flex gap-2">
                            <SecondaryButton @click="downloadClients"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-cloud-download-93" /><span class="hidden md:inline pl-2">Download
                                    Clients</span>
                            </SecondaryButton>
                            <SecondaryButton @click="addClient"
                                class="flex items-center justify-center md:justify-start md:gap-4 h-10 w-10 md:px-4 md:py-2 md:h-auto md:w-auto md:block">
                                <Icon type="icon-simple-add" /><span class="hidden md:inline pl-2">New Client</span>
                            </SecondaryButton>
                        </div>
                    </div>
                    <div class="flex flex-col gap-3 p-6">
                        <div class="shadow border px-4 py-2 rounded flex flex-col md:flex-row gap-1 md:gap-6 md:items-center justify-between"
                            v-for="client in clients.data">
                            <div class="flex-1">
                                <div class="font-semibold uppercase" v-text="client.name"></div>
                                <div class="flex flex-col md:flex-row md:divide-x text-xs text-slate-600">
                                    <div v-if="client.phone" class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Phone:</span>
                                        <span v-text="client?.phone"></span>
                                    </div>
                                    <div v-if="client.email" class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Email:</span>
                                        <span v-text="client?.email"></span>
                                    </div>
                                    <div class="md:px-2 first:pl-0 last:pr-0 flex items-center gap-1">
                                        <span class="font-bold">Balance</span>
                                        <span v-text="formatCurrency(client?.balance)"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex-none flex gap-2">
                                <SecondaryButton class="flex items-center gap-2" @click="downloadClient(client)">
                                    <Icon type="icon-cloud-download-93" />
                                    <span class="hidden md:inline">Download</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="editClient(client)">
                                    <Icon type="icon-pencil" />
                                    <span class="hidden md:inline">Edit</span>
                                </SecondaryButton>
                                <SecondaryButton class="flex items-center gap-2" @click="deleteClient(client.id)">
                                    <Icon type="icon-trash-simple" />
                                    <span class="hidden md:inline">Delete</span>
                                </SecondaryButton>
                            </div>
                        </div>
                        <Paginator :items="clients" />
                    </div>
                    <!-- <pre v-text="clients.data[0]"></pre> -->
                </div>
            </div>
        </div>
    </AppLayout>
</template>
