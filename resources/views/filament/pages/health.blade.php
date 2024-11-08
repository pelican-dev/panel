@php
    if(! function_exists('backgroundColor')) {
        function backgroundColor($status) {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'bg-emerald-100',
                Spatie\Health\Enums\Status::warning()->value => 'bg-yellow-100',
                Spatie\Health\Enums\Status::skipped()->value => 'bg-blue-100',
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'bg-red-100',
                default => 'bg-gray-100'
            };
        }
    }

    if(! function_exists('iconColor')) {
        function iconColor($status)
        {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'text-emerald-500',
                Spatie\Health\Enums\Status::warning()->value => 'text-yellow-500',
                Spatie\Health\Enums\Status::skipped()->value => 'text-blue-500',
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'text-red-500',
                default => 'text-gray-500'
            };
        }
    }

    if(! function_exists('icon')) {
        function icon($status)
        {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'tabler-circle-check',
                Spatie\Health\Enums\Status::warning()->value => 'tabler-exclamation-circle',
                Spatie\Health\Enums\Status::skipped()->value => 'tabler-circle-chevron-right',
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'tabler-circle-x',
                default => ''
            };
        }
    }
@endphp

<x-filament-panels::page>
    @if (count($checkResults?->storedCheckResults ?? []))
		<x-filament::grid default="1" sm="2" class="gap-6 mb-5">
			@foreach ($checkResults->storedCheckResults as $result)
				<div class="flex items-start px-4 space-x-2 overflow-hidden py-5 text-opacity-0 transition transform bg-white shadow-md shadow-gray-200 dark:shadow-black/25 dark:shadow-md dark:bg-gray-800 rounded-xl sm:p-6 md:space-x-3 md:min-h-[130px] dark:border-t dark:border-gray-700">
					<div class="flex justify-center items-center rounded-full p-2 {{ backgroundColor($result->status) }}">
						@svg(icon($result->status), "h-6 w-6 {{ iconColor($result->status) }}")
					</div>
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
