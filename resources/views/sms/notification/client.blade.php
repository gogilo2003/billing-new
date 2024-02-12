Dear {{ $client->name }}, we would like to remind you that the amount Kshs {{ ltrim($client->balance(true),'-') }} is due for payment. Thank you, {{ config('app.name') }}
