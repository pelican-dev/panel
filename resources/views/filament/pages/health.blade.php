<x-filament-panels::page>
    @if (count($checkResults?->storedCheckResults ?? []))
		<x-filament::grid :default="1" :sm="2" @class(['gap-6 mb-5'])>
			@foreach ($checkResults->storedCheckResults as $result)
				<div class="flex items-start px-4 space-x-2 overflow-hidden py-5 text-opacity-0 transition transform bg-white shadow-md shadow-gray-200 dark:shadow-black/25 dark:shadow-md dark:bg-gray-800 rounded-xl sm:p-6 md:space-x-3 md:min-h-[130px] dark:border-t dark:border-gray-700">
					<x-health-status-indicator :result="$result" />
					<div>
						<dd class="-mt-1 font-bold text-gray-900 dark:text-white md:mt-1 md:text-xl">
							{{ $result->label }}
						</dd>
						<dt class="mt-0 text-sm font-medium text-gray-600 dark:text-gray-300 md:mt-1">
							@if (!empty($result->notificationMessage))
								{{ $result->notificationMessage }}
							@else
								{{ $result->shortSummary }}
							@endif
						</dt>
					</div>
				</div>
			@endforeach
		</x-filament::grid>
    @endif

    @if ($lastRanAt)
        <div class="{{ $lastRanAt->diffInMinutes() > 5 ? 'text-red-500' : 'text-gray-400 dark:text-gray-200' }} text-md text-center font-medium">
            Check results from {{ $lastRanAt->diffForHumans() }}
        </div>
    @endif
</x-filament-panels::page>
