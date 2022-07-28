<?php

namespace QuetzalStudio\Maple\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuetzalStudio\Maple\Http\Requests\Role\StoreRequest;
use QuetzalStudio\Maple\Http\Requests\Role\UpdateRequest;
use QuetzalStudio\Maple\Http\Resources\RoleCollection;
use QuetzalStudio\Maple\Models\Role;

class RoleController extends Controller
{
    public function index(Request $request)
    {
        $query = Role::query();

        if ($request->filled('search')) {
            $keyword = $request->get('search');

            $query = $query->where(function ($query) use ($keyword) {
                $query->where('name', 'like', '%' . $keyword . '%');
                $query->orWhere('guard_name', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            $column = data_get($sort, 0, 'name');
            $order = data_get($sort, 1, 'ascending');

            $column = $column == 'null' ? 'name' : $column;
            $order = $order == 'null' ? 'descending' : $order;

            $query->orderBy($column, str_replace('ending', '', $order));
        }

        $users = $query->paginate();

        return new RoleCollection($users);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $role = new Role();
        $role->name = $data['name'];
        $role->guard_name = strtolower($data['guard_name']);
        $role->save();

        $role->permissions()->sync($data['permissions']);

        return response()->json([
            'message' => 'New role has been created.',
            'data' => $role,
        ], 201);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $role = Role::findOrFail($id);

        $role->name = $data['name'];
        $role->guard_name = strtolower($data['guard_name']);
        $role->save();

        $role->permissions()->sync($data['permissions']);

        return response()->json([
            'message' => 'Role has been updated.',
            'data' => $role,
        ], 201);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        DB::beginTransaction();

        try {
            $role->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'message' => 'Cannot delete this role.',
            ], 400);
        }

        return response()->json([
            'message' => 'Role has been deleted.',
        ]);
    }
}
