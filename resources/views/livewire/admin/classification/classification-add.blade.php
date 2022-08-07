@section('title', 'Classification Add')

<div>
    <!-- Main content -->
    <div class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    @if($imported)
                    <div class="p-6 rounded border my-4 border-gray-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 inline" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-xl text-green-400 inline">{{__('Contacts imported')}}</span>
                    </div>
                    @else
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <form class="form-horizontal" wire:submit.prevent="parseFile"
                                        enctype="multipart/form-data">
                                        <div class="input-group">
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="csv_file" name="csv_file" wire:model="csv_file" required>
                                                <label class="custom-file-label" for="csv_file">{{__('CSV file to import')}}</label>
                                            </div>
                                            <div class="input-group-append">
                                                <span class="input-group-text">Upload</span>
                                            </div>
                                            @error('csv_file') <span class="text-red-400">{{ $message }}</span> @enderror
                                        </div>

                                        <div class="text-monospace mt-2 p-1 border border-gray-100">Colums accepted:
                                            <code class="text-xs text-red-400">
                                                
                                            </code>
                                            <br>
                                            <a href="#">click here</a> <span>to download CSV formate</span>
                                        </div>

                                        <div class="mt-1">
                                            <label>
                                                <input type="checkbox" wire:model="fileHasHeader" name="header" checked>
                                                {{__('File contains header row?')}}
                                            </label>
                                        </div>

                                        <div class="mt-1">
                                            <button type="submit"
                                                    class="px-4 py-1 btn-primary btn-xs text-white font-semibold cursor-pointer">Upload CSV
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="p-3 rounded border my-4 border-gray-100 overflow-x-auto max-w-7xl">
                        <form wire:submit.prevent="processImport">
            
                            <div class="flex flex-col">
                                <div class="overflow-x-auto">
                                    <div class="inline-block min-w-full ">
                                        <div class="overflow-hidden shadow-md sm:rounded-lg">
                                            <table class="min-w-full table-auto">
            
                                                @foreach ($csv_data as $i=> $row)
                                                    <tr>
                                                        @foreach ($row as $key => $value)
                                                            <td class="py-3 border-r px-6 text-xs font-medium tracking-wider text-left text-gray-700 uppercase dark:text-gray-400 @if($i==0) font-bold text-yellow-400 @endif">{{ $value }}</td>
                                                        @endforeach
                                                    </tr>
                                                @endforeach
                                                <tr>
                                                    @foreach ($csv_data[0]??[] as $key => $value)
                                                        <td  class=" text-xs tracking-wider text-left text-gray-700 uppercase dark:text-gray-400">
                                                            <select wire:model="match_fields.{{$key}}"
                                                                    class="text-sm w-full border border-gray-400 rounded-sm"
                                                                    name="fields[{{ $key }}]">
                                                                <option value="">--select--</option>
                                                                @foreach ($db_fields as $i=>$db_field)
                                                                    <option
                                                                            value="{{$db_field}}">{{ str_replace('_',' ',ucfirst($db_field)) }}</option>
                                                                @endforeach
                                                            </select>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(!empty($csv_data))
                                <button type="submit" class="px-4 py-1 btn-primary btn-xs text-white font-semibold cursor-pointer">
                                    {{__('Import Contacts')}}
                                </button>
                            @endif
                        </form>
            
                        @if(!empty($faile))
                            <div class="border-t border-gray-400 mt-4">
                                <div class="font-bold text-red-400 text-lg">Failed records</div>
                                @foreach($failed as $fail)
                                    <div class="text-monospace text-sm py-2 border-b border-gray-400">{{$fail}}</div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
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
