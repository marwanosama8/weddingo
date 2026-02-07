<?php

namespace App\Filament\Resources\ResservasionResource\Pages;

use App\Filament\Resources\ResservasionResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditResservasion extends EditRecord
{
    protected static string $resource = ResservasionResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
