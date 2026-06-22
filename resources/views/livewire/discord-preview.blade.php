<div>
    @assets
        @vite('resources/css/discord-preview.css')
    @endassets

    <div class="dc-chat">
        <div class="dc-msg">
            {{-- Avatar --}}
            @if ($avatar = $sender['avatar'])
                <img src="{{ $avatar }}" class="dc-avatar" alt="">
            @else
                <div style="width:40px;height:40px;border-radius:50%;background:#5865f2;flex-shrink:0;margin-top:2px;"></div>
            @endif

            <div class="dc-msg-body">
                {{-- Meta row --}}
                <div class="dc-meta">
                    <span class="dc-username">{{ $sender['name'] ?? 'Pelican' }}</span>
                    @if (!($sender['human'] ?? false))
                        <span class="dc-bot-tag">app</span>
                    @endif
                    <span class="dc-timestamp">{{ $getTime() }}</span>
                </div>

                {{-- Message content --}}
                @if (filled($content))
                    <div class="dc-content">{!! nl2br(e($content)) !!}</div>
                @endif

                {{-- Embeds --}}
                @foreach ($embeds as $embed)
                    <div class="dc-embed" style="{{ $embed['view']['color_style'] }}">
                        <div class="dc-embed-body">
                            <div class="dc-embed-grid {{ $embed['view']['thumbnail'] ? 'has-thumbnail' : '' }}">

                                <div class="dc-embed-content">

                                    {{-- Author --}}
                                    @if ($embed['view']['author_name'])
                                        <div class="dc-embed-author">
                                            @if ($embed['view']['author_icon'])
                                                <img src="{{ $embed['view']['author_icon'] }}" class="dc-embed-author-icon" alt="">
                                            @endif
                                            @if ($embed['view']['author_url'])
                                                <a href="{{ $embed['view']['author_url'] }}" target="_blank" rel="noopener noreferrer" class="dc-embed-author-name dc-link">{{ $embed['view']['author_name'] }}</a>
                                            @else
                                                <span class="dc-embed-author-name">{{ $embed['view']['author_name'] }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Title --}}
                                    @if ($embed['view']['title'])
                                        @if ($embed['view']['title_url'])
                                            <a href="{{ $embed['view']['title_url'] }}" target="_blank" rel="noopener noreferrer" class="dc-embed-title">{{ $embed['view']['title'] }}</a>
                                        @else
                                            <div class="dc-embed-title">{{ $embed['view']['title'] }}</div>
                                        @endif
                                    @endif

                                    {{-- Description --}}
                                    @if ($embed['view']['description'])
                                        <div class="dc-embed-desc">{!! nl2br(e($embed['view']['description'])) !!}</div>
                                    @endif

                                    {{-- Fields --}}
                                    @if (!empty($embed['view']['fields']))
                                        <div class="dc-embed-fields">
                                            @foreach ($embed['view']['fields'] as $field)
                                                <div class="dc-embed-field {{ !empty($field['inline']) ? 'inline' : '' }}">
                                                    <div class="dc-embed-field-name">{{ $field['name'] ?? '' }}</div>
                                                    <div class="dc-embed-field-value">{!! nl2br(e($field['value'] ?? '')) !!}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                {{-- Thumbnail --}}
                                @if ($embed['view']['thumbnail'])
                                    <img src="{{ $embed['view']['thumbnail'] }}" class="dc-embed-thumbnail" alt="">
                                @endif

                            </div>{{-- /.dc-embed-grid --}}

                            {{-- Large image --}}
                            @if ($embed['view']['image'])
                                <img src="{{ $embed['view']['image'] }}" class="dc-embed-image" alt="">
                            @endif

                            {{-- Footer --}}
                            @if ($embed['view']['footer_text'] || $embed['view']['timestamp'])
                                <div class="dc-embed-footer">
                                    @if ($embed['view']['footer_icon'])
                                        <img src="{{ $embed['view']['footer_icon'] }}" class="dc-embed-footer-icon" alt="">
                                    @endif
                                    <span class="dc-embed-footer-text">
                                        @if ($embed['view']['footer_text']){{ $embed['view']['footer_text'] }}@endif
                                        @if ($embed['view']['footer_text'] && $embed['view']['timestamp'])<span class="dc-embed-footer-sep">•</span>@endif
                                        @if ($embed['view']['timestamp']){{ $embed['view']['timestamp'] }}@endif
                                    </span>
                                </div>
                            @endif

                        </div>{{-- /.dc-embed-body --}}
                    </div>{{-- /.dc-embed --}}
                @endforeach

            </div>{{-- /.dc-msg-body --}}
        </div>{{-- /.dc-msg --}}
    </div>{{-- /.dc-chat --}}
</div>
