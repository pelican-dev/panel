<x-filament-widgets::widget>
    @assets
        <link rel="stylesheet" href="{{ asset('/css/filament/admin/discord-preview.css') }}">
    @endassets
    <div class="container mx-auto p-4 bg-gray-800 p-4 rounded-lg shadow-lg w-full max-w-full flex items-start mb-4 sender">
        <div class="relative" style="width: 44px; min-width: 44px; height: 44px; margin-right: 12px;">
            @if($avatar = data_get($sender, 'avatar'))
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
                    $name = $name = data_get($embed, 'author.name');
                    $thumbnail = data_get($embed, 'thumbnail.url');
                    $author_icon_url = data_get($embed, 'author.icon_url');
                    $author_url = data_get($embed, 'author.url');

                    $footer_icon_url = data_get($embed, 'footer.icon_url');
                    $footer_text = data_get($embed, 'footer.text');
                    $footer_timestamp = data_get($embed, 'timestamp');
                @endphp
                <div class="p-3 mt-3 rounded-lg w-full max-w-full embed relative" style="border-color: #{{ dechex(data_get($embed, 'color')) }}">
                    @if($name || $thumbnail)
                        <div class="flex items-start mb-0 relative" style="height: auto; overflow: visible;">
                            @if($author_icon_url || $name)
                                <div class="flex items-center">
                                    @if($author_icon_url)
                                        <img src="{{ $author_icon_url }}" alt="Author Avatar" class="w-8 h-8 rounded-full mr-2 object-cover avatar">
                                    @endif
                                        {!! $link($author_url, <<<HTML
                                            @if($name)
                                                <h2 class="font-bold text-lg whitespace-nowrap">$name</h2>
                                            @endif
                                        HTML) !!}
                                </div>
                            @endif
                            @if($thumbnail)
                                <img src="{{ $thumbnail }}" alt="Embed Thumbnail" class="thumbnail rounded-lg">
                            @endif
                        </div>
                    @endif

                    @if($title = data_get($embed, 'title'))
                        {!! $link($url = data_get($embed, 'url'), <<<HTML
                            <h3 class="font-bold text-lg break-words mb-0">$title</h3>
                        HTML) !!}
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

                            @if($footer_text || $footer_timestamp)
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
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</x-filament-widgets::widget>