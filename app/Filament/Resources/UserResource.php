<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Closure;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationLabel = 'Users';
    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = 'Vendors';

    public function isTableSearchable(): bool
    {
        return true;
    }


    protected function applySearchToTableQuery(Builder $query): Builder
    {
        if (filled($searchQuery = $this->getTableSearchQuery())) {
            $query->whereIn('id', User::search($searchQuery)->keys());
        }

        return $query;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                SpatieMediaLibraryFileUpload::make('avatar'),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('gender')
                    ->maxLength(255),
                Forms\Components\DatePicker::make('birth_date'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->maxLength(255),
                Forms\Components\TextInput::make('provider_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('provider_id')
                    ->maxLength(255),
                Forms\Components\TextInput::make('address_address')
                    ->maxLength(255),
                Forms\Components\TextInput::make('country_id'),
                Forms\Components\TextInput::make('city_id'),
                Forms\Components\Toggle::make('is_blocked'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('Picture'),

                Tables\Columns\TextColumn::make('name')->searchable(['first_name', 'last_name']),
                Tables\Columns\TextColumn::make('phone'),
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('gender'),
                Tables\Columns\TextColumn::make('birth_date')
                    ->date(),
                Tables\Columns\TextColumn::make('provider_name'),
                Tables\Columns\TextColumn::make('provider_id'),
                Tables\Columns\TextColumn::make('address_address'),
                Tables\Columns\TextColumn::make('country_id'),
                Tables\Columns\TextColumn::make('city_id'),
                Tables\Columns\IconColumn::make('is_blocked')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('BlockedUser $user')
                    ->query(fn (Builder $query): Builder => $query->where('is_blocked', true))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Block')
                    ->url(fn (User $record): string => route('user.block', $record))
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-circle')
                    ->visible(fn (User $record): bool => $record->is_blocked == false),
                Action::make('Unblock')
                    ->url(fn (User $record): string => route('user.block', $record))
                    ->color('success')
                    ->icon('heroicon-o-exclamation-circle')
                    ->visible(fn (User $record): bool => $record->is_blocked == true),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
    protected static function getNavigationBadgeColor(): ?string
    {
        return static::getModel()::count() > 10 ? 'warning' : 'primary';
    }

    public function isTableBlockSelectable(): ?Closure
    {
        return fn (User $record): bool => $record->is_blocked === false;
    }
}
