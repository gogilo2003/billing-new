<script lang="ts" setup>
import { iAccount } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import TextInput from '../../Components/TextInput.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import Modal from '../../Components/Modal.vue';
import InputLabel from '../../Components/InputLabel.vue';
import InputError from '../../Components/InputError.vue';
import Swal from 'sweetalert2'
import PrimaryButton from '../../Components/PrimaryButton.vue';
import vSelect from 'vue-select'
import 'vue-select/dist/vue-select.css';
import { computed, watch } from 'vue';

const props = defineProps<{
    account: iAccount
    show: boolean
    edit: { type: boolean, default: false }
}>()

const emits = defineEmits(['close'])

const swal = Swal.mixin({
    customClass: {
        confirmButton: "btn btn-success",
        cancelButton: "btn btn-danger"
    },
    buttonsStyling: false
});

const form = useForm({
    "id": null,
    "client": null,
    "name": null,
})

const accountDialogTitle = computed(() => props.edit ? "Edit Account" : "New Account")
const clients = computed(() => usePage().props.clients)

const close = () => {
    form.id = null
    form.name = null
    form.client = null
    emits('close')
}


const submit = () => {
    if (props.edit) {
        form.patch(route('accounts-update', form.id), {
            only: ['accounts', 'notification', 'errors'],
            onSuccess: () => {
                swal.fire({
                    icon: 'success',
                    text: usePage().props?.notification?.success
                })
            },
            onError: () => {
                swal.fire({
                    icon: 'error',
                    text: usePage().props?.notification?.success ?? 'An error ocurred! Please check and try again'
                })
            }
        })
    } else {
        form.post(route('accounts-store'), {
            only: ['accounts', 'notification', 'errors'],
            onSuccess: () => {
                Swal.fire({
                    icon: 'success',
                    text: usePage().props?.notification?.success
                })
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

watch(() => props.account, (value) => {
    form.id = value.id
    form.client = value.client.id
    form.name = value.name
})
</script>
<template>
    <Modal :show="show">
        <div
            class="flex items-center justify-between px-6 py-3 bg-gradient-to-br from-primary-default via-secondary-default to-primary-default">
            <div class="text-gray-50 font-semibold text-lg" v-text="accountDialogTitle"></div>
            <div>
                <Icon type="icon-simple-remove" @click="close" class="cursor-pointer text-gray-50 font-semibold" />
            </div>
        </div>
        <form @submit.prevent="submit">
            <div class="px-6 py-3">
                <div class="mb-4">
                    <InputLabel value="Client" />
                    <!-- <TextInput class="w-full" v-model="form.client" /> -->
                    <vSelect v-model="form.client" label="name" :options="clients" :reduce="client => client.id" />
                    <InputError :message="form.errors.client" />
                </div>
                <div class="mb-4">
                    <InputLabel value="Account Name" />
                    <TextInput class="w-full" v-model="form.name" />
                    <InputError :message="form.errors.name" />
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
