<?php

namespace App\Filament\Resources\CategoryResource\Widgets;

use Filament\Widgets\DoughnutChartWidget;

class CategoryChart extends DoughnutChartWidget
{
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        return [
            //
        ];
    }
}
