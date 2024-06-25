@extends('layouts.pdf')

@section('title')
    Quotation
@endsection

@section('content')
    <div style="text-align: center; margin-bottom: 1.5rem;">
        <div style="float:left; width:50%;text-align: left">
            <h4 class="text-uppercase text-info mb-0 mt-3" style="color: #116AC3">Quotation Number</h4>
            <div style="margin-bottom:0px">#{{ $quotation->id }}</div>
            <div class="barcode"><img src="{{ $quotation->barcode }}" /></div>
        </div>
        <div style="float:right; width:50%; text-align:right">
            <h4 class="text-uppercase text-info mb-0 mt-3" style="color: #116AC3">
                Date Issued
            </h4>
            <div class="date">{{ date_create($quotation->created_at)->format('D, j-M-Y') }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div>
        <div>
            <table style="width: 100%">
                <tr>
                    <td valign="top" width="50%">
                        <p class="category">
                            <b style="color: #116AC3">QUOTATION FOR:</b><br>
                            {{ $quotation->client->name }}<br>
                            P.O. Box
                            {{ $quotation->client->box_no . ($quotation->client->post_code ? ' - ' . $quotation->client->post_code : '') . ($quotation->client->town ? ', ' . $quotation->client->town : '') }}<br>
                            {{ $quotation->client->email }}, {{ $quotation->client->phone }}
                        </p>
                    </td>
                    <td valign="top" width="50%">
                        &nbsp;
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <p class="category" style="margin-top: 16px;">
                            <b style="color: #116AC3; text-transform:uppercase">Quotation Description:</b>
                            <br>{{ $quotation->description }}
                        </p>
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
                    @foreach ($quotation->items as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item->particulars }}</td>
                            <td class="text-right">KES {{ number_format($item->price, 2) }}</td>
                            <td class="text-center">{{ $item->quantity }}</td>
                            <td class="text-right">KES {{ number_format($item->amount(), 2) }}</td>
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
                                class="text-right">KES {{ number_format($quotation->subTotal(), 2) }}</td>
                        </tr>
                        <tr>
                            <td class="text-right" colspan="4" style="font-weight:bold;">
                                TAX
                            </td>
                            <td style="font-weight: bold; font-size: 1.1em; border-bottom: 1px double #333"
                                class="text-right">KES {{ number_format($quotation->tax(), 2) }}</td>
                        </tr>
                    @endif
                    <tr>
                        <td class="text-right" colspan="4" style="font-weight:bold;">
                            TOTAL
                        </td>
                        <td style="font-weight: bold; font-size: 1.1em; border-bottom: 1px double #333" class="text-right">
                            KES {{ number_format($quotation->amount(), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <div class="quotation-footer"
                style="position:relative;@if (config('billing.tax.show')) {{ 'top:-8rem' }}@else{{ 'top:-2.5rem' }} @endif">
                @if (config('billing.mpesa.show'))
                    <h4 class="text-uppercase">MPESA</h4>
                    @if (config('billing.mpesa.buy_goods'))
                        <ol>
                            <li>Go to Lipa Na M-PESA</li>
                            <li>Select Buy Goods</li>
                            <li>Enter the Till Number <strong>{{ config('billing.mpesa.buy_goods') }}</strong></li>
                            <li>Enter the amount ({{ config('billing.currency') }}
                                {{ number_format($quotation->balance(), 2) }})
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
                            <li>Enter Account No. <strong>{{ $quotation->id }}</strong></li>
                            <li>Enter the amount ({{ config('billing.currency') }}
                                {{ number_format($quotation->amount(), 2) }})
                            </li>
                            <li>Enter Your PIN and confirm sending to <strong>{{ config('billing.mpesa.name') }}</strong>
                            </li>
                        </ol>
                    @endif
                @endif
                <h4 class="text-uppercase">Cheque Payment</h4>
                <p class="category">Make all cheques payable to <strong>{{ config('app.name') }}</strong>.
                    <br>All prices inclusive of {{ config('billing.taxt.vat.rate') }} VAT
                </p>
                @if ($quotation->notes)
                    <div style="margin-top:1rem">
                        <h4 class="text-uppercase">NOTES</h4>
                        <div class="my-3">
                            @markdown($quotation->notes)
                        </div>
                    </div>
                @endif
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
