<x-filament-panels::page>
    @if (count($checkResults?->storedCheckResults ?? []))
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-5">
            @foreach ($checkResults->storedCheckResults as $result)
                <div class="flex items-start px-4 py-5 space-x-2 md:space-x-3 overflow-hidden shadow-lg rounded-xl bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10 sm:p-6">
                    <div class="flex justify-center items-center rounded-full p-2 mr-2 {{ $this->backgroundColor($result->status) }}">
                        <x-filament::icon icon="{{ $this->icon($result->status) }}" class="h-6 w-6 {{ $this->iconColor($result->status) }}" />
                    </div>
                    <div>
                        <dd class="-mt-1 font-bold md:mt-1 md:text-xl text-gray-900 dark:text-white">
                            {{ trans('admin/health.results.' . preg_replace('/\s+/', '', mb_strtolower($result->label)) . '.label') }}
                        </dd>
                        <dt class="mt-0 text-sm font-medium md:mt-1 text-gray-600 dark:text-gray-300">
                            @if (!empty($result->notificationMessage))
                                {{ $result->notificationMessage }}
                            @else
                                {{ $result->shortSummary }}
                            @endif
                        </dt>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if ($lastRanAt)
        <div class="text-md text-center font-medium {{ $lastRanAt->diffInMinutes() > 5 ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-200' }}">
            {{ trans('admin/health.checked', ['time' => $lastRanAt->diffForHumans()]) }}
        </div>
    @endif
</x-filament-panels::page>
