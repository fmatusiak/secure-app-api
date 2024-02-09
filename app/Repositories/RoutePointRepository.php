<?php

namespace App\Repositories;

use App\Helpers\AuthHelper;
use App\Interfaces\RoutePointRepositoryInterface;
use App\Models\RoutePoint;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;

class RoutePointRepository extends CrudRepository implements RoutePointRepositoryInterface
{
    public function __construct(RoutePoint $routePoint)
    {
        parent::__construct($routePoint);
    }

    public function paginate($input): LengthAwarePaginator
    {
        $perPage = Arr::get($input, 'per_page', 10);
        $columns = Arr::get($input, 'columns', ['*']);

        $query = $this->model::query();

        $authUser = AuthHelper::getCurrentUser();

        if (!$authUser->isAdmin()) {
            $query->whereHas('routes', function ($query) use ($authUser) {
                $query->whereHas('users', function ($query) use ($authUser) {
                    $query->where('user_id', $authUser->id);
                });
            });
        }

        if ($name = Arr::get($input, 'name')) {
            $query = $query->where('name', 'like', '%' . $name . '%');
        }

        return $query->paginate($perPage, $columns);
    }
}
