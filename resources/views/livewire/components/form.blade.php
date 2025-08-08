<div>
    <form wire:submit="create" class="space-y-4">
        {{ $this->form }}

        @if (session('success'))
        <x-filament::button disabled>{{ session('success') }}</x-filament::button>
        @else
        <x-filament::button type="submit" wire:loading.attr="disabled">Submit</x-filament::button>
        @endif

    </form>

    <x-filament-actions::modals />
</div>