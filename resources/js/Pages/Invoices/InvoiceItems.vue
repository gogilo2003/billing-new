<script setup lang="ts">
import { ref, watch, onMounted, watchEffect } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Icon from '@/Components/Icons/Icon.vue';
import { iInvoiceItem } from '@/types';
import { formatCurrency } from '@/helpers';

const props = defineProps<{
    value: iInvoiceItem[];
}>();

const emit = defineEmits<{
    'update:value': iInvoiceItem[];
}>()

const items = ref([]);
const newItem = ref<iInvoiceItem>({ particulars: '', price: 0, quantity: 0, editing: false });

onMounted(() => {
    console.log("onMounted InvoiceItem: ", props.value);

    if (props.value) {
        items.value = props.value
    }
})

watch(() => props.value, (value) => {
    console.log("watch InvoiceItems: ", value);

})

const addItem = () => {
    if (newItem.value.particulars.trim() !== '' && newItem.value.price > 0 && newItem.value.quantity > 0) {
        items.value.push({ ...newItem.value });
        newItem.value = { particulars: '', price: 0, quantity: 0 };
        emit('update:value', items.value);
    }
};

const removeItem = (index: number) => {
    items.value.splice(index, 1);
    emit('update:value', items.value);
};

const editItem = (index: number) => {
    items.value[index].editing = true;
};

const saveEdit = (index: number) => {
    items.value[index].editing = false;
    emit('update:value', items.value);
};

const cancelEdit = (index: number) => {
    items.value[index].editing = false;
};
</script>
<template>
    <div class="mt-4">
        <div
            class="bg-primary-default grid grid-cols-1 md:grid-cols-6 items-center justify-center gap-4 text-gray-50 uppercase font-medium">
            <div class="md:col-span-2 px-2 py-2">Particulars</div>
            <div class=" px-2 py-2">Price</div>
            <div class=" px-2 py-2">Quantity</div>
            <div class=" px-2 py-2">Amount</div>
            <div class=" px-2 py-1"></div>
        </div>
        <div class="py-2 text-sm font-light">
            <div class="grid grid-cols-1 md:grid-cols-6 items-center justify-center gap-4 odd:bg-gray-300/30"
                v-for="(item, index) in items" :key="index">
                <template v-if="item.editing">
                    <div class="col-span-2 px-2 py-2">
                        <TextInput class="w-full" v-model="item.particulars" placeholder="Particulars" />
                    </div>
                    <div class="px-2 py-2">
                        <TextInput class="w-full" v-model.number="item.price" type="number" placeholder="Price" />
                    </div>
                    <div class="px-2 py-2">
                        <TextInput class="w-full" v-model.number="item.quantity" type="number" placeholder="Quantity" />
                    </div>
                    <div class="px-2 py-2">
                        <div v-text="formatCurrency(item.quantity * item.price)"></div>
                    </div>
                    <div class="px-2 py-2 flex gap-1 items-center">
                        <SecondaryButton @click="saveEdit(index)">
                            <Icon type="icon-check-2" />
                        </SecondaryButton>
                        <SecondaryButton @click="cancelEdit(index)">
                            <Icon type="icon-simple-remove" />
                        </SecondaryButton>
                    </div>
                </template>
                <template v-else>
                    <div class="col-span-2 px-2 py-2" v-text="item.particulars"></div>
                    <div class="px-2 py-2" v-text="formatCurrency(item.price)"></div>
                    <div class="px-2 py-2" v-text="item.quantity"></div>
                    <div class="px-2 py-2">
                        <div v-text="formatCurrency(item.quantity * item.price)"></div>
                    </div>
                    <div class="flex gap-1 items-center px-2 py-2">
                        <SecondaryButton @click.prevent="editItem(index)">
                            <Icon type="icon-pencil" />
                        </SecondaryButton>
                        <SecondaryButton @click.prevent="removeItem(index)">
                            <Icon type="icon-trash-simple" />
                        </SecondaryButton>
                    </div>
                </template>
            </div>
        </div>
        <div
            class="text-sm font-light grid grid-cols-1 md:grid-cols-6 items-center justify-center gap-4 bg-gradient-to-br from-primary-default via-primary-400/90 to-primary-600 rounded-xl">
            <div class="col-span-1 md:col-span-2 px-2 py-2">
                <TextInput class="w-full" v-model="newItem.particulars" placeholder="Enter particulars" />
            </div>
            <div class="px-2 py-2">
                <TextInput class="w-full" v-model.number="newItem.price" type="number" placeholder="Enter price" />
            </div>
            <div class="px-2 py-2">
                <TextInput class="w-full" v-model.number="newItem.quantity" type="number"
                    placeholder="Enter quantity" />
            </div>
            <div class="px-2 py-2">
                <div class="text-gray-50" v-text="formatCurrency(newItem.quantity * newItem.price)"></div>
            </div>
            <div class="px-2 py-2">
                <SecondaryButton @click.prevent="addItem">Add Item</SecondaryButton>
            </div>
        </div>
    </div>
</template>
