<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Widgets;
use Filament\Forms;
use Filament\Infolists;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables;
use Filament\Tables\Enums\FiltersLayout;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->profile()
            ->passwordReset(null)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->collapsibleNavigationGroups(false)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->font('Onest')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        Tables\Actions\ActionGroup::configureUsing(fn(Tables\Actions\ActionGroup $group) => $group->color('white'));

        Tables\Actions\CreateAction::configureUsing(fn(Tables\Actions\CreateAction $action) => $action->modalWidth(MaxWidth::ExtraLarge));
        Tables\Actions\ViewAction::configureUsing(fn(Tables\Actions\ViewAction $action) => $action->modalWidth(MaxWidth::Large)->hiddenLabel());
        Tables\Actions\EditAction::configureUsing(fn(Tables\Actions\EditAction $action) => $action->modalWidth(MaxWidth::Large)->hiddenLabel()->color('gray'));

        Forms\Components\DateTimePicker::configureUsing(fn(Forms\Components\DateTimePicker $picker) => $picker->seconds(false));
        Forms\Components\Select::configureUsing(fn(Forms\Components\Select $select) => $select->native(false));

        Forms\Components\Component::configureUsing(fn(Forms\Components\Component $component) => $component->translateLabel());
        Tables\Columns\Column::configureUsing(fn(Tables\Columns\Column $column) => $column->placeholder('None')->translateLabel());
        Infolists\Components\Entry::configureUsing(fn(Infolists\Components\Entry $select) => $select->translateLabel());

        Tables\Table::configureUsing(
            fn(Tables\Table $table) => $table
                ->filtersLayout(FiltersLayout::AboveContent)
                ->paginationPageOptions([10, 25, 50, 100])
        );
    }
}
