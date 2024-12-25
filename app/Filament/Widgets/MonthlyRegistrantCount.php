<?php

namespace App\Filament\Widgets;

use App\Enums\UserRole;
use App\Models\Admission\Registrant;
use App\Models\Member;
use Filament\Support\Colors\Color;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class MonthlyRegistrantCount extends ChartWidget
{
    protected static ?int $sort = 2;

    public function getHeading(): string
    {
        return __('Monthly registrant');
    }

    protected function getData(): array
    {
        $registrant = Trend::model(Registrant::class)
            ->between(
                start: now()->startOfYear(),
                end: now()->endOfYear(),
            )
            ->perMonth()
            ->count();

        // $member = Trend::model(Member::class)
        //     ->query(Member::member())
        //     ->between(
        //         start: now()->startOfYear(),
        //         end: now()->endOfYear(),
        //     )
        //     ->perMonth()
        //     ->count();

        return [
            'datasets' => [
                [
                    'label' => __('Registrant'),
                    'data' => $registrant->map(fn(TrendValue $value) => $value->aggregate),
                ],
                // [
                //     'label' => __('Member'),
                //     'backgroundColor' => '#0284c7',
                //     'borderColor' => '#0284c7',
                //     'data' => $member->map(fn(TrendValue $value) => $value->aggregate),
                // ],
            ],
            'labels' => $registrant->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    public static function canView(): bool
    {
        return true;
        // return auth()->user()->role !== UserRole::registrant && auth()->user()->role !== UserRole::Member;
    }
}
