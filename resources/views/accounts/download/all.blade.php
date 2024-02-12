@extends('layouts.pdf')

@section('title')
    Accounts Listing
@endsection

@section('content')
    <div class="card card-plain">
        <table class="table table-hover table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account</th>
                    <th>Account Name</th>
                    <th class="text-right">DEBIT</th>
                    <th class="text-right">CREDIT</th>
                    <th class="text-right">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    {{-- <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div>{{ $account->client->name }}</div>
                            <div>{{ $account->name }}</div>
                        </td>
                        <td class="text-right">{{ $, 2) : '' }}</td>
                        <td class="text-right">{{ $tx->type === 'CR' ? number_format($tx->amount, 2) : '' }}</td>
                        <td class="text-right">{{ $tx->type === 'CR' ? number_format($tx->amount, 2) : '' }}</td>
                    </tr> --}}
                    @dump($accounts)
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
