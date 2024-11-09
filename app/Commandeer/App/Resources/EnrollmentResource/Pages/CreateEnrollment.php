<?php

namespace App\Commandeer\App\Resources\EnrollmentResource\Pages;

use App\Commandeer\App\Resources\EnrollmentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;
}
