export interface iClient {
    id: number,
    name: string
    email: string
    phone: string
    box_no: string
    post_code: string
    town: string
    address: string
    balance: number,
    latest_payment_date: string
    latest_charge_date: string
    latest_transaction_date: string
    accounts: Array<{
        id: number
        name: string
        amount: number,
        paid: number,
        balance: number,
        latest_payment_date: string
        latest_charge_date: string
        latest_transaction_date: string
    }>
}

export interface iAccount {
    id: number
    name: string
    amount: number,
    paid: number,
    balance: number,
    latest_payment_date: string
    latest_charge_date: string
    latest_transaction_date: string
    client: {
        id: number,
        name: string
        email: string
        phone: string
        box_no: string
        post_code: string
        town: string
        address: string
        balance: number,
        latest_payment_date: string
        latest_charge_date: string
        latest_transaction_date: string
    },
    transactions: [
        {
            id: number,
            particulars: string
            type: string
            amount: number
            amount_word: string
            method: null,
            cr: number,
            dr: number
            transaction_date: string
            barcode: string
            qrcode: string
        },
    ]
}

export interface iUser {
    id: number
    name: string
    email: string
    phone: string
    photo: string
}

export interface iInvoiceItem {
    id?: number
    particulars: string | null
    quantity: number | 0
    price: number
    editing?: boolean
}

export interface iInvoice {
    id: number
    name: string
    ref: string
    amount: number
    paid: number
    balance: number
    barcode: string
    qrcode: string
    date: Date
    account: number
    client: iClient
    items: Array<iInvoiceItem>
    receipts: Array<iReceipt>
}
export interface iQuotationItem {
    id?: number
    particulars: string
    quantity: number
    price: number
    unit?: string
    editing?: boolean
}

export interface iQuotation {
    id?: number | null
    description: string
    validity: number
    sub_total?: number
    tax?: number
    amount?: number
    barcode?: string
    qrcode?: string
    client?: iClient
    user?: iUser
    date?: Date
    items?: Array<iQuotationItem>
}
export interface iReceipt {
    id: number | null;
    particulars: string;
    method: string;
    amount: number | null;
    transaction_ref: string | null;
    date: Date | null;
}

