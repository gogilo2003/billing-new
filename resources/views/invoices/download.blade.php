@extends('layouts.pdf')

@section('title')
    Invoice
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 1.5rem">
        <table style="width:100%">
            <tr>
                <td>
                    <h4 class="text-uppercase text-info mb-0 mt-3" style="color: #116AC3">Invoice Number</h4>
                    <div style="margin-bottom:0px">#{{ $invoice->id }}</div>
                </td>
                <td>
                    <div class="barcode"><img src="{{ $invoice->qrcode }}" /></div>
                </td>
                <td>
                    <h4 class="text-uppercase text-info mb-0 mt-3" style="color: #116AC3">
                        Date Issued
                    </h4>
                    <div class="date">{{ date_create($invoice->created_at)->format('D, j-M-Y') }}</div>
                </td>
            </tr>
        </table>
    </div>
    <div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td valign="top" width="50%">
                        <p class="category">
                            <b style="color: #116AC3">INVOICE TO:</b><br>
                            {{ $invoice->client->name }}<br>
                            P.O. Box
                            {{ $invoice->client->box_no . ($invoice->client->post_code ? ' - ' . $invoice->client->post_code : '') . ($invoice->client->town ? ', ' . $invoice->client->town : '') }}<br>
                            {{ $invoice->client->email }}, {{ $invoice->client->phone }}
                        </p>
                    </td>
                    <td valign="top" width="50%">
                        <p class="category">
                            <b style="color: #116AC3">FOR:</b>
                            <br>{{ $invoice->name }}
                        </p>
                        @if ($invoice->ref)
                            <p class="category">
                                <b style="color: #116AC3">REF:</b>
                                <br>{{ $invoice->ref }}
                            </p>
                        @endif
                    </td>
                </tr>
            </table>
            <hr>
        </div>

        <div class="">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th style="width: 400px">Particulars</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Quantity</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->particulars }}</td>
                            <td class="text-right">{{ config('billing.currency') }}
                                {{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">{{ config('billing.currency') }}
                                {{ number_format($item->amount(), 2) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    @if (config('billing.tax.show'))
                        <tr>
                            <td class="text-right" colspan="4" style="font-weight:400;">
                                SUB-TOTAL
                            </td>
                            <td class="text-right total">{{ config('billing.currency') }}
                                {{ number_format($invoice->subTotal(), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="4" style="font-weight:400;">
                                TAX
                            </td>
                            <td class="text-right total">{{ config('billing.currency') }}
                                {{ number_format($invoice->tax(), 2) }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-right" colspan="4" style="font-weight:400;">
                            TOTAL
                        </td>
                        <td class="text-right total">
                            {{ config('billing.currency') }} {{ number_format($invoice->amount(), 2) }}</td>
                    </tr>
                    <tr>
                        <td class="text-right" colspan="4" style="font-weight:400;">
                            Paid
                        </td>
                        <td class="text-right total">
                            {{ config('billing.currency') }} {{ number_format($invoice->paid(), 2) }}</td>
                    </tr>
                    <tr style="backround-color:#116AC3">
                        <td class="text-right" colspan="4" style="font-weight:400;">
                            Balance Due
                        </td>
                        <td class="text-right total">
                            {{ config('billing.currency') }} {{ number_format($invoice->balance(), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="invoice-footer bg-green-400"
                style="position:relative;@if (config('billing.tax.show')) {{ 'top:-13rem' }}@else{{ 'top:-7.5rem' }} @endif">
                @if (config('billing.mpesa'))
                    <h4 class="text-uppercase">MPESA</h4>
                    @if (config('billing.mpesa.buy_goods'))
                        <ol>
                            <li>Go to Lipa Na M-PESA</li>
                            <li>Select Buy Goods</li>
                            <li>Enter the Till Number <strong>{{ config('billing.mpesa.buy_goods') }}</strong></li>
                            <li>Enter the amount ({{ config('billing.currency') }}
                                {{ number_format($invoice->amount(), 2) }})
                            </li>
                            <li>Enter Your PIN and confirm sending to <strong>{{ config('billing.mpesa.name') }}</strong>
                            </li>
                        </ol>
                    @endif
                    @if (config('billing.mpesa.pay_bill'))
                        <ol>
                            <li>Go to Lipa Na M-PESA</li>
                            <li>Select Pay Bill</li>
                            <li>Enter the Business No. <strong>{{ config('billing.mpesa.pay_bill') }}</strong></li>
                            <li>Enter Account No. <strong>{{ $invoice->id }}</strong></li>
                            <li>Enter the amount ({{ config('billing.currency') }}
                                {{ number_format($invoice->amount(), 2) }})
                            </li>
                            <li>Enter Your PIN and confirm sending to <strong>{{ config('billing.mpesa.name') }}</strong>
                            </li>
                        </ol>
                    @endif
                @endif
                <h4 class="text-uppercase">
                    Cheque Payment</h4>
                <p class="category">Make all cheques payable to <strong>{{ config('app.name') }}</strong>.
                    @if (config('billing.tax.vat.type') === 'inclusive')
                        <br>All prices inclusive of
                        <strong>{{ config('billing.tax.vat.rate') }}%</strong> VAT
                    @endif
                </p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .card {
            box-shadow: 0 0px 0px #fff, 0 0 0 0px #fff;
            border-color: #fff;
        }

        .card .card-footer {
            font-size: 11px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .barcode {
            width: 8rem;
            margin: 0 auto;
        }

        .barcode>img {
            width: 100%;
            height: auto;
            object-fit: cover;
        }

        .total {
            font-weight: 400;
            font-size: 0.8em;
        }

        .total-due {
            background-color: #116AC3;
            color: #fff;
        }
    </style>
@endpush
