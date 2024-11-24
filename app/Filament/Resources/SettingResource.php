<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?int $navigationSort = 10;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('main_sponsor')
                    ->default(null),
                Forms\Components\FileUpload::make('profile_ad')
                    ->default(null),
                Forms\Components\Textarea::make('terms_and_condition')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('about_us')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('registration_terms_and_conditions')
                    ->columnSpanFull(),
                Select::make('submission')
                    ->label('Open and close submission')
                    ->options(function () {
                        return [
                            1 => 'Opening',
                            0 => 'Cloased',
                        ];
                    })
                    ->required(),
                DateTimePicker::make('ended_at')
                    ->required(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('main_sponsor'),
                Tables\Columns\ImageColumn::make('profile_ad'),
                Tables\Columns\TextColumn::make('terms_and_condition')->limit(50),
                Tables\Columns\TextColumn::make('about_us')->limit(30),
                Tables\Columns\TextColumn::make('ended_at'),
                BadgeColumn::make('submission')
                ->label('Open and close submission')
                ->colors([
                    'danger' => 0, // Color red when it's closed
                    'success' => 1, // Color green when it's open
                ])
                ->formatStateUsing(function ($state) {
                    return $state == 1 ? 'Opening' : 'Closed'; // Format the state text
                })
                ->sortable(),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListSettings::route('/'),
            'create' => Pages\CreateSetting::route('/create'),
            'view' => Pages\ViewSetting::route('/{record}'),
            'edit' => Pages\EditSetting::route('/{record}/edit'),
        ];
    }
}
