<?php

namespace App\Http\Controllers;

use App\Models\NanoMdm\Command;
use App\Models\NanoMdm\Device;
use App\Models\NanoMdm\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class MdmCallbackController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        if ($request->has('topic')) {
            switch ($request->get('topic')) {
                case 'mdm.Connect':
                    return $this->connect($request->get('acknowledge_event'));
                case 'mdm.TokenUpdate':
                    return $this->tokenUpdate($request->get('checkin_event'));
                case 'mdm.Authenticate':
                case 'mdm.CheckOut':
                    return $this->checkinEvent();
            }
        }

        return response()
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->json();
    }

    private function connect(array $acknowledgeEvent): JsonResponse
    {
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
            $device = Device::find($checkinEvent['udid']);
            $device->enroll();
            $device->automatedCheckin();
        }

        return response()
            ->json();
    }

    private function checkinEvent(): JsonResponse
    {
        return response()
            ->json();
    }
}
