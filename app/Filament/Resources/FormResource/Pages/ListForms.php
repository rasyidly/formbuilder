<?php

namespace App\Filament\Resources\FormResource\Pages;

use App\Filament\Resources\FormResource;
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
                ->badge(fn() => \App\Models\Form::count())
                ->badgeColor('gray'),
            'draft' => Tab::make('Draft')
                ->badge(fn() => \App\Models\Form::draft()->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn(Builder $query) => $query->draft()),
            'published' => Tab::make('Published')
                ->badge(fn() => \App\Models\Form::published()->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn(Builder $query) => $query->published()),
            'archived' => Tab::make('Archived')
                ->badge(fn() => \App\Models\Form::archived()->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn(Builder $query) => $query->archived()),
            'trashed' => Tab::make('Trashed')
                ->badge(fn() => \App\Models\Form::onlyTrashed()->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn(Builder $query) => $query->onlyTrashed()),
        ];
    }
}
