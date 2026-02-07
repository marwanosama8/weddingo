<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ResservasionResource\Pages;
use App\Filament\Resources\ResservasionResource\RelationManagers;
use App\Models\Partner;
use App\Models\Resservasion;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\BadgeColumn;

class ResservasionResource extends Resource
{
    protected static ?string $model = Resservasion::class;
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationGroup = 'App';

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User Name')
                    ->options(User::all()->pluck('name', 'id')),
                Select::make('partner_id')
                    ->label('Partner Business Name')
                    ->options(Partner::all()->pluck('business_name', 'id')),
                Forms\Components\TextInput::make('status')
                    ->maxLength(255),
                Forms\Components\DateTimePicker::make('date_time'),
                Forms\Components\TextInput::make('total_price')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('User Name'),
                Tables\Columns\TextColumn::make('partner.business_name')->label('Partner Name'),
                BadgeColumn::make('status')
                    ->colors([
                        'primary' => 'approved',
                        'secondary' => 'waiting for approve',
                        'warning' => 'decapproved',
                        'success' => 'done',
                        'danger' => 'canceled',
                    ]),
                Tables\Columns\TextColumn::make('date_time')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('total_price'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ResservasionPriceListsRelationManager::class,

        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListResservasions::route('/'),
            'create' => Pages\CreateResservasion::route('/create'),
            'edit' => Pages\EditResservasion::route('/{record}/edit'),
        ];
    }
}
