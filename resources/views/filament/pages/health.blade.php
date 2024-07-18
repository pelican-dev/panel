<x-filament-panels::page>
    @if (count($checkResults?->storedCheckResults ?? []))
		<x-filament::grid :default="1" :sm="2" @class(['gap-6 mb-5'])>
			@foreach ($checkResults->storedCheckResults as $result)
				<x-filament::section
					icon="{{
						match ($result->status) {
							Spatie\Health\Enums\Status::ok()->value => 'tabler-circle-check',
							Spatie\Health\Enums\Status::warning()->value => 'tabler-exclamation-circle',
							Spatie\Health\Enums\Status::skipped()->value => 'tabler-circle-arrow-right',
							default => 'tabler-circle-x'
						}
					}}"
					icon-color="{{
						match ($result->status) {
							Spatie\Health\Enums\Status::ok()->value => 'success',
							Spatie\Health\Enums\Status::warning()->value => 'warning',
							Spatie\Health\Enums\Status::skipped()->value => 'primary',
							default => 'danger'
						}
					}}"
				>
					<x-slot name="heading">{{ $result->label }}</x-slot>

					<p>
						@if (!empty($result->notificationMessage))
							{{ $result->notificationMessage }}
						@else
							{{ $result->shortSummary }}
						@endif
					</p>

				</x-filament::section>
			@endforeach
		</x-filament::grid>
    @endif

    @if ($lastRanAt)
        <div class="{{ $lastRanAt->diffInMinutes() > 5 ? 'text-red-500' : 'text-gray-400 dark:text-gray-200' }} text-md text-center font-medium">
            Check results from {{ $lastRanAt->diffForHumans() }}
        </div>
    @endif
</x-filament-panels::page>
