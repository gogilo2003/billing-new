@extends('layouts.app', ['pageSlug' => 'invoices'])

@section('title')
	Invoices
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Invoices</h4>
			@if ($client)
				<p class="category">{{ $client->name }}</p>
			@else
				<p class="category">List of Invoices</p>
			@endif
		</div>
		<div class="card-body">
            <a href="JavaScript:" class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#mergeInvoicesModal">Merge Invoices</a>
            <hr>
			<table class="table table-hover table-striped" id="invoicesDataTable">
				<thead>
					<tr class="d-sm-table-row d-none">
						<th colspan="2">#</th>
						<th>Client</>
						<th>Name</th>
                        <th>Date</th>
						<th class="text-right">Amount</th>
						<th>&nbsp;</th>
					</tr>
				</thead>
				<tbody>
					@foreach($invoices as $invoice)
					<tr class="d-sm-table-row d-flex flex-column">
						<td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="form-check form-check-inline">
                                <input id="tbl_invoice-{{ $loop->iteration }}" class="form-check-input" type="checkbox" name="invoices" value="{{ $invoice->id }}">
                            </div>
                        </td>
						<td>{{ $invoice->client->name }}</td>
						<td>{{ $invoice->name }}</td>
                        <td>{{ $invoice->created_at->format('D, d-M-Y') }}</td>
						<td class="text-right">{{ number_format($invoice->amount(),2) }}</td>
						<td>
							<a href="{{ route('invoices-view',$invoice->id) }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-th"></i>&nbsp;View</a>
							<a href="{{ route('invoices-download',$invoice->id) }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-file-download"></i>&nbsp;Download</a>
							<a href="{{ route('invoices-delivery',$invoice->id) }}" class="btn btn-primary btn-sm rounded-pill"><i class="fas fa-file-download"></i>&nbsp;Delivery</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
    </div>

    <div id="mergeInvoicesModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mergeInvoicesModalTitle" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="mergeInvoicesModalTitle">Merge Invoices</h5>
                    <button class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Accounts</p>
                    <ul class="list-group">
                        @foreach($invoices->sortBy('name') as $invoice)
                        <li class="list-group-item">
                            <div class="form-check form-check-inline">
                                <input id="invoice-{{ $loop->iteration }}" class="form-check-input" type="checkbox" name="invoices" value="{{ $invoice->id }}">
                                <label for="invoice-{{ $loop->iteration }}" class="form-check-label">{{ $invoice->client->name }} >> {{ $invoice->name }}</label>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary rounded-pill" type="button" id="mergerInvoicesButton">Merge</button>
                    <button class="btn btn-danger rounded-pill" type="button" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('navbar')
    <li class="nav-item {{ is_current_path(route('invoices-create'),true) }}">
        <a class="nav-link waves-effect" href="{{ route('invoices-create') }}">
            <span class="fas fa-plus-circle"></span>
            Invoice
        </a>
    </li>
@endsection

@push('styles')
	<style>

	</style>
@endpush

@push('scripts_top')
	<script type="text/javascript">

	</script>
@endpush

@push('scripts_bottom')
    <script type="text/javascript">
        let invoices = [];
		$('#invoicesDataTable').dataTable();
        $('#mergerInvoicesButton').click(function(e){
            let data = {
                _token: token,
                invoices
            }

            let url = '{{ route('invoices-merge-post') }}'

            $.post(url,data).then(
                response=>{console.log(response)},
                error=>{console.error(error)}
            )
        })
	</script>
@endpush
