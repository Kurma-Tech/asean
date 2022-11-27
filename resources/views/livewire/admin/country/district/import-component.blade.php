<div>
    <div class="modal fade" id="modal-default" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-info pt-2 pb-2">
                    <h4 class="modal-title text-white" style="font-size: 15px;">Import District</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent='districtImport'>
                    <div class="modal-body">
                        <div class="input-group">
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file') is-invalid @enderror" id="csv_file" wire:model='file'>
                                <label class="custom-file-label" for="csv_file">@if($file) {{ $file->getClientOriginalName() }} @else {{__('CSV file to import')}} @endif</label>
                            </div>
                            <small wire:loading wire:target='file' wire:key='file' class="form-text text-muted"><i class="fa fa-spinner fa-spin mt-2 ml-2"></i> Uploading...</small>
                        </div>
                        @error('file') <p class="text-red">{{ $message }}</p> @enderror
                        
                        <div class="mt-1 p-1"><strong class="text-red-400 strong-code">Colums accepted:</strong>
                            <code class="text-xs text-info-400">
                                province_code,
                                name,
                                code,
                            </code>
                        </div>

                        <blockquote class="blockquote">
                            <p class="mb-0"><span class="text-red-400">Note*</span>: accepted file types xlsx, xls, csv</p>
                            <footer class="blockquote-footer"><a href="#" wire:click="downloadSample">click here</a> <span>to download CSV format</span></footer>
                        </blockquote>
                    </div>
                    <div class="modal-footer justify-content-end pt-1 pb-1">
                        <button type="button" class="btn btn-sm btn-danger pt-1 pb-1" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-sm btn-info pt-1 pb-1" @if($file) @else disabled @endif>
                            <small wire:loading wire:target='file' wire:key='file' class="form-text text-muted"><i class="fa fa-spinner fa-spin mt-2 ml-2"></i> </small>
                            District Import
                        </button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>
