<?php

namespace App\Commandeer\App\Resources\DeviceResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsRelationManager extends RelationManager
{
    protected static string $relationship = 'enrollments';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('enrollment_id')
                    ->label('Enrollment ID')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('enrollment_id')
            ->columns([
                Tables\Columns\TextColumn::make('enrollment_id')
                    ->label('Enrollment ID'),
                Tables\Columns\TextColumn::make('type'),
//                    ->searchable(),
                Tables\Columns\TextColumn::make('last_seen_at')
                    ->label('Last Seen At')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->options([
                        'Device' => 'Device',
                        'User' => 'User',
                    ])
            ])
            ->persistFiltersInSession()
            ->headerActions([])
            ->actions([])
            ->bulkActions([]);
    }

    public function isReadOnly(): bool
    {
        return true;
    }
}
