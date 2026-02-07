<div>
    <h1>{{ __('passkeys::passkeys.passkeys') }}</h1>
    <div class="mt-2">
        <form id="passkeyForm" wire:submit="validatePasskeyProperties" class="flex items-center space-x-2">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700">{{ __('passkeys::passkeys.name') }}</label>
                <input autocomplete="off" type="text" wire:model="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                @error('name')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="mt-6 inline-flex justify-center py-2 px-4 font-medium">
                {{ __('passkeys::passkeys.create') }}
            </button>
        </form>
    </div>

    <div class="mt-6">
        <ul class="space-y-4">
            @foreach($passkeys as $passkey)
                <li class="flex justify-between items-center p-4 bg-gray-100 rounded-lg shadow-sm">
                    <div class="text-gray-700">
                        {{ $passkey->name }}
                    </div>
                    <div class="ml-2">
                        {{ __('passkeys::passkeys.last_used') }}: {{ $passkey->last_used_at?->diffForHumans() ?? __('passkeys::passkeys.not_used_yet') }}
                    </div>


                    <div>
                        <button wire:click="deletePasskey({{ $passkey->id }})" class="inline-flex justify-center py-2 px-4 text-sm font-medium text-white bg-red-600">
                            {{ __('passkeys::passkeys.delete') }}
                        </button>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</div>

@include('passkeys::livewire.partials.createScript')
