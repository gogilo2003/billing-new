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
                    <th>Account Name</th>
                    <th class="text-right">DEBIT</th>
                    <th class="text-right">CREDIT</th>
                    <th class="text-right">BALANCE</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($accounts as $account)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div>{{ $account->client }}</div>
                            <div>{{ $account->name }}</div>
                        </td>
                        <td class="text-right">{{ $account->debit }}</td>
                        <td class="text-right">{{ $account->credit }}</td>
                        <td class="text-right" style="white-space: nowrap">{{ $account->balance }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table>
    </div>
@endsection
