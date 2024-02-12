export function formatCurrency(amount: number) {
    return new Intl.NumberFormat('en-KE', { style: 'currency', currency: 'KES' }).format(amount)
}

export function formatDate(date: any) {
    let d = new Date(date)
    return d.toLocaleDateString('en-KE', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    })
}
