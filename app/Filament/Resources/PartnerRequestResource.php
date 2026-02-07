<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerRequestResource\Pages;
use App\Filament\Resources\PartnerRequestResource\RelationManagers;
use App\Models\Partner;
use App\Models\PartnerRequest;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;

class PartnerRequestResource extends Resource
{
    protected static ?string $model = PartnerRequest::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('partner_id')
                    ->label('Partner')
                    ->options(Partner::all()->pluck('business_name', 'id'))
                    ->searchable(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('partner.user.name')->label('Partner Name'),
                Tables\Columns\TextColumn::make('partner.points')->label('Partner Points'),
                Tables\Columns\TextColumn::make('subscription.name')->label('Subscription Requset Name'),
                Tables\Columns\TextColumn::make('subscription.subscription_request')->label('Subscription Requset Limit'),

            ])
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                Action::make('Accept')
                    ->url(fn (PartnerRequest $record): string => route('sub.accept', $record))
                    ->color('success')
                    ->icon('heroicon-o-exclamation-circle')
                    ->visible(fn (PartnerRequest $record): bool => $record->accepted == false),

            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPartnerRequests::route('/'),
            'create' => Pages\CreatePartnerRequest::route('/create'),
            'edit' => Pages\EditPartnerRequest::route('/{record}/edit'),
        ];
    }
}
