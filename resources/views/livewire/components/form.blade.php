<div>
    <form wire:submit="create" class="space-y-4">
        {{ $this->form }}

        @if (session('success'))
        <div class="relative px-4 py-3 text-teal-700 border border-teal-100 rounded-lg bg-teal-50" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
        @endif

        @if (session('success'))
        <x-filament::button disabled>Submitted!</x-filament::button>
        @else
        <x-filament::button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
            <span wire:loading>Submitting ...</span>
            <span wire:loading.remove>{{ $this->model->settings['submit_label'] ?? 'Submit' }}</span>
        </x-filament::button>
        @endif

    </form>

    <x-filament-actions::modals />
</div>