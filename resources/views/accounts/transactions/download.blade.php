<html>

<head>
    <title>Receipt</title>
    <style>
        @import url('{{ asset('css/receipt.css') }}');
    </style>
</head>

<body>
    <div class="receipt">
        <div class="logo">
            <img src="data:image/png;base64,{{ file_get_contents(public_path('logo.txt')) }}" alt="logo" />
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
                <div class="value">#{{ $transaction->id }}</div>
            </div>
            <div class="barcode"><img src="{{ $transaction->barcode }}" /></div>
        </div>
        <div class="text-center date">
            <div class="value">{{ $transaction->created_at->format('j-M-Y') }}</div>
        </div>
        <div class="text-center from">
            <div class="caption">RECEIVED FROM</div>
            <div class="underline"></div>
            <div class="value">{{ $transaction->account->client->name }}</div>
        </div>
        <div class="text-center amount">
            <div class="number">Kshs {{ number_format($transaction->amount, 2) }}</div>
            <div class="word">Kenya shillings {{ $transaction->amount_word }}</div>
            <div class="method">{{ $transaction->method }}@if ($transaction->transaction_ref)
                    ({{ $transaction->transaction_ref }})
                @endif
            </div>
        </div>
        <div class="text-center particulars">
            <div class="caption">Being payment for</div>
            <div class="underline"></div>
            <div class="value">{{ $transaction->particulars }}</div>
        </div>
        <div class="text-center thank">Than you for your Business!</div>
    </div>
</body>

</html>
