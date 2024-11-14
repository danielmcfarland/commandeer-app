<?php

namespace App\Commandeer\App\Resources;

use App\Commandeer\App\Resources\EnrollmentResource\Pages;
use App\Commandeer\App\Resources\EnrollmentResource\RelationManagers;
use App\Models\Enrollment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EnrollmentResource extends Resource
{
    protected static ?string $model = Enrollment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('device_id')
                    ->relationship('device', 'id')
                    ->required(),
//                Forms\Components\TextInput::make('user_id')
//                    ->maxLength(255),
                Forms\Components\TextInput::make('type')
                    ->required()
                    ->maxLength(31),
//                Forms\Components\TextInput::make('topic')
//                    ->required()
//                    ->maxLength(255),
//                Forms\Components\TextInput::make('push_magic')
//                    ->required()
//                    ->maxLength(127),
//                Forms\Components\TextInput::make('token_hex')
//                    ->required()
//                    ->maxLength(255),
//                Forms\Components\Toggle::make('enabled')
//                    ->required(),
//                Forms\Components\TextInput::make('token_update_tally')
//                    ->required()
//                    ->numeric()
//                    ->default(1),
                Forms\Components\DateTimePicker::make('last_seen_at')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('device.device_id')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('user_id')
//                    ->searchable(),
                Tables\Columns\TextColumn::make('type')
                    ->searchable(),
//                Tables\Columns\TextColumn::make('topic')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('push_magic')
//                    ->searchable(),
//                Tables\Columns\TextColumn::make('token_hex')
//                    ->searchable(),
//                Tables\Columns\IconColumn::make('enabled')
//                    ->boolean(),
//                Tables\Columns\TextColumn::make('token_update_tally')
//                    ->numeric()
//                    ->sortable(),
                Tables\Columns\TextColumn::make('last_seen_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
//                Tables\Actions\BulkActionGroup::make([
//                    Tables\Actions\DeleteBulkAction::make(),
//                ]),
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
            'index' => Pages\ListEnrollments::route('/'),
            'view' => Pages\ViewEnrollment::route('/{record}'),
        ];
    }
}
