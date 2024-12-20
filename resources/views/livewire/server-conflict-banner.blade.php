@php
    $shouldShow = false;

    try {
        $server->validateCurrentState();
    } catch (\App\Exceptions\Http\Server\ServerStateConflictException $exception) {
        $shouldShow = true;
        $message = $exception->getMessage();
    }
@endphp

<div id="server-conflict-banner">
    @if ($shouldShow)
        <div class="mt-2 p-2 rounded-lg text-white" style="background-color: #D97706;">
            <div class="flex items-center">
                <x-filament::icon icon="tabler-alert-triangle" class="h-6 w-6 mr-2 text-gray-500 dark:text-gray-400 text-white" />
                <p>{!! $message !!}</p>
            </div>
        </div>
    @endif
</div>
