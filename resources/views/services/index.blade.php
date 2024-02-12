@extends('layouts.app', ['pageSlug' => 'services'])

@section('title')
	Services
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="card-title">Services</h4>
			@if ($client)
				<p class="category">{{ $client->name }}</p>
			@else
				<p class="category">List of Services</p>
			@endif
		</div>
		<div class="card-body">
			<table class="table table-hover table-striped" id="servicesDataTable">
				<thead>
					<tr class="d-sm-table-row d-none">
						<th>#</th>
						<th>Client</th>
						<th>Name</th>
						<th class="text-right">Amount</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					@foreach($services as $service)
					<tr class="d-sm-table-row d-flex flex-column">
						<td>{{ $loop->iteration }}</td>
						<td>{{ $service->client->name }}</td>
						<td>{{ $service->name }}</td>
						<td class="text-right">{{ number_format($service->amount(),2) }}</td>
						<td>
							<a href="{{ route('services-view',$service->id) }}" class="btn btn-primary btn-sm"><i class="pe-7s-display1">&nbsp;View</i></a>
							<a href="{{ route('services-download',$service->id) }}" class="btn btn-primary btn-sm"><i class="pe-7s-download"></i>&nbsp;Download</a>
						</td>
					</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endsection

@section('navbar')
	<li class="nav-item"><a class="nav-link" href="{{ route('services-create') }}"><i class="fas fa-plus-circle"></i>&nbsp;Service</a></li>
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
		$('#servicesDataTable').dataTable();
	</script>
@endpush
