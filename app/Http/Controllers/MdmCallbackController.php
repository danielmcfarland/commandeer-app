<?php

namespace App\Http\Controllers;

use App\Models\NanoMdm\Command;
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
                    return $this->acknowledgeEvent($request->get('acknowledge_event'));
                case 'mdm.Authenticate':
                case 'mdm.TokenUpdate':
                case 'mdm.CheckOut':
                    return $this->checkinEvent();
            }
        }

        return response()
            ->setStatusCode(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->json();
    }

    private function acknowledgeEvent(array $acknowledgeEvent): JsonResponse
    {
        if (array_key_exists('command_uuid', $acknowledgeEvent)) {
            $commandResult = Command::find($acknowledgeEvent['command_uuid'])
                ->commandResults()
                ->latest()
                ->first();

            $commandResult->addResult();
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
