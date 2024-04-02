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
                            <td class="text-right" colspan="4" style="font-weight:bold;">
                                SUB-TOTAL
                            </td>
                            <td style="font-weight: bold; font-size: 1.1em; border-bottom: 1px double #333"
                                class="text-right">{{ config('billing.currency') }}
                                {{ number_format($invoice->subTotal(), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="4" style="font-weight:bold;">
                                TAX
                            </td>
                            <td style="font-weight: bold; font-size: 1.1em; border-bottom: 1px double #333"
                                class="text-right">{{ config('billing.currency') }}
                                {{ number_format($invoice->tax(), 2) }}
                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-right" colspan="4" style="font-weight:bold;">
                            TOTAL
                        </td>
                        <td style="font-weight: bold; font-size: 1.1em; border-bottom: 1px double #333" class="text-right">
                            {{ config('billing.currency') }} {{ number_format($invoice->amount(), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="invoice-footer">
                @if (config('billing.mpesa'))
                    <h4 class="text-uppercase">MPESA</h4>
                    <ol>
                        <li>Go to Lipa Na M-PESA</li>
                        <li>Select Buy Goods</li>
                        <li>Enter the Till Number <strong>{{ config('billing.mpesa.buy_goods') }}</strong></li>
                        <li>Enter the amount ({{ config('billing.currency') }} {{ number_format($invoice->amount(), 2) }})
                        </li>
                        <li>Enter Your PIN and confirm sending to <strong>{{ config('billing.mpesa.name') }}</strong></li>
                    </ol>
                @endif
                <h4 class="text-uppercase">Cheque Payment</h4>
                <p class="category">Make all cheques payable to <strong>{{ config('app.name') }}</strong>.
                    @if (config('billing.tax.vat.type') === 'inclusive')
                        <br>All prices inclusive of
                        {{ config('billing.tax.vat.rate') }} VAT
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
    </style>
@endpush
