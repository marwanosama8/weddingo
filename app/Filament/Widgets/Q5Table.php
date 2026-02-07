<?php

namespace App\Filament\Widgets;

use App\Models\Partner;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class Q5Table extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Partner::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('business_name'),
            Tables\Columns\TextColumn::make('about_us_survey')
                ->label('Q5'),
        ];
    }
}
