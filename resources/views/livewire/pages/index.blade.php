<div class="max-w-2xl py-8 mx-auto">
    <h1 class="mb-6 text-2xl font-bold">Published Forms</h1>
    @if($forms->isEmpty())
    <p class="text-gray-500">No published forms available.</p>
    @else
    <ul class="space-y-4">
        @foreach($forms as $form)
        <li>
            <x-filament::section>
                <h2 class="text-lg font-semibold">{{ $form->name }}</h2>
                @if($form->description)
                <div class="prose dark:prose-invert line-clamp-2">
                    {!! $form->description !!}
                </div>
                @endif
                <br />
                <x-filament::button tag="a" href="{{ route('forms.show', $form->slug) }}">View Form</x-filament::button>
            </x-filament::section>
        </li>
        @endforeach
    </ul>
    @endif
</div>