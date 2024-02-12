@extends('layouts.app', ['pageSlug' => 'invoices/view'])

@section('title')
    View Invoice
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $invoice->name }}</h4>
            <hr>
            <p class="category">Invoice No: <em>{{ str_pad($invoice->id, 4, '0', 0) }}</em><img
                    src="{{ $invoice->barcode }}" /></p>
            <p class="category">Date: <em>{{ $invoice->created_at->format('j M, Y h:i:s A') }}</em></p>
            <p class="category">Amount: <em>KES {{ number_format($invoice->amount(), 2) }}</em></p>
        </div>
        <hr>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Particulars</th>
                        <th class="text-right">Price</th>
                        <th class="text-center">Qauantity</th>
                        <th class="text-right">Amount</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($invoice->items as $item)
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
                    <tr>
                        <td class="text-right" colspan="4" style="font-weight:bold;">
                            TOTAL
                        </td>
                        <td style="font-weight: bold; font-size: 1.1em" class="text-right">KES
                            {{ number_format($invoice->amount(), 2) }}</td>
                    </tr>
                </tfoot>
            </table>
            <hr>
            <div class="card-footer">
                <p class="text-center">{{ config('app.name') }}</p>
            </div>
        </div>
    </div>
@endsection

@section('navbar')
    @parent
    <li><a href="{{ route('invoices-download', $invoice->id) }}"><i class="pe-7s-download"></i>&nbsp;Download</a></li>
@endsection

@section('styles')
    <style>

    </style>
@endsection

@section('scripts_top')
    <script type="text/javascript"></script>
@endsection

@section('scripts_bottom')
    <script type="text/javascript"></script>
@endsection
