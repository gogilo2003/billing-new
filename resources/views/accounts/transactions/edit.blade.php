<div id="editTransactionModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form method="post" action="{{ route('accounts-transactions-update') }}" id="transaction-edit-form">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="account">Account</label>
                        <select class="form-control" id="account" name="account">
                            @foreach(App\Models\Account::with('client')->get()->sortBy('client.name') as $account)
                            <option value="{{ $account->id }}" {{ isset($account_id) ? ($account_id == $account->id ? 'selected' : '') : '' }}>{{ $account->client->name }}-{{ $account->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group{!! $errors->has('particulars') ? ' has-error':'' !!}">
                        <label for="particulars">Particulars</label>
                        <input type="text" class="form-control" id="particulars" name="particulars" placeholder="Enter particulars"{!! ((old('particulars')) ? ' value="'.old('particulars').'"' : '') !!}>
                        {!! $errors->has('particulars') ? '<span class="text-danger">'.$errors->first('particulars').'</span>' : '' !!}
                    </div>
                    <div class="form-group">
                        <label for="type">Transcaction Type</label>
                        <select class="form-control" id="type" name="type">
                            <option value="DR">Debit</option>
                            <option value="CR" selected>Credit</option>
                        </select>
                    </div>
                    <div class="form-group{!! $errors->has('method') ? ' has-error':'' !!}">
                        <label for="method">Mode of Payment</label>
                        <input type="text" class="form-control" id="method" name="method" placeholder="Cash/MPesa/Paypal/etc"{!! ((old('method')) ? ' value="'.old('method').'"' : '') !!}>
                        {!! $errors->has('method') ? '<span class="text-danger">'.$errors->first('method').'</span>' : '' !!}
                    </div>
                    <div class="form-group{!! $errors->has('amount') ? ' has-error':'' !!}">
                        <label for="amount">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" placeholder="Enter amount"{!! ((old('amount')) ? ' value="'.old('amount').'"' : '') !!}>
                        {!! $errors->has('amount') ? '<span class="text-danger">'.$errors->first('amount').'</span>' : '' !!}
                    </div>
                    <input class="form-control" type="hidden" id="id" name="id" value="">
                    @csrf
                </div>
                <div class="modal-footer">
                        <button data-dismiss="modal" class="btn btn-danger rounded-pill">Close</button>
                        <button type="submit" class="btn btn-primary rounded-pill">SAVE</a>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts_bottom')
    <script>
        $('#editTransactionModal').on('show.bs.modal',function(e){
            let transaction = JSON.parse(e.relatedTarget.getAttribute('data-transaction'))
            $('#editTransactionModal #id').val(transaction.id)
            $('#editTransactionModal #amount').val(transaction.amount)
            $('#editTransactionModal #method').val(transaction.method)
            $('#editTransactionModal #type').val(transaction.type)
            $('#editTransactionModal #particulars').val(transaction.particulars)
            $('#editTransactionModal #account').val(transaction.account_id)
        })
    </script>
@endpush

@push('styles')
    <style>
        .underline{
            border-bottom: 1px solid #000;
        }
    </style>
@endpush
