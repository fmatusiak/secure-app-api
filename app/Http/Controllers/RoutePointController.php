<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRoutePointRequest;
use App\Http\Requests\UpdateRoutePointRequest;
use App\Models\RoutePoint;
use App\Models\User;
use App\Services\RoutePointService;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoutePointController extends Controller
{
    protected RoutePointService $routePointService;

    public function __construct(RoutePointService $routePointService)
    {
        $this->routePointService = $routePointService;
    }

    public function paginate(Request $request): JsonResponse
    {
        $data = $this->routePointService->paginate($request->all());

        return response()->json($data);
    }

    /**
     * @throws AuthorizationException
     */
    public function getRoutePoint(int $id): JsonResponse
    {
        $routePoint = $this->routePointService->getRoutePoint($id);

        $this->authorize('show', [RoutePoint::class, $routePoint]);

        return response()->json(['data' => $routePoint]);
    }

    /**
     * @throws AuthorizationException
     */
    public function createRoutePoint(CreateRoutePointRequest $request): JsonResponse
    {
        $this->authorize('isAdmin', User::class);

        $routePoint = $this->routePointService->createRoutePoint($request->all());

        return response()->json(['data' => $routePoint]);
    }

    /**
     * @throws AuthorizationException
     */
    public function updateRoutePoint(int $id, UpdateRoutePointRequest $request): JsonResponse
    {
        $this->authorize('isAdmin', User::class);

        $routePoint = $this->routePointService->updateRoutePoint($id, $request->all());

        return response()->json(['data' => $routePoint]);
    }

    /**
     * @throws AuthorizationException
     */
    public function deleteRoutePoint(int $id): JsonResponse
    {
        $this->authorize('isAdmin', User::class);

        $status = $this->routePointService->deleteRoutePoint($id);

        return response()->json(['status' => $status]);
    }
}
