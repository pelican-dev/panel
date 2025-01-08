@php
    if(! function_exists('backgroundColor')) {
        function backgroundColor($status) {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'bg-success-100 dark:bg-success-200',
                Spatie\Health\Enums\Status::warning()->value => 'bg-warning-100 dark:bg-warning-200',
                Spatie\Health\Enums\Status::skipped()->value => 'bg-info-100 dark:bg-info-200',
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'bg-danger-100 dark:bg-danger-200',
                default => 'bg-gray-100 dark:bg-gray-200'
            };
        }
    }

    if(! function_exists('iconColor')) {
        function iconColor($status)
        {
            return match ($status) {
                Spatie\Health\Enums\Status::ok()->value => 'text-success-500 dark:text-success-600',
                Spatie\Health\Enums\Status::warning()->value => 'text-warning-500 dark:text-warning-600',
                Spatie\Health\Enums\Status::skipped()->value => 'text-info-500 dark:text-info-600',
                Spatie\Health\Enums\Status::failed()->value, Spatie\Health\Enums\Status::crashed()->value => 'text-danger-500 dark:text-danger-600',
                default => 'text-gray-500 dark:text-gray-600'
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
                <div class="flex items-start px-4 py-5 space-x-2 md:space-x-3 overflow-hidden shadow-lg rounded-xl bg-white dark:bg-gray-900 ring-1 ring-gray-950/5 dark:ring-white/10 sm:p-6">
                    <div class="flex justify-center items-center rounded-full p-2 mr-2 {{ backgroundColor($result->status) }}">
                        <x-filament::icon icon="{{ icon($result->status) }}" class="h-6 w-6 {{ iconColor($result->status) }}" />
                    </div>
                    <div>
                        <dd class="-mt-1 font-bold md:mt-1 md:text-xl text-gray-900 dark:text-white">
                            {{ $result->label }}
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
        </x-filament::grid>
    @endif

    @if ($lastRanAt)
        <div class="text-md text-center font-medium {{ $lastRanAt->diffInMinutes() > 5 ? 'text-red-500 dark:text-red-400' : 'text-gray-400 dark:text-gray-200' }}">
            Check results from {{ $lastRanAt->diffForHumans() }}
        </div>
    @endif
</x-filament-panels::page>
