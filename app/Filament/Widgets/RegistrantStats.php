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

    protected function getStats(): array
    {
        return [
            Stat::make(__('Total registrant'), Registrant::count() . ' ' . __('Candidate'))
                ->description(__('Accepted') . ' ' . Registrant::whereNotNull('accepted_at')->count() . ' ' . __('students'))
                ->color('primary')
                ->icon('heroicon-o-users')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectToIndexMember()",
                ])
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
            Stat::make(__('Unpaid bill amount'), 'Rp ' . number_format(RegistrantBill::whereHas('items')->withSum('items', 'amount')->get()->sum('items_sum_amount') - RegistrantPayment::sum('amount'), 0, ',', '.'))
                ->description(__('Total bill amount') . ' ' . 'Rp ' . number_format(RegistrantBill::whereHas('items')->withSum('items', 'amount')->get()->sum('items_sum_amount'), 0, ',', '.'))
                ->color('primary')
                ->icon('heroicon-o-users')
                ->extraAttributes([
                    'class' => 'cursor-pointer',
                    'wire:click' => "redirectToIndexMember()",
                ])
                ->chart(array_map(fn() => rand(1, 100), range(1, 7))),
        ];
    }

    public static function canView(): bool
    {
        return true;
        // return auth()->user()->role !== UserRole::Candidate && auth()->user()->role !== UserRole::Member;
    }

    public function redirectToIndexMember()
    {
        return redirect()->route('filament.admin.resources.members.index');
    }
    public function redirectToIndexWallet()
    {

        return redirect()->route('filament.admin.resources.wallets.index');
    }
    public function redirectToIndexSavingCycle()
    {
        return redirect()->route('filament.admin.resources.saving-cycles.index');
    }
}
