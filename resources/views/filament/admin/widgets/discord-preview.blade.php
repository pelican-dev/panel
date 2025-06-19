<x-filament-widgets::widget>
    @assets
        <style>
            :root {
                --discord-embed-background-color: #13162a;
                --discord-tag-color: #5865f2;
                --discord-timestamp-color: #949ba4;
                --discord-text-color: #dbdee1;
                --discord-link-color: #00a8fc;
                --discord-avatar-margin-right: 8px;
                --discord-thumbnail-margin-right: 8px;
                --discord-footer-margin-top: 8px;
                --discord-spacer-margin-left: 4px;
                --discord-avatar-length: 40px;
                --discord-avatar-height: 40px;
            }

            .container {
                background-color: #11131f !important;
            }

            .link {
                color: var(--discord-link-color);
            }

            .link:hover {
                text-decoration: underline;
            }

            img:hover {
                cursor: pointer !important;
            }

            .sender .avatar {
                border: 1px solid rgba(0, 0, 0, 0.2);
            }


            .sender .name {
                display: inline;
                vertical-align: baseline;
                margin: 0px 0.25rem 0px 0px;
                color: var(--color-white);
                font-size: 1rem;
                font-weight: 500;
                line-height: 1.375rem;
                overflow-wrap: break-word;
                cursor: pointer;
            }

            .sender .tag {
                min-height: 1.275em;
                max-height: 1.275em;
                margin: 0.075em 0.25rem 0px 0px;
                padding: 0.071875rem 0.275rem;
                border-radius: 3px;
                background: var(--discord-tag-color);
                font-size: .8rem;
                font-weight: 500;
                line-height: 1.3;
                vertical-align: baseline;
                text-transform: uppercase;
            }

            .sender .timestamp {
                display: inline-block;
                height: 1.25rem;
                cursor: default;
                color: var(--discord-timestamp-color);
                margin-left: 0.25rem;
                font-size: 0.75rem;
                font-weight: 500;
                line-height: 1.375rem;
                vertical-align: baseline;
            }

            .embed {
                border-left: 5px solid;
                background-color: var(--discord-embed-background-color) !important;
            }

            .avatar,
            .footer-icon {
                margin-right: var(--discord-avatar-margin-right);
            }

            .thumbnail {
                width: 64px;
                height: 64px;
                object-fit: cover;
                position: absolute;
                top: 50%;
                right: 0;
                transform: translateY(-15%);
            }

            .description,
            .field-value {
                color: var(--discord-text-color);
            }

            .footer {
                margin-top: var(--discord-footer-margin-top);
                color: var(--discord-text-color);
            }

            .spacer {
                margin-left: var(--discord-spacer-margin-left);
            }


        </style>
    @endassets
    <div class="container mx-auto p-4 bg-gray-800 p-4 rounded-lg shadow-lg w-full max-w-full flex items-start mb-4 sender">
        <div class="relative" style="width: 44px; min-width: 44px; height: 44px; margin-right: 12px;">
            @if($avatar = $sender['avatar'])
                <img
                    src="{{ $avatar }}"
                    alt="Avatar"
                    class="w-full h-full rounded-full object-cover absolute top-0 left-0 z-10 avatar"
                    >
            @endif
        </div>

        <div class="flex flex-col flex-grow">
            <div class="flex items-center space-x-2">
                <h1 class="font-bold text-white name">{{ data_get($sender, 'name') }}</h1>
                @if(!data_get($sender, 'human'))
                    <span class="text-white text-xs rounded-md tag">app</span>
                @endif
                <span class="timestamp text-xs">{{ $getTime }}</span>
            </div>

            @if(filled($content))
                <p class="text-gray-300 break-words">{!! nl2br($content) !!}</p>
            @endif

            @foreach($embeds as $embed)
                @php
                    $name = $embed['author']['name'] ?? null;
                    $thumbnail = $embed['thumbnail']['url'] ?? null;
                    $author_icon_url = $embed['author']['icon_url'] ?? null;
                    $author_url = $embed['author']['url'] ?? null;
                    $footer_icon_url = $embed['footer']['icon_url'] ?? null;
                    $footer_text = $embed['footer']['text'] ?? null;
                    $footer_timestamp = $embed['timestamp'] ?? null;
                @endphp
                <div class="p-3 mt-3 rounded-lg w-full max-w-full embed relative" style="border-color: #{{ dechex(data_get($embed, 'color')) }}">
                    @if($name || $thumbnail)
                        <div class="flex items-start mb-0 relative" style="height: auto; overflow: visible;">
                            @if($author_icon_url || $name)
                                <div class="flex items-center">
                                    @if($author_icon_url)
                                        <img src="{{ $author_icon_url }}" alt="Author Avatar" class="w-8 h-8 rounded-full mr-2 object-cover avatar">
                                    @endif
                                    @if($author_url)
                                        {!! $link($author_url, $name ? '<h2 class="font-bold text-lg whitespace-nowrap">' . e($name) . '</h2>' : '') !!}
                                    @elseif($name)
                                        <h2 class="font-bold text-lg whitespace-nowrap">{{ $name }}</h2>
                                    @endif
                                </div>
                            @endif
                            @if($thumbnail)
                                <img src="{{ $thumbnail }}" alt="Embed Thumbnail" class="thumbnail rounded-lg">
                            @endif
                        </div>
                    @endif

                    @if($title = data_get($embed, 'title'))
                        {!! $link(
                            $url = data_get($embed, 'url'),
                            '<h3 class="font-bold text-lg break-words mb-0">' . e($title) . '</h3>'
                        ) !!}
                    @endif

                    @if($description = data_get($embed, 'description'))
                        <p class="break-words description mt-0">{!! nl2br($description) !!}</p>
                    @endif

                    @if($fields = data_get($embed, 'fields'))
                        <div class="mt-2 w-full">
                            @foreach($fields as $field)
                                <div class="mb-2 w-full">
                                    <strong class="break-words mt-2">{{ data_get($field, 'name') }}</strong>
                                    <span class="break-words field-value">{{ data_get($field, 'value') }}</span>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    @if($image = data_get($embed, 'image.url'))
                        <img src="{{ $image }}" alt="Embed Image" class="object-contain mt-3 w-full">
                    @endif

                    @if($footer_text || $footer_timestamp)
                        <div class="flex items-center text-sm mt-4 footer">
                            @if($footer_icon_url)
                                <img src="{{ $footer_icon_url }}" alt="Footer Icon" class="w-5 h-5 rounded-full mr-2 object-cover footer-icon">
                            @endif

                                <div class="flex space-x-1">
                                    @if($footer_text)
                                        <p class="break-words">{!! nl2br($footer_text) !!}</p>
                                    @endif

                                    @if($footer_timestamp)
                                        <span class="timestamp">
                                            @if($footer_text)
                                                <span class="spacer">•</span>
                                            @endif
                                            {{ $footer_timestamp }}
                                        </span>
                                    @endif
                                </div> 
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    
</x-filament-widgets::widget>