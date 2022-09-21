<div>
    <div class="modal fade" id="modal-assign-manpower" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info pt-2 pb-2">
                    <h4 class="modal-title text-white" style="font-size: 15px;">Assign Manpower</h4>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form wire:submit.prevent='assignManpower'>
                    <div class="modal-body">
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th style="width: 70%;">Manpower</th>
                                    <th style="width: 20%;">Seat(s)</th>
                                    <th style="width: 10%;"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inputs as $key => $value)
                                <tr>
                                    <td>
                                        <select class="form-control" wire:model="manpower_id.{{ $key }}">
                                            <option hidden>Select Manpower</option>
                                            @foreach($manpowers as $manpower)
                                            <option value="{{ $manpower->id }}">{{ $manpower->title }} -- {{ $manpower->skilled }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        <input type="number" min="1" class="form-control" placeholder="Enter number of seats" wire:model='seats.{{ $key }}'>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="btn btn-xs bg-danger" wire:click.prevent="removeFields({{$key}})" data-toggle="tooltip" data-placement="top" title="Remove Field Row">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer justify-content-end pt-1 pb-1">
                        <button class="btn text-white btn-success btn-sm" wire:click.prevent="addFields({{$i}})"><i class="fas fa-plus"></i></button>
                        <button type="button" class="btn btn-sm btn-danger pt-1 pb-1" data-dismiss="modal">Discard</button>
                        <button type="submit" class="btn btn-sm btn-info pt-1 pb-1">Assign Manpower</button>
                    </div>
                </form>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
</div>

@push('extra-styles')

@endpush

@push('extra-scripts')

@endpush
