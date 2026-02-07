<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PartnerResource\Pages;
use App\Filament\Resources\PartnerResource\RelationManagers;
use App\Models\Category;
use App\Models\Partner;
use App\Models\User;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\MultiSelect;

class PartnerResource extends Resource
{
    protected static ?string $model = Partner::class;
    protected static ?string $navigationLabel = 'Partners';
    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = 'Vendors';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                    ->label('User')
                    ->options(User::all()->pluck('name', 'id'))
                    ->searchable(),
                Select::make('category_id')
                    ->label('Category')
                    ->options(Category::all()->pluck('name', 'id'))
                    ->searchable(),
                // Select::make('other_categroy_id')
                //     ->label('Other Category')
                //     ->options(Category::all()->pluck('name', 'id'))
                //     ->multiple()
                //     ->relationship('categories', 'name'),
                Select::make('categrories')
                    ->multiple()
                    ->options(Category::all()->pluck('name', 'id'))
                    ->relationship('categories', 'name'),
                Forms\Components\TextInput::make('gallery_limit'),
                Forms\Components\TextInput::make('rate'),
                Forms\Components\TextInput::make('points'),
                Forms\Components\TextInput::make('business_name')
                    ->maxLength(255),
                Forms\Components\TextInput::make('social_provider')
                    ->maxLength(255),
                Forms\Components\TextInput::make('social_url')
                    ->maxLength(255),
                Forms\Components\TextInput::make('business_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('about_us_survey')
                    ->maxLength(255),
                Forms\Components\Toggle::make('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SpatieMediaLibraryImageColumn::make('user.getFirstMediaUrl()')->label('Avater'),

                Tables\Columns\TextColumn::make('user.first_name')->label('First name'),
                Tables\Columns\TextColumn::make('user.last_name')->label('Last name'),
                Tables\Columns\TextColumn::make('user.phone')->searchable()->label('Phone'),
                Tables\Columns\TextColumn::make('category.name')->label('Category'),
                Tables\Columns\TextColumn::make('category.name')->label('Other Category'),
                Tables\Columns\TextColumn::make('business_name'),
                Tables\Columns\TextColumn::make('rate'),
                Tables\Columns\TextColumn::make('gallery_limit'),
                Tables\Columns\TextColumn::make('points'),
                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('social_provider'),
                Tables\Columns\TextColumn::make('social_url'),
                Tables\Columns\TextColumn::make('business_type'),
                Tables\Columns\TextColumn::make('about_us_survey'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime(),
            ])
            ->filters([
                Filter::make('On Hold')
                    ->query(fn (Builder $query): Builder => $query->where('active', false))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Action::make('Accept')
                    ->url(fn (Partner $record): string => route('partner.accept', $record))
                    ->color('success')
                    ->icon('heroicon-o-exclamation-circle')
                    ->visible(fn (Partner $record): bool => $record->active == false),
                Action::make('Block')
                    ->url(fn (Partner $record): string => route('partner.accept', $record))
                    ->color('danger')
                    ->icon('heroicon-o-exclamation-circle')
                    ->visible(fn (Partner $record): bool => $record->active == true),

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
            'index' => Pages\ListPartners::route('/'),
            'create' => Pages\CreatePartner::route('/create'),
            'edit' => Pages\EditPartner::route('/{record}/edit'),
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
}
