<?php

namespace App\Commandeer\App\Resources\DeviceResource\Pages;

use App\Commandeer\App\Resources\DeviceResource;
use App\Models\Device;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Resources\Pages\ViewRecord;

class ViewDevice extends ViewRecord
{
    protected static string $resource = DeviceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ActionGroup::make([
                Action::make('request_checkin')
                    ->label('Request Device Checkin')
                    ->requiresConfirmation()
                    ->action(function (Device $device) {
                        $device->mdmDevice->automatedCheckin(false);
                    }),

                Action::make('request_info')
                    ->label('Request Device Info')
                    ->requiresConfirmation()
                    ->action(function (Device $device) {
                        $device->mdmDevice->automatedCheckin();
                    }),
            ])
                ->button()
                ->label('Actions'),
        ];
    }
}
