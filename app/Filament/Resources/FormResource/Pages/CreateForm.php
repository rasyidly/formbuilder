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

        return $data;
    }

    protected function afterCreate(): void
    {
        $fields = array_values($this->data['fields'] ?? []);

        foreach ($fields as $index => $fieldData) {
            $this->createFormField($fieldData, $index);
        }
    }
}
