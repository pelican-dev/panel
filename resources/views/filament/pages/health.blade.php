@php
    if(! function_exists('backgroundColor')) {
        function backgroundColor($status) {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'background-color: rgb(209 250 229);', // bg-emerald-100
                Spatie\Health\Enums\Status::warning()->value => 'background-color: rgb(254 249 195);', // bg-yellow-100
                Spatie\Health\Enums\Status::skipped()->value => 'background-color: rgb(219 234 254);', // bg-blue-100
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'background-color: rgb(254 226 226);', // bg-red-100
                default => 'background-color: rgb(243 244 246);' // bg-gray-100
            };
        }
    }

    if(! function_exists('iconColor')) {
        function iconColor($status)
        {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'color: rgb(16 185 129);', // text-emerald-500
                Spatie\Health\Enums\Status::warning()->value => 'color: rgb(234 179 8);', // text-yellow-500
                Spatie\Health\Enums\Status::skipped()->value => 'color: rgb(59 130 246);', // text-blue-500
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'color: rgb(239 68 68);', // text-red-500
                default => 'color: rgb(107 114 128);' // text-gray-500
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
                default => 'tabler-help-circle'
            };
        }
    }
@endphp

<x-filament-panels::page>
    @if (count($checkResults?->storedCheckResults ?? []))
		<x-filament::grid default="1" sm="2" class="gap-6 mb-5">
			@foreach ($checkResults->storedCheckResults as $result)
				<div class="flex items-start px-4 space-x-2 overflow-hidden py-5 text-opacity-0 transition transform bg-white shadow-md shadow-gray-200 dark:shadow-black/25 dark:shadow-md dark:bg-gray-800 rounded-xl sm:p-6 md:space-x-3 md:min-h-[130px] dark:border-t dark:border-gray-700">
					<div class="flex justify-center items-center rounded-full p-2" style="margin-right: 0.5rem; {{ backgroundColor($result->status) }}">
                        <x-filament::icon icon="{{ icon($result->status) }}" class="h-6 w-6" style="{{ iconColor($result->status) }}" />
					</div>
					<div>
						<dd class="-mt-1 font-bold md:mt-1 md:text-xl" style="color: light-dark(rgb(17 24 39), rgb(255 255 255));">
							{{ $result->label }}
						</dd>
						<dt class="mt-0 text-sm font-medium md:mt-1" style="color: light-dark(rgb(75 85 99), rgb(209 213 219));">
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
        <div class="text-md text-center font-medium" style="{{ $lastRanAt->diffInMinutes() > 5 ? 'color: rgb(239 68 68);' : 'color: light-dark(rgb(156 163 175), rgb(229 231 235));' }}">
            Check results from {{ $lastRanAt->diffForHumans() }}
        </div>
    @endif
</x-filament-panels::page>
