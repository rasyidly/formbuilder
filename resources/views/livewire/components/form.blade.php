<div>
    <form wire:submit="create" class="space-y-4">
        {{ $this->form }}

        <x-filament::button type="submit">
            Submit
        </x-filament::button>
    </form>

    <x-filament-actions::modals />
</div>