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
