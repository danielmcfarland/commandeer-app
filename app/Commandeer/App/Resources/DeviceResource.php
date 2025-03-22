<?php

namespace App\Commandeer\App\Resources;

use App\Commandeer\App\Resources\DeviceResource\Pages;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

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

                Section::make('Device Information')
                    ->id('main-card')
//                    ->heading('')
                    ->schema([
                        Forms\Components\KeyValue::make('deviceInformation'),
                        Forms\Components\TextInput::make('device_name')
                            ->label('Device Name'),
                    ]),


                Forms\Components\TextInput::make('device_name')
                    ->label('Device Name')
//                    ->maxLength(127),
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
                    ->dateTime()
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->join('enrollments', 'enrollments.device_id', '=', 'devices.device_id')
                            ->where('enrollments.type', '=', 'Device')
                            ->orderBy('enrollments.last_seen_at', $direction);
                    }),
                Tables\Columns\TextColumn::make('device_name')
                    ->label('Device Name')
                    ->sortable(query: function (Builder $query, string $direction) {
                        return $query->join('enrollments', 'enrollments.device_id', '=', 'devices.device_id')
                            ->join('device_information', 'device_information.enrollment_id', '=', 'enrollments.id')
                            ->where('enrollments.type', '=', 'Device')
                            ->where('device_information.key', '=', 'DeviceName')
                            ->orderBy('device_information.value', $direction);
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created At')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('created_at', 'desc')
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Section::make('Device Info')
                ->columns(3)
                ->schema([
                    Infolists\Components\TextEntry::make('device_name')
                        ->label('Device Name'),
                    Infolists\Components\TextEntry::make('os_version')
                        ->label('OS Version'),
                    Infolists\Components\TextEntry::make('build_version')
                        ->label('Build Version'),
                    Infolists\Components\TextEntry::make('model_name')
                        ->label('Model Name'),
                    Infolists\Components\TextEntry::make('model')
                        ->label('Model Identifier'),
                    Infolists\Components\TextEntry::make('product_name')
                        ->label('Product Name'),
                    Infolists\Components\TextEntry::make('serial_number')
                        ->label('Serial Number'),
                    Infolists\Components\TextEntry::make('udid')
                        ->label('UDID'),
                    Infolists\Components\TextEntry::make('last_seen_at')
                        ->label('Last Seen At'),
                ])
        ]);
    }

    public static function getRelations(): array
    {
        return [

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
