<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use App\Filament\Resources\FormResource\Concerns\ManagesFormFields;
use Filament\Resources\Pages\CreateRecord;

class CreateForm extends CreateRecord
{
    use ManagesFormFields;

    protected static string $resource = FormResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        unset($data['fields']);

        $data['published_at'] = $data['published_at'] ? now() : null;

        return $data;
    }

    protected function afterCreate(): void
    {
        $fields = array_values($this->data['fields'] ?? []);
        $this->upsertFormFields($fields, $this->record->id);
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }
}
