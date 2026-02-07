<?php

namespace App\Filament\Widgets;

use App\Models\Resservasion;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        $ressDone = Resservasion::where('status','done')->count();
        $ressCancel = Resservasion::where('status','canceled')->count();
        return [
            Card::make('Completed Resservasions', $ressDone)
                ->description('20% increase')
                ->descriptionIcon('heroicon-s-trending-up')
                ->color('success'),
            Card::make('Canceled Resservasions', $ressCancel)
                ->description('7% increase')
                ->descriptionIcon('heroicon-s-trending-down')
                ->color('danger')
        ];
    }
}
