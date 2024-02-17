<script lang="ts" setup>
import { iInvoice } from '@/types';
import SecondaryButton from '../../Components/SecondaryButton.vue';
import Icon from '../../Components/Icons/Icon.vue'
import Modal from '../../Components/Modal.vue';
import { formatDate, formatCurrency } from '../../helpers';

const props = defineProps<{
    invoice: iInvoice
    show: boolean
}>()

const emits = defineEmits(['close'])

const close = () => {
    emits('close')
}

const downloadInvoice = () => {
    let link = document.createElement('a')
    link.setAttribute('href', route('invoices-download', props.invoice.id))
    link.setAttribute('target', '_BLANK')
    link.click()
}

</script>
<template>
    <Modal :show="show" max-width="5xl">
        <div class="modal fade" id="invoiceViewModalDialog" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
            aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title text-uppercase">{{ title }}</h3>
                    </div>
                    <div class="modal-body">
                        <h3 class="modal-title text-center text-uppercase mb-5">
                            Invoice
                        </h3>
                        <div style="text-align: center; margin-bottom: 1.5rem">
                            <h6 class="text-uppercase text-info mb-0">Invoice Number</h6>
                            <div style="margin-bottom:-10px">#{{ invoice.id }}</div>
                            <div class="barcode"><img :src="invoice.barcode" /></div>
                            <h6 class="text-uppercase text-info mb-0 mt-3">
                                Date Issued:
                            </h6>
                            <p class="mb-0">{{ invoice.created_at }}</p>
                        </div>
                        <div class="row">
                            <div class="col-md-5">
                                <h4 class="text-uppercase text-info mb-1">
                                    Invoice For:
                                </h4>
                                <p>
                                    {{ invoice.account.client.name }},<br />
                                    {{
                                        invoice.account.client.postal_address
                                    }},<br />
                                    {{ invoice.account.client.email }},
                                    {{ invoice.account.client.phone }}
                                </p>
                            </div>
                            <div class="col-md-7">
                                <div>
                                    <h4 class="text-uppercase text-info mb-1">
                                        FOR:
                                    </h4>
                                    <p>{{ invoice.name }}</p>
                                </div>
                                <div v-if="invoice.ref">
                                    <h4 class="text-uppercase text-info mb-1">
                                        REF:
                                    </h4>
                                    <p>{{ invoice.ref }}</p>
                                </div>
                            </div>
                            <div class="col-md-12 mt-4">
                                <table class="table table-bordered">
                                    <thead class="bg-secondary">
                                        <tr>
                                            <th>#</th>
                                            <th>PARTICULARS</th>
                                            <th>PRICE</th>
                                            <th>QUANTITY</th>
                                            <th>AMOUNT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, i) in invoice.items" :key="i">
                                            <td>{{ i + 1 }}</td>
                                            <td>{{ item.particulars }}</td>
                                            <td class="text-right">
                                                {{ item.price }}
                                            </td>
                                            <td class="text-center">
                                                {{ item.quantity }}
                                            </td>
                                            <td class="text-right">
                                                {{ item.amount }}
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot class="bg-secondary">
                                        <tr>
                                            <td class="text-right" colspan="4">
                                                TOTAL
                                            </td>
                                            <td class="text-right">
                                                {{ invoice.amount }}
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-round" data-dismiss="modal">
                            Close
                        </button>
                        <a :href="`/invoices/download/${invoice.id}`" class="btn btn-primary btn-round">
                            Download
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </Modal>
</template>
