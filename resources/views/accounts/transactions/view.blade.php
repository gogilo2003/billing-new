<div id="viewTransactionModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button data-dismiss="modal" class="btn btn-danger">Close</button>
                <a href="{{ route('accounts-transactions-download', 1) }}" class="btn btn-primary">Download</a>
            </div>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script>
        const formatCurrency = (number) => {
            const options = {
                style: 'currency',
                currency: 'Ksh'
            };

            var formatter = new Intl.NumberFormat('en-US', options);

            return formatter.format(number);
        }

        const formatDate = (date) => {
            let months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            let d = new Date(date)
            let strDate = d.getDate() + '-' + months[d.getMonth()] + '-' + d.getFullYear()
            return strDate
        }

        $('#viewTransactionModal').on('show.bs.modal', function(e) {
            let transaction = JSON.parse(e.relatedTarget.getAttribute('data-transaction'))
            $('#viewTransactionModal .modal-footer a').attr('href', '/accounts/transactions/download/' + transaction
                .id)

            let body = `<div class="receipt">
                <div class="logo">
                    <img src="data:image/png;base64,{{ file_get_contents(public_path('logo.txt')) }}" alt="logo"/>
                </div>
                <div class="address">
                    <div class="email">{{ config('billing.email') }}</div>
                    <div class="phone">{{ config('billing.phone') }}</div>
                    <div class="postal_address">{{ config('billing.address') }}</div>
                </div>
                <div class="text-center title">RECEIPT</div>
                <div class="text-center receipt_number">
                    <div class="number">
                        <div class="caption">Receipt Number</div>
                        <div class="value">#${transaction.id}</div>
                    </div>
                    <div class="barcode"><img src="${transaction.barcode}"/></div>
                </div>
                <div class="text-center date">
                    <div class="value">${formatDate(transaction.created_at)}</div>
                </div>
                <div class="text-center from">
                    <div class="caption">RECEIVED FROM</div>
                    <div class="underline"></div>
                    <div class="value">${transaction.account.client.name}</div>
                </div>
                <div class="text-center amount">
                    <div class="number">${formatCurrency(transaction.amount)}</div>
                    <div class="word">Kenya shillings ${transaction.amount_word}</div>
                    <div class="method">${transaction.method ? transaction.method : ''}</div>
                </div>
                <div class="text-center particulars">
                    <div class="caption">Being payment for</div>
                    <div class="underline"></div>
                    <div class="value">${transaction.particulars}</div>
                </div>
                <div class="text-center thank">Than you for your Business!</div>
            </div>
            `
            $('#viewTransactionModal .modal-body').html(body)

        })
    </script>
@endpush

@push('styles')
    <link rel="stylesheet" href="{{ mix('css/receipt.css') }}">
    <style>
        .underline {
            border-bottom: 1px solid #000;
        }

        #viewTransactionModal .modal-dialog {
            max-width: 6in;
            transform: translate(0, 0)
        }

        #viewTransactionModal .modal-body {
            background-color: #999;
            padding: 0;
            max-height: 84vh;
            overflow-y: auto;
        }
    </style>
@endpush
