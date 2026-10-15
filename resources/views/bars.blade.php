{{-- Announcement bars. Themes can place them anywhere with @include('announcements::bars') --}}
@php
    $bars ??= \Azuriom\Plugin\Announcements\Support\Announcements::forRequest(request(), auth()->user())['bars'];
    $barMode = \Azuriom\Plugin\Announcements\Support\Announcements::barMode();
@endphp
@if(! empty($bars))
    <link rel="stylesheet" href="{{ plugin_asset('announcements', 'css/announcements.css') }}">
    <div id="plugiit-ann-bars" class="plugiit-ann-bars plugiit-ann-mode-{{ $barMode }}" role="region" aria-label="{{ trans('announcements::messages.region') }}"
         data-plugiit-ann-mode="{{ $barMode }}" data-plugiit-ann-delay="{{ \Azuriom\Plugin\Announcements\Support\Announcements::rotationDelay() }}">
        @foreach($bars as $bar)
            <div class="plugiit-ann-bar plugiit-ann-style-{{ $bar['style'] }} @if($barMode === 'rotate' && ! $loop->first) plugiit-ann-inactive @endif"
                 style="{{ \Azuriom\Plugin\Announcements\Support\Announcements::styleAttribute($bar) }}"
                 data-plugiit-ann-id="{{ $bar['id'] }}" data-plugiit-ann-version="{{ $bar['version'] }}"
                 @if($bar['dismissible']) data-plugiit-ann-dismissible @endif>
                <div class="plugiit-ann-bar-inner">
                    <div class="plugiit-ann-bar-content">
                        <strong class="plugiit-ann-bar-title">{{ $bar['title'] }}</strong>
                        <span class="plugiit-ann-message">{!! $bar['html'] !!}</span>
                        @if($bar['countdown'])
                            <span class="plugiit-ann-countdown" data-plugiit-ann-countdown="{{ $bar['ends_at'] }}"></span>
                        @endif
                    </div>
                    @if($bar['button_label'] && $bar['button_url'])
                        <a href="{{ $bar['button_url'] }}" class="plugiit-ann-btn">{{ $bar['button_label'] }}</a>
                    @endif
                    @if($bar['dismissible'])
                        <button type="button" class="plugiit-ann-close" data-plugiit-ann-close aria-label="{{ trans('announcements::messages.dismiss', ['title' => $bar['title']]) }}">
                            <svg viewBox="0 0 16 16" width="14" height="14" aria-hidden="true" focusable="false"><path fill="currentColor" d="M2.1 2.1a1 1 0 0 1 1.4 0L8 6.6l4.5-4.5a1 1 0 1 1 1.4 1.4L9.4 8l4.5 4.5a1 1 0 0 1-1.4 1.4L8 9.4l-4.5 4.5a1 1 0 0 1-1.4-1.4L6.6 8 2.1 3.5a1 1 0 0 1 0-1.4z"/></svg>
                        </button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    <script>
        (function () {
            try {
                var bars = document.getElementById('plugiit-ann-bars');
                Array.prototype.forEach.call(bars.querySelectorAll('[data-plugiit-ann-dismissible]'), function (bar) {
                    if (window.localStorage.getItem('plugiit-ann-dismissed-' + bar.getAttribute('data-plugiit-ann-id')) === bar.getAttribute('data-plugiit-ann-version')) {
                        bar.parentNode.removeChild(bar);
                    }
                });
                var first = bars.querySelector('.plugiit-ann-bar');
                if (!first) {
                    bars.parentNode.removeChild(bars);
                } else {
                    first.classList.remove('plugiit-ann-inactive');
                }
            } catch (e) {
            }
        })();
    </script>
@endif
