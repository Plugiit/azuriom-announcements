@extends('admin.layouts.admin')

@section('title', trans('plugiit-announcements::admin.settings.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('plugiit-announcements.admin.settings.save') }}" method="POST" v-scope="{ mode: @js(old('bar_mode', $barMode)) }">
                @csrf

                <div class="mb-3">
                    <label class="form-label" for="barModeSelect">{{ trans('plugiit-announcements::admin.settings.bar_mode') }}</label>
                    <select class="form-select @error('bar_mode') is-invalid @enderror" id="barModeSelect" name="bar_mode" v-model="mode">
                        @foreach(\Azuriom\Plugin\PlugiitAnnouncements\Support\Announcements::BAR_MODES as $mode)
                            <option value="{{ $mode }}">{{ trans('plugiit-announcements::admin.settings.bar_modes.'.$mode) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3" v-show="mode === 'rotate'">
                    <label class="form-label" for="delayInput">{{ trans('plugiit-announcements::admin.settings.rotation_delay') }}</label>
                    <div class="input-group @error('rotation_delay') has-validation @enderror">
                        <input type="number" min="3" max="60" class="form-control @error('rotation_delay') is-invalid @enderror" id="delayInput" name="rotation_delay" value="{{ old('rotation_delay', $rotationDelay) }}" required>
                        <span class="input-group-text">{{ trans('plugiit-announcements::admin.settings.seconds') }}</span>
                        @error('rotation_delay')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header">
            <h2 class="card-title h5 mb-0"><i class="bi bi-code-slash"></i> {{ trans('plugiit-announcements::admin.settings.integration') }}</h2>
        </div>
        <div class="card-body">
            <p class="mb-0">{{ trans('plugiit-announcements::admin.settings.integration_info', ['include' => '@'.'include(…)']) }}</p>
            <pre class="bg-body-tertiary p-3 rounded mt-3 mb-0"><code>&#64;include('plugiit-announcements::bars')</code></pre>
        </div>
    </div>
@endsection
