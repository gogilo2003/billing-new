@extends('layouts.app', ['pageSlug' => 'accounts/view'])

@section('title')
    View Account
@endsection

@section('sidebar_left')
    @parent
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">{{ $account->client->name }}({{ $account->name }})</h4>
            <hr>
            <p class="category">Credit: <em>{{ number_format($account->cr(), 2) }}</em></p>
            <p class="category">Debit: <em>{{ number_format($account->dr(), 2) }}</em></p>
            <p class="category">Balance: <em>{{ number_format($account->balance(), 2) }}</em></p>
        </div>
        <hr>
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Particulars</th>
                    <th class="text-right">DEBIT</th>
                    <th class="text-right">CREDIT</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($account->transactions as $tx)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tx->created_at->format('j M, Y h:i:s A') }}</td>
                        <td>{{ $tx->particulars }}</td>
                        <td class="text-right">{{ $tx->type === 'DR' ? number_format($tx->amount, 2) : '' }}</td>
                        <td class="text-right">{{ $tx->type === 'CR' ? number_format($tx->amount, 2) : '' }}</td>
                        <td>
                            <div class="btn-group">
                                <a href="#" data-toggle="modal" data-target="#viewTransactionModal"
                                    data-transaction='@json($tx)'
                                    class="btn btn-info btn-fill btn-sm view"><i class="pe-7s-albums"></i>&nbsp;View</a>
                                <a href="#" data-toggle="modal" data-target="#editTransactionModal"
                                    data-transaction='@json($tx)'
                                    class="btn btn-info btn-fill btn-sm edit"><i class="pe-7s-pen"></i>&nbsp;Edit</a>
                                <a href="{{ route('accounts-transactions-download', $tx->id) }}"
                                    class="btn btn-info btn-fill btn-sm"><i class="pe-7s-download"></i>&nbsp;Download</a>
                            </div>
                        </td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
    @include('accounts.transactions.edit')
@endsection

@section('navbar')
    @parent
    <li class="nav-item">
        <a class="nav-link" href="{{ route('accounts-create') }}">
            New Account
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('invoices-create', $account->id) }}">
            New Invoice
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('accounts-transaction', $account->id) }}">
            New Transaction
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('accounts-edit', $account->id) }}">
            Edit
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ route('accounts-download', $account->id) }}">
            Download
        </a>
    </li>
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
