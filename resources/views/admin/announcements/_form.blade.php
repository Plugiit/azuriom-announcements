@csrf

@include('admin.elements.date-picker')

@php($t = 'announcements::admin.announcements')

<div v-scope="{
        type: @js(old('type', $announcement->type ?? 'bar')),
        style: @js(old('style', $announcement->style ?? 'info')),
        audience: @js(old('audience', $announcement->audience ?? 'all')),
        pages: @js(old('pages', $announcement->pages ?? 'all')),
    }">
    <div class="row">
        <div class="col-xl-7">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="card-title h5 mb-0"><i class="bi bi-chat-square-text"></i> {{ trans($t.'.content') }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="titleInput">{{ trans($t.'.fields.title') }}</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="titleInput" name="title" value="{{ old('title', $announcement->title ?? '') }}" required maxlength="150">
                        @error('title')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-end gap-2 mb-2">
                            <label class="form-label mb-0" for="messageInput">{{ trans($t.'.fields.message') }}</label>
                            <select class="form-select form-select-sm w-auto @error('format') is-invalid @enderror" name="format" aria-label="{{ trans($t.'.fields.format') }}">
                                @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::FORMATS as $format)
                                    <option value="{{ $format }}" @selected(old('format', $announcement->format ?? 'markdown') === $format)>{{ trans($t.'.formats.'.$format) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="messageInput" name="message" rows="5" required maxlength="5000" aria-describedby="messageHelp">{{ old('message', $announcement->message ?? '') }}</textarea>
                        @error('message')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div id="messageHelp" class="form-text">{{ trans($t.'.help.message') }}</div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-5">
                            <label class="form-label" for="buttonLabelInput">{{ trans($t.'.fields.button_label') }}</label>
                            <input type="text" class="form-control @error('button_label') is-invalid @enderror" id="buttonLabelInput" name="button_label" value="{{ old('button_label', $announcement->button_label ?? '') }}" maxlength="50">
                            @error('button_label')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-7">
                            <label class="form-label" for="buttonUrlInput">{{ trans($t.'.fields.button_url') }}</label>
                            <input type="text" class="form-control @error('button_url') is-invalid @enderror" id="buttonUrlInput" name="button_url" value="{{ old('button_url', $announcement->button_url ?? '') }}" maxlength="255" placeholder="https://… ou /shop" aria-describedby="buttonHelp">
                            @error('button_url')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div id="buttonHelp" class="form-text">{{ trans($t.'.help.button') }}</div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="card-title h5 mb-0"><i class="bi bi-calendar-event"></i> {{ trans($t.'.schedule') }}</h2>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="startsInput">{{ trans($t.'.fields.starts_at') }}</label>
                            <input type="text" class="form-control date-picker @error('starts_at') is-invalid @enderror" id="startsInput" name="starts_at" value="{{ old('starts_at', $announcement->starts_at ?? '') }}" aria-describedby="datesHelp">
                            @error('starts_at')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label" for="endsInput">{{ trans($t.'.fields.ends_at') }}</label>
                            <input type="text" class="form-control date-picker @error('ends_at') is-invalid @enderror" id="endsInput" name="ends_at" value="{{ old('ends_at', $announcement->ends_at ?? '') }}" aria-describedby="datesHelp">
                            @error('ends_at')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>
                    <div id="datesHelp" class="form-text mb-3">{{ trans($t.'.help.dates') }}</div>

                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="countdownSwitch" name="has_countdown" @checked(old('has_countdown', $announcement->has_countdown ?? false))>
                        <label class="form-check-label" for="countdownSwitch">{{ trans($t.'.fields.has_countdown') }}</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-5">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="card-title h5 mb-0"><i class="bi bi-palette"></i> {{ trans($t.'.display') }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="typeSelect">{{ trans($t.'.fields.type') }}</label>
                        <select class="form-select @error('type') is-invalid @enderror" id="typeSelect" name="type" v-model="type">
                            @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::TYPES as $type)
                                <option value="{{ $type }}">{{ trans($t.'.types.'.$type) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="styleSelect">{{ trans($t.'.fields.style') }}</label>
                        <select class="form-select @error('style') is-invalid @enderror" id="styleSelect" name="style" v-model="style">
                            @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::STYLES as $style)
                                <option value="{{ $style }}">{{ trans($t.'.styles.'.$style) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row g-3 mb-3" v-if="style === 'custom'">
                        <div class="col-6">
                            <label class="form-label" for="colorInput">{{ trans($t.'.fields.color') }}</label>
                            <input type="color" class="form-control form-control-color w-100 @error('color') is-invalid @enderror" id="colorInput" name="color" value="{{ old('color', $announcement->color ?? '#5b5bd6') }}">
                            @error('color')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label" for="textColorInput">{{ trans($t.'.fields.text_color') }}</label>
                            <input type="color" class="form-control form-control-color w-100 @error('text_color') is-invalid @enderror" id="textColorInput" name="text_color" value="{{ old('text_color', $announcement->text_color ?? '#ffffff') }}">
                            @error('text_color')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3" v-if="type === 'bar'">
                        <div class="form-check form-switch">
                            <input type="checkbox" class="form-check-input" id="dismissibleSwitch" name="is_dismissible" @checked(old('is_dismissible', $announcement->is_dismissible ?? true)) aria-describedby="dismissibleHelp">
                            <label class="form-check-label" for="dismissibleSwitch">{{ trans($t.'.fields.is_dismissible') }}</label>
                        </div>
                        <div id="dismissibleHelp" class="form-text">{{ trans($t.'.help.dismissible') }}</div>
                    </div>
                    <input type="hidden" name="is_dismissible" value="1" v-if="type === 'popup'">

                    <div class="mb-3" v-if="type === 'popup'">
                        <label class="form-label" for="frequencySelect">{{ trans($t.'.fields.frequency') }}</label>
                        <select class="form-select @error('frequency') is-invalid @enderror" id="frequencySelect" name="frequency" aria-describedby="frequencyHelp">
                            @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::FREQUENCIES as $frequency)
                                <option value="{{ $frequency }}" @selected(old('frequency', $announcement->frequency ?? 'once') === $frequency)>{{ trans($t.'.frequencies.'.$frequency) }}</option>
                            @endforeach
                        </select>
                        <div id="frequencyHelp" class="form-text">{{ trans($t.'.help.frequency') }}</div>
                    </div>
                    <input type="hidden" name="frequency" value="{{ old('frequency', $announcement->frequency ?? 'once') }}" v-if="type === 'bar'">

                    <div class="mb-3">
                        <label class="form-label" for="priorityInput">{{ trans($t.'.fields.priority') }}</label>
                        <input type="number" min="-100" max="100" class="form-control @error('priority') is-invalid @enderror" id="priorityInput" name="priority" value="{{ old('priority', $announcement->priority ?? 0) }}" required aria-describedby="priorityHelp">
                        @error('priority')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div id="priorityHelp" class="form-text">{{ trans($t.'.help.priority') }}</div>
                    </div>

                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="enabledSwitch" name="is_enabled" @checked(old('is_enabled', $announcement->is_enabled ?? true))>
                        <label class="form-check-label" for="enabledSwitch">{{ trans($t.'.fields.is_enabled') }}</label>
                    </div>
                </div>
            </div>

            <div class="card shadow mb-4">
                <div class="card-header">
                    <h2 class="card-title h5 mb-0"><i class="bi bi-bullseye"></i> {{ trans($t.'.targeting') }}</h2>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label" for="audienceSelect">{{ trans($t.'.fields.audience') }}</label>
                        <select class="form-select @error('audience') is-invalid @enderror" id="audienceSelect" name="audience" v-model="audience">
                            @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::AUDIENCES as $audience)
                                <option value="{{ $audience }}">{{ trans($t.'.audiences.'.$audience) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3" v-if="audience === 'roles'">
                        <label class="form-label" for="rolesSelect">{{ trans($t.'.fields.roles') }}</label>
                        <select class="form-select @error('roles') is-invalid @enderror" id="rolesSelect" name="roles[]" multiple size="5">
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" @selected(in_array($role->id, array_map('intval', old('roles', $announcement->roles ?? [])), true))>{{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('roles')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="pagesSelect">{{ trans($t.'.fields.pages') }}</label>
                        <select class="form-select @error('pages') is-invalid @enderror" id="pagesSelect" name="pages" v-model="pages">
                            @foreach(\Azuriom\Plugin\Announcements\Models\Announcement::PAGES as $page)
                                <option value="{{ $page }}">{{ trans($t.'.pages_options.'.$page) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div v-if="pages === 'pattern'">
                        <label class="form-label" for="patternsInput">{{ trans($t.'.fields.page_patterns') }}</label>
                        <textarea class="form-control font-monospace @error('page_patterns') is-invalid @enderror" id="patternsInput" name="page_patterns" rows="3" aria-describedby="patternsHelp">{{ old('page_patterns', $announcement->page_patterns ?? '') }}</textarea>
                        @error('page_patterns')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                        <div id="patternsHelp" class="form-text">{{ trans($t.'.help.page_patterns') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
