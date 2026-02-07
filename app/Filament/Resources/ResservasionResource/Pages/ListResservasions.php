<?php

namespace App\Filament\Resources\ResservasionResource\Pages;

use App\Filament\Resources\ResservasionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListResservasions extends ListRecords
{
    protected static string $resource = ResservasionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
