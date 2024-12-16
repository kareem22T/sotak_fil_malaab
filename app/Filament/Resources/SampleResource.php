<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SampleResource\Pages;
use App\Filament\Resources\SampleResource\RelationManagers;
use App\Models\Sample;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SampleResource extends Resource
{
    protected static ?string $model = Sample::class;

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('video')
                    ->default(null),
                Forms\Components\FileUpload::make('thumbnail')
                    ->default(null),
                Forms\Components\TextInput::make('title')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('sub_title')
                    ->maxLength(255)
                    ->default(null)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('title_en')
                    ->maxLength(255)
                    ->default(null),
                Forms\Components\TextInput::make('sub_title_en')
                    ->maxLength(255)
                    ->default(null)
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('description_en')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
                Tables\Columns\ImageColumn::make('thumbnail')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('title')
                    ->searchable(),
                Tables\Columns\TextColumn::make('sub_title')
                    ->searchable(),
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
            'index' => Pages\ListSamples::route('/'),
            'create' => Pages\CreateSample::route('/create'),
            'view' => Pages\ViewSample::route('/{record}'),
            'edit' => Pages\EditSample::route('/{record}/edit'),
        ];
    }
}
