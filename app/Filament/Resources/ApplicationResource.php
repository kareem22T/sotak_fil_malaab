<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ApplicationResource\Pages;
use App\Filament\Resources\ApplicationResource\RelationManagers;
use App\Models\Application;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApplicationResource extends Resource
{
    protected static ?string $model = Application::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user_id')
                ->disabled()
                ->label('User')
                ->options(function () {
                    return \App\Models\User::pluck('email', 'id');
                })
                ->required(),
                TextInput::make('name')->disabled()->required(),
                TextInput::make('dob')->disabled()->required(),
                TextInput::make('gender')->disabled()->required(),
                TextInput::make('phone')->disabled()->required(),
                TextInput::make('email')->disabled()->required(),
                TextInput::make('governoment')->disabled()->required(),
                TextInput::make('educational_qualification')->disabled()->required(),
                TextInput::make('languages')->disabled()->required(),
                TextInput::make('admin_rate')->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                TextColumn::make('gender')->sortable()->searchable(),
                TextColumn::make('admin_rate')->sortable(),
                TextColumn::make('rates_avg_rate')->label('Users avg rates')->avg('rates', 'rate'),
                TextColumn::make('created_at')->sortable()->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                ->icon('heroicon-s-eye')
                ->label('show')
                ,
                Tables\Actions\DeleteAction::make(), // Add the DeleteAction here
                Tables\Actions\Action::make('download_video')
                ->extraAttributes([
                    'target' => '_blank',
                    'download' => 'download',
                    'random_shit' => 'this_works',
                ])

                ->label('Download Video')

                ->url(function ($record) {

                    return ($record->video);

                })
                ->icon('heroicon-s-arrow-down-tray'),
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
            'index' => Pages\ListApplications::route('/'),
            'create' => Pages\CreateApplication::route('/create'),
            'edit' => Pages\EditApplication::route('/{record}/edit'),
        ];
    }
}
