<div>
    <div class="modal fade" id="modal-default" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info pt-2 pb-2">
                    <h4 class="modal-title text-white" style="font-size: 15px;">Import Business</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent='businessImport'>
                    <div class="modal-body">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="csv_file" wire:model='file' wire.ignore.self>
                                <label class="custom-file-label" for="csv_file">{{__('CSV file to import')}}</label>
                            </div>
                        </div>
                        @error('file') <p class="text-red">{{ $message }}</p> @enderror
                        <blockquote class="blockquote" style="font-size: 15px;border-left: 0.2rem solid #17a2b8;margin: 0.5em 0rem;padding: 0.5em 0.7rem;">
                            <p class="mb-0"><span class="text-red-400">Note*</span>: accepted file types xlsl, xls, csv</p>
                            <footer class="blockquote-footer"><a href="#" wire:click="downloadSample">click here</a> <span>to download CSV formate</span></footer>
                        </blockquote>
                        <div class="mt-1 p-1"><strong class="text-red-400" style="font-size: 12px;display:block;">Colums accepted:</strong>
                            <code class="text-xs text-info-400">
                                year, 
                                company_name, 
                                business_type, 
                                psic_code, 
                                sec_no, 
                                date_registered, 
                                ngc_code, 
                                status, 
                                address, 
                                industry_code, 
                                industry_description, 
                                geo_code, 
                                geo_description, 
                                long, 
                                lat
                            </code>
                        </div>
                    </div>
                    <div class="modal-footer justify-content-end pt-1 pb-1">
                        <button type="button" class="btn btn-sm btn-danger pt-1 pb-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-info pt-1 pb-1">Business Import</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

@push('extra-scripts')
    <!-- bs-custom-file-input -->
    <script src="{{asset('plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <!-- Page specific script -->
    <script>
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
@endpush

