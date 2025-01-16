<x-filament::modal id="{{ $getName() }}" :width="'xl'">
    <div class="text-xl font-bold text-center">
        {{ $getHeading() }}
    </div>

    <div class="text-lg text-center">
        {{ $getDescription() }}
    </div>

    <div class="w-full flex justify-center mt-3">
        @if ($actions = $getActions())
            <x-filament::actions
                    :actions="$actions"
            />
        @endif
    </div>
</x-filament::modal>
