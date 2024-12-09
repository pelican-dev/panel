@php
    $shouldShow = false;

    try {
        \Filament\Facades\Filament::getTenant()->validateCurrentState();
    } catch (\App\Exceptions\Http\Server\ServerStateConflictException $exception) {
        $shouldShow = true;
        $message = $exception->getMessage();
    }
@endphp

@if ($shouldShow)
    <div class="mt-2 p-2 rounded-lg text-white" style="background-color: #D97706;">
        <div class="flex items-center">
            <x-filament::icon icon="tabler-alert-triangle" class="h-6 w-6 mr-2 text-gray-500 dark:text-gray-400 text-white" />
            <p>{!! $message !!}</p>
        </div>
    </div>
@endif