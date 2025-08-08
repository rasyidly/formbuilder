<div>
    <form wire:submit="create" class="space-y-4">
        {{ $this->form }}

        @if (session('success'))
        <x-filament::button disabled>{{ session('success') }}</x-filament::button>
        @else
        <x-filament::button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
            <span wire:loading>Submitting ...</span>
            <span wire:loading.remove>Submit</span>
        </x-filament::button>
        @endif

    </form>

    <x-filament-actions::modals />
</div>