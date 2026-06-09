<div class="fi-small-stat-block grid grid-flow-row w-full p-3 rounded-lg bg-white shadow-sm overflow-hidden overflow-x-auto ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
@if ($isCopyable($value = $getValue()))
    <span class="cursor-pointer" x-on:click="
        navigator.clipboard.writeText(@js($value));
        $tooltip(@js($getCopyMessage($value)), {
        theme: $store.theme,
        timeout: 2000,
    })">
@else
    <span>
@endif
        <span class="text-md font-medium text-gray-500 dark:text-gray-400">
            {{ $getLabel() }}
        </span>
        <span class="text-md font-semibold">
            {{ $value }}
        </span>
    </span>
</div>
