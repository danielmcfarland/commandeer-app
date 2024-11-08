<?php

namespace App\Commandeer\App\Pages;

//use App\Scheduley\App\Resources\OrganisationResource\RelationManagers\TeamsRelationManager;
//use App\Scheduley\App\Resources\OrganisationResource\RelationManagers\UsersRelationManager;
//use App\Scheduley\App\Widgets\OrganisationUsers;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\EditTenantProfile;
use Filament\Widgets\StatsOverviewWidget;

class EditOrganisation extends EditTenantProfile
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function getLabel(): string
    {
        return 'Edit Organisation';
    }

    protected ?string $subheading = 'Custom Page Subheading';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                TextInput::make('slug')->readOnly()->disabled(),
                TextInput::make('topic')->readOnly()->disabled(), // temp
            ]);
    }

    public function getFooterWidgetsColumns(): int|array
    {
        return 1;
    }

    protected function getFooterWidgets(): array
    {
        return [
//            OrganisationUsers::make(),
            //            StatsOverviewWidget::make([
            //                'status' => 'active',
            //            ]),
        ];
    }

    //    public static function getRelations(): array
    //    {
    //        return [
    //            UsersRelationManager::class,
    ////            TeamsRelationManager::class,
    //        ];
    //    }
}
