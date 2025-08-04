<div class="container flex flex-col max-w-4xl px-4 py-8 mx-auto space-y-6">
    <x-filament::section>
        <h1 class="mb-2 text-2xl font-bold">{{ $form->name }}</h1>
        @if($form->description)
        <div class="prose dark:prose-invert">
            {!! $form->description !!}
        </div>
        @endif
    </x-filament::section>
    <x-filament::section>
        @livewire('components.form', ['form' => $form])
    </x-filament::section>
</div>