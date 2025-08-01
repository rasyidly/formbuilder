<?php

namespace App\Filament\Resources\SubmissionResource\Pages;

use App\Filament\Resources\SubmissionResource;
use App\Models\Submission;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListSubmissions extends ListRecords
{
    protected static string $resource = SubmissionResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(fn () => Submission::count())
                ->badgeColor('gray'),
            'trashed' => Tab::make('Trashed')
                ->badge(fn () => Submission::onlyTrashed()->count())
                ->badgeColor('gray')
                ->modifyQueryUsing(fn (Builder $query) => $query->onlyTrashed()),
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
