<?php

namespace App\Commandeer\App\Resources;

use App\Commandeer\App\Resources\DeviceResource\Pages;
use App\Commandeer\App\Resources\DeviceResource\RelationManagers;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('device_id')
                    ->label('Device ID')
                    ->maxLength(127),
                Forms\Components\TextInput::make('serial_number')
                    ->label('Serial Number')
                    ->maxLength(127),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('device_id')
                    ->label('Device ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('serial_number')
                    ->label('Serial Number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('last_seen_at')
                    ->label('Last Seen At')
                    ->dateTime(),
//                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->persistFiltersInSession()
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\EnrollmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDevices::route('/'),
            'view' => Pages\ViewDevice::route('/{record}'),
        ];
    }
}
