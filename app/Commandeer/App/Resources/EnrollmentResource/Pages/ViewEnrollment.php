<?php

namespace App\Commandeer\App\Resources\EnrollmentResource\Pages;

use App\Commandeer\App\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEnrollment extends ViewRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
//            Actions\EditAction::make(),
        ];
    }
}
