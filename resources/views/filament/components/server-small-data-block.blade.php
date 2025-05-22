<{{$shouldCopyOnClick() ? 'button' : 'div' }} class="fi-small-stat-block grid grid-flow-row w-full p-3 rounded-lg bg-white shadow-sm overflow-hidden overflow-x-auto ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10 justify-start" type="button" wire:click="copyClick('{{ $getValue() }}')">
    <span>
        <span class="text-md font-medium text-gray-500 dark:text-gray-400">
            {{ $getLabel() }}
        </span>
        <span class="text-md font-semibold">
            {{ $getValue() }}
        </span>
    </span>
</{{$shouldCopyOnClick() ? 'button' : 'div' }}>
