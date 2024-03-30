<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style type="text/css">
        @import url('{{ asset('css/pdf.css') }}');
    </style>
</head>

<body>
    <header class="header">
        <div class="banner">
            <img src="{{ asset('images/letterhead-bg.png') }}" alt="">
        </div>
        <div class="text">
            <div class="contact">
                <img src="{{ asset('images/icons/email.png') }}" alt="">
                <span class="value">{{ config('billing.email') }}</span>
                <div style="clear: both"></div>
            </div>
            <div class="contact">
                <img src="{{ asset('images/icons/phone.png') }}" alt="">
                <span class="value">{{ config('billing.phone') }}</span>
                <div style="clear: both"></div>
            </div>
            <div class="contact">
                <img src="{{ asset('images/icons/map.png') }}" alt="">
                <span class="value">{{ config('billing.address') }}</span>
                <div style="clear: both"></div>
            </div>
        </div>
        <div style="clear:both"></div>
    </header>
</body>

</html>
