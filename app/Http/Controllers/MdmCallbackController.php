<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\NanoMdm\Command;
use App\Models\NanoMdm\Device as NanoMdmDevice;
use App\Models\NanoMdm\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MdmCallbackController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        Log::debug('__invoke: ', $request->toArray());
        if ($request->has('topic')) {
            Log::debug('__invoke: ' . $request->get('topic'));
            switch ($request->get('topic')) {
                case 'mdm.Connect':
                    return $this->connect($request->get('acknowledge_event'));
                case 'mdm.TokenUpdate':
                    return $this->tokenUpdate($request->get('checkin_event'));
                case 'mdm.Authenticate':
                    return $this->authenticate();
                case 'mdm.CheckOut':
                    return $this->checkOut($request->get('checkin_event'));
            }
        }

        return response()
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->json();
    }

    private function connect(array $acknowledgeEvent): JsonResponse
    {
        Log::debug('connect', $acknowledgeEvent);
        if (array_key_exists('command_uuid', $acknowledgeEvent)) {
            $commandResult = Command::find($acknowledgeEvent['command_uuid'])
                ->commandResults()
                ->latest()
                ->first();

            $commandResult->addResult();
        }

        if (array_key_exists('udid', $acknowledgeEvent)) {
            $enrollment = Enrollment::find($acknowledgeEvent['udid']);

            $enrollment->updateOrCreateEnrollment();
        }

        return response()
            ->json();
    }

    private function tokenUpdate(array $checkinEvent): JsonResponse
    {
        if (array_key_exists('udid', $checkinEvent)) {
            $device = NanoMdmDevice::find($checkinEvent['udid']);
            $device->enroll();
        }

        return response()
            ->json();
    }

    private function checkOut(array $checkOutEvent): JsonResponse
    {
        if (array_key_exists('udid', $checkOutEvent)) {
            Device::where('device_id', $checkOutEvent['udid'])
                ->each(function (Device $device) {
                    $device->enrollments()->delete();
                    $device->delete();
                });
        }

        return response()
            ->json();
    }

    private function authenticate(): JsonResponse
    {
        return response()
            ->json();
    }
}
