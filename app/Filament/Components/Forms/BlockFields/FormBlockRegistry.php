<?php

namespace App\Filament\Components\Forms\BlockFields;

class FormBlockRegistry
{
    public static function getAllBlocks(): array
    {
        return [
            TextInputBlock::make(),
            TextareaBlock::make(),
            EmailBlock::make(),
            NumberBlock::make(),
            SelectBlock::make(),
            RadioBlock::make(),
            CheckboxBlock::make(),
            SingleCheckboxBlock::make(),
            FileBlock::make(),
            DateBlock::make(),
            TimeBlock::make(),
            HiddenBlock::make(),
        ];
    }
}
