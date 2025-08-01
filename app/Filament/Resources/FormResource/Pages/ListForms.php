<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
use App\Models\Form;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListForms extends ListRecords
{
    protected static string $resource = FormResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn() => Form::count())
                ->badgeColor('gray'),
            'draft' => Tab::make('Draft')
                ->badge(fn() => Form::draft()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->draft()),
            'published' => Tab::make('Published')
                ->badge(fn() => Form::published()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->published()),
            'archived' => Tab::make('Archived')
                ->badge(fn() => Form::archived()->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn(Builder $query) => $query->archived()),
            'trashed' => Tab::make('Trashed')
                ->badge(fn() => Form::onlyTrashed()->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed()),
        ];
    }
}
