<div>
    @assets
        <style>
            .dc-chat {
                background: #313338;
                padding: 8px 16px;
                border-radius: 6px;
                font-family: 'gg sans', 'Noto Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
                font-size: 1rem;
                line-height: 1.375rem;
                color: #dbdee1;
            }

            .dc-msg {
                display: flex;
                gap: 16px;
                align-items: flex-start;
            }

            .dc-avatar {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                object-fit: cover;
                flex-shrink: 0;
                margin-top: 2px;
            }

            .dc-msg-body {
                flex: 1;
                min-width: 0;
            }

            .dc-meta {
                display: flex;
                align-items: baseline;
                gap: 8px;
                margin-bottom: 4px;
            }

            .dc-username {
                font-size: 1rem;
                font-weight: 500;
                color: #f2f3f5;
                line-height: 1.375rem;
            }

            .dc-bot-tag {
                background: #5865f2;
                border-radius: 3px;
                font-size: 0.625rem;
                font-weight: 500;
                padding: 1px 4px;
                color: #fff;
                text-transform: uppercase;
                letter-spacing: 0.02em;
                line-height: 1.3;
                vertical-align: middle;
                position: relative;
                top: -1px;
            }

            .dc-timestamp {
                font-size: 0.75rem;
                color: #949ba4;
                line-height: 1.375rem;
            }

            .dc-content {
                font-size: 1rem;
                color: #dbdee1;
                white-space: pre-wrap;
                word-break: break-word;
                line-height: 1.375rem;
            }

            /* ── Embed ────────────────────────────────── */

            .dc-embed {
                background: #2b2d31;
                border-left: 4px solid #1e1f22;
                border-radius: 4px;
                max-width: 516px;
                margin-top: 4px;
                overflow: hidden;
            }

            .dc-embed-body {
                padding: 8px 16px 16px 12px;
            }

            /* Grid: content column + optional thumbnail column */
            .dc-embed-grid {
                display: grid;
                grid-template-columns: 1fr;
                gap: 0 16px;
            }

            .dc-embed-grid.has-thumbnail {
                grid-template-columns: 1fr 80px;
            }

            .dc-embed-content {
                grid-column: 1;
                min-width: 0;
            }

            .dc-embed-thumbnail {
                grid-column: 2;
                grid-row: 1;
                width: 80px;
                height: 80px;
                object-fit: cover;
                border-radius: 3px;
                align-self: start;
                margin-top: 8px;
            }

            /* Author */
            .dc-embed-author {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 8px;
            }

            .dc-embed-author-icon {
                width: 24px;
                height: 24px;
                border-radius: 50%;
                object-fit: cover;
            }

            .dc-embed-author-name {
                font-size: 0.875rem;
                font-weight: 600;
                color: #f2f3f5;
                line-height: 1.375rem;
                overflow-wrap: break-word;
            }

            a.dc-embed-author-name:hover {
                text-decoration: underline;
            }

            /* Title */
            .dc-embed-title {
                display: inline-block;
                font-size: 1rem;
                font-weight: 600;
                color: #f2f3f5;
                margin-top: 8px;
                line-height: 1.375rem;
                overflow-wrap: break-word;
            }

            a.dc-embed-title {
                color: #00a8fc;
                text-decoration: none;
            }

            a.dc-embed-title:hover {
                text-decoration: underline;
            }

            /* Description */
            .dc-embed-desc {
                font-size: 0.875rem;
                color: #dbdee1;
                line-height: 1.375rem;
                margin-top: 8px;
                white-space: pre-wrap;
                word-break: break-word;
            }

            /* Fields — 3-column grid */
            .dc-embed-fields {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 8px;
                margin-top: 8px;
            }

            .dc-embed-field {
                grid-column: 1 / -1;
                min-width: 0;
            }

            .dc-embed-field.inline {
                grid-column: span 1;
            }

            .dc-embed-field-name {
                font-size: 0.875rem;
                font-weight: 600;
                color: #f2f3f5;
                line-height: 1.375rem;
                margin-bottom: 2px;
                overflow-wrap: break-word;
            }

            .dc-embed-field-value {
                font-size: 0.875rem;
                color: #dbdee1;
                line-height: 1.375rem;
                white-space: pre-wrap;
                overflow-wrap: break-word;
            }

            /* Large image */
            .dc-embed-image {
                display: block;
                width: 100%;
                max-height: 300px;
                object-fit: contain;
                border-radius: 0 0 4px 4px;
                margin-top: 16px;
                cursor: pointer;
            }

            /* Footer */
            .dc-embed-footer {
                display: flex;
                align-items: center;
                gap: 8px;
                margin-top: 8px;
                flex-wrap: wrap;
            }

            .dc-embed-footer-icon {
                width: 20px;
                height: 20px;
                border-radius: 50%;
                object-fit: cover;
                flex-shrink: 0;
            }

            .dc-embed-footer-text {
                font-size: 0.75rem;
                color: #949ba4;
                line-height: 1.375rem;
                overflow-wrap: break-word;
            }

            .dc-embed-footer-sep {
                margin: 0 4px;
            }

            a.dc-link {
                color: #00a8fc;
                text-decoration: none;
            }

            a.dc-link:hover {
                text-decoration: underline;
            }
        </style>
    @endassets

    <div class="dc-chat">
        <div class="dc-msg">
            {{-- Avatar --}}
            @if($avatar = $sender['avatar'])
                <img src="{{ $avatar }}" class="dc-avatar" alt="">
            @else
                <div style="width:40px;height:40px;border-radius:50%;background:#5865f2;flex-shrink:0;margin-top:2px;"></div>
            @endif

            <div class="dc-msg-body">
                {{-- Meta row --}}
                <div class="dc-meta">
                    <span class="dc-username">{{ data_get($sender, 'name', 'Pelican') }}</span>
                    @if(!data_get($sender, 'human'))
                        <span class="dc-bot-tag">app</span>
                    @endif
                    <span class="dc-timestamp">{{ $getTime() }}</span>
                </div>

                {{-- Message content --}}
                @if(filled($content))
                    <div class="dc-content">{!! nl2br(e($content)) !!}</div>
                @endif

                {{-- Embeds --}}
                @foreach($embeds as $embed)
                    @php
                        $eAuthorName  = $embed['author']['name']     ?? null;
                        $eAuthorUrl   = $embed['author']['url']      ?? null;
                        $eAuthorIcon  = $embed['author']['icon_url'] ?? null;
                        $eTitle       = $embed['title']              ?? null;
                        $eTitleUrl    = $embed['url']                ?? null;
                        $eDesc        = $embed['description']        ?? null;
                        $eFields      = $embed['fields']             ?? [];
                        $eImage       = $embed['image']['url']       ?? null;
                        $eThumbnail   = $embed['thumbnail']['url']   ?? null;
                        $eFooterText  = $embed['footer']['text']     ?? null;
                        $eFooterIcon  = $embed['footer']['icon_url'] ?? null;
                        $eTimestamp   = $embed['timestamp']          ?? null;
                        $eColor       = $embed['color']              ?? null;
                    @endphp

                    <div class="dc-embed" style="border-left-color: {{ $eColor ?? '#1e1f22' }}">
                        <div class="dc-embed-body">
                            <div class="dc-embed-grid {{ $eThumbnail ? 'has-thumbnail' : '' }}">

                                <div class="dc-embed-content">

                                    {{-- Author --}}
                                    @if($eAuthorName)
                                        <div class="dc-embed-author">
                                            @if($eAuthorIcon)
                                                <img src="{{ $eAuthorIcon }}" class="dc-embed-author-icon" alt="">
                                            @endif
                                            @if($eAuthorUrl)
                                                <a href="{{ $eAuthorUrl }}" target="_blank" class="dc-embed-author-name dc-link">{{ $eAuthorName }}</a>
                                            @else
                                                <span class="dc-embed-author-name">{{ $eAuthorName }}</span>
                                            @endif
                                        </div>
                                    @endif

                                    {{-- Title --}}
                                    @if($eTitle)
                                        @if($eTitleUrl)
                                            <a href="{{ $eTitleUrl }}" target="_blank" class="dc-embed-title">{{ $eTitle }}</a>
                                        @else
                                            <div class="dc-embed-title">{{ $eTitle }}</div>
                                        @endif
                                    @endif

                                    {{-- Description --}}
                                    @if($eDesc)
                                        <div class="dc-embed-desc">{!! nl2br(e($eDesc)) !!}</div>
                                    @endif

                                    {{-- Fields --}}
                                    @if(!empty($eFields))
                                        <div class="dc-embed-fields">
                                            @foreach($eFields as $field)
                                                <div class="dc-embed-field {{ !empty($field['inline']) ? 'inline' : '' }}">
                                                    <div class="dc-embed-field-name">{{ $field['name'] ?? '' }}</div>
                                                    <div class="dc-embed-field-value">{!! nl2br(e($field['value'] ?? '')) !!}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                </div>

                                {{-- Thumbnail --}}
                                @if($eThumbnail)
                                    <img src="{{ $eThumbnail }}" class="dc-embed-thumbnail" alt="">
                                @endif

                            </div>{{-- /.dc-embed-grid --}}

                            {{-- Large image --}}
                            @if($eImage)
                                <img src="{{ $eImage }}" class="dc-embed-image" alt="">
                            @endif

                            {{-- Footer --}}
                            @if($eFooterText || $eTimestamp)
                                <div class="dc-embed-footer">
                                    @if($eFooterIcon)
                                        <img src="{{ $eFooterIcon }}" class="dc-embed-footer-icon" alt="">
                                    @endif
                                    <span class="dc-embed-footer-text">
                                        @if($eFooterText){{ $eFooterText }}@endif
                                        @if($eFooterText && $eTimestamp)<span class="dc-embed-footer-sep">•</span>@endif
                                        @if($eTimestamp){{ $eTimestamp }}@endif
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
