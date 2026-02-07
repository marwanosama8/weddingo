<?php

namespace App\Filament\Widgets;

use App\Models\Category;
use Filament\Widgets\DoughnutChartWidget;

class CategoryChart extends DoughnutChartWidget
{
    protected static ?array $options = [
        'plugins' => [
            'legend' => [
                'display' => false,
            ],
            'styling' => [
                'color' => [
                    'backgroundColor' => 'rgba(245, 153, 154, 1)'   
                ],
            ],
        ],
    ];
    protected static ?string $maxHeight = '300px';

    protected static ?string $heading = 'Most Visited Categories';

    protected function getData(): array
    {
        $category = Category::orderBy('id', 'asc')->get();
        return [
            'datasets' => [
                [
                    'label' => 'Blog posts created',
                    'data' =>   $category->pluck('viewes_count')
                ],
            ],
            'labels' => $category->pluck('name'),
        ];
    }
}
