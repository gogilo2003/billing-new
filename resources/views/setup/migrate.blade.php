<!-- Modal -->
<div class="modal fade" id="migrateDialog" tabindex="-1" role="dialog" aria-labelledby="migrateDialogTitle" aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
            <div class="modal-header modal-header-primary">
                <h5 class="modal-title" id="migrateDialogTitle">Run migration</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="key" class="bmd-label-floating">Enter Security Key</label>
                    <input type="password" class="form-control" id="key" name="key">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger rounded-pill" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary rounded-pill" id="okMigrateButton">Ok</button>
            </div>
		</div>
	</div>
</div>

@push('scripts_bottom')
<script type="text/javascript">
	$(document).ready(function(){
		$('#okMigrateButton').click(function(e){
			let url = '{{ route('setup-migrate') }}'
			let data = {
				key: $('#migrateDialog input#key').val(),
				_token: '{{ csrf_token() }}'
			}
			$.post(url,data).then(function(response){
				$('#setup_results').html('<pre>'+(response.migration ? response.migration : 'Initialized')+'</pre>')
			})
			$('#migrateDialog').modal('hide')
		})
	})
</script>

@endpush
