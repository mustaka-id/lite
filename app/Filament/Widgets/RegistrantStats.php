<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Admission\Registrant;
use App\Models\Admission\RegistrantBill;
use App\Models\Admission\RegistrantPayment;
use App\Models\SavingCycle;
use App\Models\SavingCycleMember;
use App\Models\Wallet;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class RegistrantStats extends BaseWidget
{
    protected static ?string $pollingInterval = '60s';

    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        return [
            Stat::make(__('Registrant total'), Registrant::count() . ' ' . __('Registrant'))
                ->description(__('Accepted') . ' ' . Registrant::whereNotNull('accepted_at')->count() . ' ' . __('students'))
                ->color('primary')
                ->icon('heroicon-o-users'),
            Stat::make(__('Unpaid bill amount'), 'Rp ' . number_format(RegistrantBill::whereHas('items')->withSum('items', 'amount')->get()->sum('items_sum_amount') - RegistrantPayment::sum('amount'), 0, ',', '.'))
                ->description(__('Total bill amount') . ' ' . 'Rp ' . number_format(RegistrantBill::whereHas('items')->withSum('items', 'amount')->get()->sum('items_sum_amount'), 0, ',', '.'))
                ->color('danger')
                ->icon('heroicon-o-document-duplicate'),
            Stat::make(__('Interview today'), Registrant::whereNotNull('meta->appointment_at')->whereDate('meta->appointment_at', '=', now()->format('Y-m-d'))->count())
                ->description(__('Upcoming interiew') . ' ' . Registrant::whereNotNull('meta->appointment_at')->whereDate('meta->appointment_at', '>=', now()->format('Y-m-d'))->count() . ' ' . __('registrants'))
                ->color('info')
                ->icon('heroicon-o-video-camera'),
        ];
    }

    public static function canView(): bool
    {
        return true;
        // return auth()->user()->role !== UserRole::Registrant && auth()->user()->role !== UserRole::Member;
    }
}
