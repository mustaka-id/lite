<?php

namespace App\Providers\Filament;

use App\Filament\Admission\Pages;
use Filament\Enums\ThemeMode;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\MaxWidth;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdmissionPanelProvider extends AdminPanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('admission')
            ->path('ppdb')
            ->login(Pages\Login::class)
            ->profile(Pages\Account::class, isSimple: false)
            ->registration(Pages\Register::class)
            ->passwordReset()
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->collapsibleNavigationGroups(false)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->maxContentWidth(MaxWidth::Full)
            ->font('Onest')
            ->brandName('PPDB MA Ihsaniyya')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->discoverResources(in: app_path('Filament/Admission/Resources'), for: 'App\\Filament\\Admission\\Resources')
            ->discoverPages(in: app_path('Filament/Admission/Pages'), for: 'App\\Filament\\Admission\\Pages')
            ->discoverWidgets(in: app_path('Filament/Admission/Widgets'), for: 'App\\Filament\\Admission\\Widgets')
            ->discoverClusters(in: app_path('Filament/Admission/Clusters'), for: 'App\\Filament\\Admission\\Clusters')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->widgets([
                Widgets\AccountWidget::class,
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
            ])
            ->defaultThemeMode(ThemeMode::Light)
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->favicon(asset('/favicon.ico'));
    }
}
