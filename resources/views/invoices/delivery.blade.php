@extends('layouts.pdf')

@section('title')
	Delivery Note
@endsection

@section('content')
	<div class="">
		<div class="">
			<table style="width: 100%">
				<tr>
					<td valign="top" width="60%">
						<div class="category">
						    <b style="color: #116AC3; text-transform: uppercase">Delivered To:</b><br>
						    {{ $invoice->client->name }}<br>
						    P.O. Box {{ $invoice->client->box_no.($invoice->client->post_code? ' - '.$invoice->client->post_code : '').($invoice->client->town ? ', '.$invoice->client->town : '') }}<br>
						    {{ $invoice->client->email }}, {{ $invoice->client->phone }}
					    </div>
					</td>
					<td valign="bottom" width="40%;" style="text-align:right">
						<div class="category"><b>Date:</b> {{ date_create($invoice->delivery->delivery_date)->format('D, j-M-Y') }}</div>
                        @if($invoice->order_no)
                        <div class="category"><b>Order No:</b> {{ $invoice->order_no }}</div>
                        @endif
						<div class="category"><b>Delivery No:</b> {{ $invoice->delivery->id }}</div>
						<div class="category"><b>For Invoice No:</b> {{ $invoice->id }}</div>
					</td>
				</tr>
			</table>
			<hr>
		</div>

		<div class="">
			<table cellspacing="0" class="table">
				<thead>
					<tr>
						<th style="width: 48px" class="text-right">#</th>
						<th style="width: auto" class="text-left">Item Description</th>
						<th style="width: 180px" class="text-center">Quantity</th>
					</tr>
				</thead>
				<tbody>
				@foreach($invoice->items as $item)
					<tr>
						<td class="text-right">{{ $loop->iteration }}.</td>
						<td>{{ $item->particulars }}</td>
						<td class="text-center">{{ $item->quantity }}</td>
					</tr>
				@endforeach
                </tbody>
			</table>
			<div class="delivery-footer">
                <div class="received_by">
                    <div class="name">Received By:</div>
                </div>
                <div class="received_by">
                    <div class="signature">Signature:</div>
                    <div class="date">Date</div>
                </div>
                <div class="received">Received in good order and condition</div>
			</div>
		</div>
	</div>
@endsection

@push('styles')
	<style>
		.card{
			box-shadow: 0 0px 0px #fff, 0 0 0 0px #fff;
			border-color: #fff;
		}
		.card .card-footer{
		    font-size: 11px;
		}
        .received_by div{
            border-bottom: 1px solid #222;
            padding: 0.5rem 0;
            margin-bottom: 1rem;
            font-weight: 500;
        }
        .received_by .date{
            display: inline-block;
            width: 39.8%;
        }
        .received_by .signature{
            display: inline-block;
            width: 59.8%;
        }
        .delivery-footer{
            margin-top: 2.5rem;
        }
        .received{
            margin: 2.5rem 0;
            text-align: center
        }
	</style>
@endpush
