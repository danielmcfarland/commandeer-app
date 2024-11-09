<?php

namespace App\Commandeer\App\Pages;

use App\Models\Organisation;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Pages\Tenancy\RegisterTenant;
use Illuminate\Support\Str;

class RegisterOrganisation extends RegisterTenant
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getLabel(): string
    {
        return 'Create Organisation';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (Get $get, Set $set, ?string $operation, ?string $old, ?string $state, ?Organisation $record) {
                        if ($operation === 'edit') {
                            return;
                        }

                        $set('slug', Str::slug($state));
                    }),

                TextInput::make('slug')
                    ->required()
                    ->readOnly()
                    ->maxLength(255)
                    ->rules(['alpha_dash'])
                    ->unique(Organisation::class, 'slug', fn($record) => $record),

                TextInput::make('topic') // temp
                    ->required()
                    ->unique(Organisation::class, 'topic', fn($record) => $record),
            ]);
    }

    protected function handleRegistration(array $data): Organisation
    {
        return auth()->user()->organisations()->create($data, [
            'owner' => true,
            'admin' => true,
        ]);
    }
}
