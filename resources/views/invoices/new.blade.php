@extends('layouts.app', ['pageSlug' => 'invoices/add'])

@section('title')
	New Invoice
@endsection

@section('sidebar_left')
	@parent
@endsection

@section('content')
	<div class="card">
		<div class="card-header">
			<h4 class="card-title"><i class="pe-7s-plus"></i>&nbsp;New Invoice</h4>
			@if ($client)
				<p class="category">{{ $client->name }}</p>
			@else
				<p class="category">Provide invoice details below</p>
			@endif
		</div>
		<hr>
		<form id="invoiceForm" class="card-body" method="post" action="{{route('invoices-store')}}" role="form" accept-charset="UTF-8" enctype="multipart/form-data">
			<fieldset>
				<legend>Invoice Header</legend>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label for="account">Client</label>
							<select class="form-control" id="account" name="account">

								@foreach ($client ? $client->accounts : App\Models\Account::all() as $account)
									<option value="{{ $account->id }}">{{ $account->client->name }}-{{ $account->name }}</option>
								@endforeach

							</select>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group{!! $errors->has('name') ? ' has-error':'' !!}">
							<label for="name">Invoice Name</label>
							<input type="text" class="form-control" id="name" name="name" placeholder="Enter invoice name"{!! ((old('name')) ? ' value="'.old('name').'"' : '') !!}>
							{!! $errors->has('name') ? '<span class="text-danger">'.$errors->first('name').'</span>' : '' !!}
						</div>
					</div>
				</div>
			</fieldset>
			<fieldset>
				<legend>Invoice Items</legend>
				<div class="row">
					<div class="col-md-12">
						<table class="table table-striped table-hover">
							<thead>
								<tr>
									<th>#</th>
									<th>Particulars</th>
									<th class="text-right">Price</th>
									<th class="text-right">Quantity</th>
									<th class="text-right">Amount</th>
									<th>&nbsp;</th>
								</tr>
								@if($errors->has('details'))
								<tr>
									<td colspan="6">

										<div class="form-group{!! $errors->has('details') ? ' has-error':'' !!}">
											{!! $errors->has('details') ? '<span class="text-danger">'.$errors->first('details').'</span>' : ''!!}
										</div>

									</td>
								</tr>
								@endif
							</thead>
							<tbody>

							</tbody>
							<tfoot>
								<tr>
									<td></td>
									<td>
										<div class="form-group{!! $errors->has('particulars_add') ? ' has-error':'' !!}">
											<!--<label for="particulars_add">Particulars</label>-->
											<input type="text" class="form-control" id="particulars_add" name="particulars_add" placeholder="Enter particulars"{!! ((old('particulars_add')) ? ' value="'.old('particulars_add').'"' : '') !!}>
											{!! $errors->has('particulars_add') ? '<span class="text-danger">'.$errors->first('particulars_add').'</span>' : '' !!}
										</div>
									</td>
									<td>
										<div class="form-group{!! $errors->has('price_add') ? ' has-error':'' !!}">
											<!--<label for="price_add">Price</label>-->
											<input type="text" class="form-control text-right" id="price_add" name="price_add" placeholder="Enter price"{!! ((old('price_add')) ? ' value="'.old('price_add').'"' : '') !!}>
											{!! $errors->has('price_add') ? '<span class="text-danger">'.$errors->first('price_add').'</span>' : '' !!}
										</div>
									</td>
									<td>
										<div class="form-group{!! $errors->has('quantity_add') ? ' has-error':'' !!}">
											<!--<label for="quantity_add">Quantity</label>-->
											<input type="text" class="form-control text-right" id="quantity_add" name="quantity_add" placeholder="Enter quantity"{!! ((old('quantity_add')) ? ' value="'.old('quantity_add').'"' : '') !!}>
											{!! $errors->has('quantity_add') ? '<span class="text-danger">'.$errors->first('quantity_add').'</span>' : '' !!}
										</div>
									</td>
									<td>
										<div class="form-group{!! $errors->has('amount_add') ? ' has-error':'' !!}">
											<!--<label for="amount_add">Amount</label>-->
											<input type="text" class="form-control text-right" id="amount_add" name="amount_add" placeholder="Enter amount"{!! ((old('amount_add')) ? ' value="'.old('amount_add').'"' : '') !!}>
											{!! $errors->has('amount_add') ? '<span class="text-danger">'.$errors->first('amount_add').'</span>' : '' !!}
										</div>
									</td>
									<td>
										<a id="addInvoiceItem" href="javascript:" style="font-size: 20px"><i class="fas fa-plus-circle"></i></a>
									</td>
								</tr>
							</tfoot>
						</table>
					</div>
				</div>
			</fieldset>
			<div class="card-footer">
				<input type="hidden" name="_token" value="{{csrf_token()}}">
				<button type="submit" class="btn btn-primary"><i class="fas fa-save"></i>&nbsp;Create</button>
			</div>

		</form>
	</div>
@endsection

@section('navbar')
	@parent
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
	var index = 1;
	$(document).ready(function(){

		$('#amount_add').val(null);
		$('#particulars_add').val(null)
		$('#quantity_add').val(1)
		$('#price_add').val(null)

		$('#particulars_add').focus()

		$('#quantity_add, #price_add').change(function(){
			$('#amount_add').val(parseInt($('#quantity_add').val()) * parseInt($('#price_add').val()))
		})

		$('#addInvoiceItem').click(function(){
			let amount_add 		= $('#amount_add').val();
			let particulars_add = $('#particulars_add').val()
			let quantity_add 	= $('#quantity_add').val()
			let price_add 		= $('#price_add').val()

			// let amount_add = price_add + quantity_add

			let tr = '<tr>' +
						'<td>'+ index +'</td>'+
						'<td>'+ particulars_add +'</td>'+
						'<td class="text-right">'+ price_add +'</td>'+
						'<td class="text-right">'+ quantity_add +'</td>'+
						'<td class="text-right">'+ amount_add +'</td>'+
						'<td><i class="pe-7s-trash"></i></td>'+
					'</tr>'

			$('tbody').append(tr);

			$('#amount_add').val(null);
			$('#particulars_add').val(null)
			$('#quantity_add').val(1)
			$('#price_add').val(null)

			$('#particulars_add').focus()

			$('form#invoiceForm').append('<input type="hidden" name="details['+ (index-1) +'][particulars]" class="form-control text-right" value="'+ particulars_add +'">\n')
			$('form#invoiceForm').append('<input type="hidden" name="details['+ (index-1) +'][price]" class="form-control text-right" value="'+ price_add +'">\n')
			$('form#invoiceForm').append('<input type="hidden" name="details['+ (index-1) +'][quantity]" class="form-control text-right" value="'+ quantity_add +'">\n')

			index++;
		});
	})
	</script>
@endpush

