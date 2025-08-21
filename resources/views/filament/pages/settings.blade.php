@php
/** @var \Filament\Pages\Page $this */
@endphp

<x-filament::page>
    <div style="--cols-default: repeat(1, minmax(0, 1fr)); --cols-lg: repeat(5, minmax(0, 1fr));" class="grid grid-cols-[--cols-default] lg:grid-cols-[--cols-lg] fi-fo-component-ctn gap-6">
        <div style="--col-span-default: 1 / -1; --col-span-lg: span 3 / span 3;" class="col-[--col-span-default] lg:col-[--col-span-lg] space-y-4">
            <x-filament::section heading="MAIL Configuration">
                <form wire:submit="save" class="space-y-4">
                    {{ $this->form }}
                    <x-filament::button type="submit" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                        <span wire:loading>Saving changes</span>
                        <span wire:loading.remove>Save changes</span>
                    </x-filament::button>
                </form>
            </x-filament::section>
            <x-filament::section heading="Test SMTP Settings">
                <div class="space-y-4">
                    <x-filament::input.wrapper>
                        <x-filament::input wire:model.defer="test_to_email" id="test_to_email" type="email" placeholder="you@example.com" />
                    </x-filament::input.wrapper>
                    <x-filament::button wire:click.prevent="sendTestEmail" color="primary">Send test email</x-filament::button>
                </div>
            </x-filament::section>
        </div>
        <div style="--col-span-default: 1 / -1; --col-span-lg: span 2 / span 2;" class="col-[--col-span-default] lg:col-[--col-span-lg]">
        </div>
    </div>
</x-filament::page>