@unless($withBars ?? false)
    <link rel="stylesheet" href="{{ plugin_asset('announcements', 'css/announcements.css') }}">
@endunless
@foreach($popups as $popup)
    <div class="plugiit-ann-modal" id="plugiit-ann-popup-{{ $popup['id'] }}" data-plugiit-ann-popup
         data-plugiit-ann-id="{{ $popup['id'] }}" data-plugiit-ann-version="{{ $popup['version'] }}" data-plugiit-ann-frequency="{{ $popup['frequency'] }}" hidden>
        <div class="plugiit-ann-dialog plugiit-ann-style-{{ $popup['style'] }}" style="{{ \Azuriom\Plugin\Announcements\Support\Announcements::styleAttribute($popup) }}"
             role="dialog" aria-modal="true" aria-labelledby="plugiit-ann-popup-{{ $popup['id'] }}-title" tabindex="-1">
            <div class="plugiit-ann-dialog-accent" aria-hidden="true"></div>
            <button type="button" class="plugiit-ann-close plugiit-ann-dialog-close" data-plugiit-ann-close aria-label="{{ trans('announcements::messages.close') }}">
                <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true" focusable="false"><path fill="currentColor" d="M2.1 2.1a1 1 0 0 1 1.4 0L8 6.6l4.5-4.5a1 1 0 1 1 1.4 1.4L9.4 8l4.5 4.5a1 1 0 0 1-1.4 1.4L8 9.4l-4.5 4.5a1 1 0 0 1-1.4-1.4L6.6 8 2.1 3.5a1 1 0 0 1 0-1.4z"/></svg>
            </button>
            <h2 class="plugiit-ann-dialog-title" id="plugiit-ann-popup-{{ $popup['id'] }}-title">{{ $popup['title'] }}</h2>
            <div class="plugiit-ann-dialog-message">{!! $popup['html'] !!}</div>
            @if($popup['countdown'])
                <p class="plugiit-ann-countdown plugiit-ann-countdown-block" data-plugiit-ann-countdown="{{ $popup['ends_at'] }}"></p>
            @endif
            <div class="plugiit-ann-dialog-actions">
                @if($popup['button_label'] && $popup['button_url'])
                    <a href="{{ $popup['button_url'] }}" class="plugiit-ann-btn plugiit-ann-btn-solid">{{ $popup['button_label'] }}</a>
                @endif
                <button type="button" class="plugiit-ann-btn plugiit-ann-btn-ghost" data-plugiit-ann-close>{{ trans('announcements::messages.close') }}</button>
            </div>
        </div>
    </div>
@endforeach
<script type="application/json" id="plugiit-ann-config">@json([
    'texts' => trans('announcements::messages.countdown'),
])</script>
<script src="{{ plugin_asset('announcements', 'js/announcements.js') }}" defer></script>
