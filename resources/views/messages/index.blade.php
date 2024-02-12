@extends('layouts.app',['pageSlug'=>'messages','page'=>'Messages'])

@section('content')
<div class="card">
    <div class="card-body">
        <table id="smsDataTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Message Id</th>
                    <th>Recepient</th>
                    <th>Message</th>
                    <th>Status</th>
                    <th>Send Time</th>
                    <th>Sender Name</th>
                    <th>SMS Units</th>
                    <th>Network Name</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($messages as $message)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $message->message_id }}</td>
                    <td>{{ $message->recepient }}</td>
                    <td>{{ $message->message }}</td>
                    <td>{{ $message->status }}</td>
                    <td>{{ $message->send_time }}</td>
                    <td>{{ $message->sender_name }}</td>
                    <td>{{ $message->sms_unit }}</td>
                    <td>{{ $message->network_name }}</td>
                    <td></td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>
</div>
@endsection

@push('js')
    <script>
        $('#smsDataTable').dataTable()
    </script>
@endpush
