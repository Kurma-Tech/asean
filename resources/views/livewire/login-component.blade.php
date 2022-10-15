<div>
    <div class="modal fade modal-auth" id="modal-login" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent='loginSubmit'>
                <div class="modal-content">
                    <div class="modal-header bg-success pt-2 pb-2">
                        <h4 class="modal-title text-white" style="font-size: 15px;">{{ GoogleTranslate::trans('Login', app()->getLocale()) }}</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="email">{{ GoogleTranslate::trans('Email address', app()->getLocale()) }}</label>
                            <input type="email" class="form-control" id="email" wire:model='email'
                                placeholder="{{ GoogleTranslate::trans('Enter email', app()->getLocale()) }}">
                        </div>
                        @error('email') <p class="text-red">{{ $message }}</p> @enderror
                        <div class="form-group">
                            <label for="password">{{ GoogleTranslate::trans('Password', app()->getLocale()) }}</label>
                            <input type="password" class="form-control" id="password" wire:model='password'
                                placeholder="{{ GoogleTranslate::trans('Password', app()->getLocale()) }}">
                        </div>
                        @error('password') <p class="text-red">{{ $message }}</p> @enderror

                        <blockquote class="blockquote">
                            <p class="mb-0"><span class="text-red-400">{{ GoogleTranslate::trans('Username', app()->getLocale()) }}</span>: admin@aseana.com</p>
                            <p class="mb-0"><span class="text-red-400">{{ GoogleTranslate::trans('Password', app()->getLocale()) }}</span>: password</p>
                            <p class="mb-0"><span class="text-red-400">{{ GoogleTranslate::trans('Note', app()->getLocale()) }}</span>: {{ GoogleTranslate::trans('Copy and past the crediantials for admin login', app()->getLocale()) }}</p>
                        </blockquote>
                    </div>
                    <div class="modal-footer justify-content-end pt-1 pb-1">
                        <button type="button" class="btn btn-sm btn-danger pt-1 pb-1" data-dismiss="modal">{{ GoogleTranslate::trans('Cancel', app()->getLocale()) }}</button>
                        <button type="submit" class="btn btn-sm btn-info pt-1 pb-1">{{ GoogleTranslate::trans('Login', app()->getLocale()) }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->

    <div class="modal fade modal-auth" id="modal-register" wire:ignore.self>
        <div class="modal-dialog">
            <form wire:submit.prevent='registerSubmit'>
                <div class="modal-content">
                    <div class="modal-header bg-success pt-2 pb-2">
                        <h4 class="modal-title text-white" style="font-size: 15px;">{{ GoogleTranslate::trans('Register', app()->getLocale()) }}</h4>
                        <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="fullname">{{ GoogleTranslate::trans('Full Name', app()->getLocale()) }}</label>
                            <input type="text" class="form-control" id="fullname" wire:model='fullname'
                                placeholder="{{ GoogleTranslate::trans('Enter Full Name', app()->getLocale()) }}">
                        </div>
                        @error('fullname') <p class="text-red">{{ $message }}</p> @enderror
                        <div class="form-group">
                            <label for="email">{{ GoogleTranslate::trans('Email address', app()->getLocale()) }}</label>
                            <input type="email" class="form-control" id="email" wire:model='email'
                                placeholder="{{ GoogleTranslate::trans('Enter email', app()->getLocale()) }}">
                        </div>
                        @error('email') <p class="text-red">{{ $message }}</p> @enderror
                        <div class="form-group">
                            <label for="password">{{ GoogleTranslate::trans('Password', app()->getLocale()) }}</label>
                            <input type="password" class="form-control" id="password" wire:model='password'
                                placeholder="{{ GoogleTranslate::trans('Password', app()->getLocale()) }}">
                        </div>
                        @error('password') <p class="text-red">{{ $message }}</p> @enderror
                    </div>
                    <div class="modal-footer justify-content-end pt-1 pb-1">
                        <button type="button" class="btn btn-sm btn-danger pt-1 pb-1" data-dismiss="modal">{{ GoogleTranslate::trans('Cancel', app()->getLocale()) }}</button>
                        <button type="submit" class="btn btn-sm btn-info pt-1 pb-1">{{ GoogleTranslate::trans('Register', app()->getLocale()) }}</button>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

@push('extra-scripts')
@endpush
