<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use Filament\Widgets\BarChartWidget;

class BusinessTypeChart extends BarChartWidget
{
    protected static ?string $heading = 'Business Type';


    protected function getData(): array
    {

        $freelanceCount = Partner::where('business_type','Freelance')->count();
        $workspaceCount = Partner::where('business_type','Workspace')->count();
        return [
            'datasets' => [
                [
                    'label' => 'Business Type',
                    'data' => [$freelanceCount, $workspaceCount],
                ],
            ],
            'labels' => ['Freelance', 'Workspace'],
        ];
    }
}
