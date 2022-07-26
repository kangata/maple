<?php

namespace QuetzalStudio\Maple\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use QuetzalStudio\Maple\Http\Requests\User\StoreRequest;
use QuetzalStudio\Maple\Http\Requests\User\UpdateRequest;
use QuetzalStudio\Maple\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with('roles');

        if ($request->filled('search')) {
            $keyword = $request->get('search');

            $query = $query->where(function ($query) use ($keyword) {
                $query->orWhere('name', 'like', '%' . $keyword . '%');
                $query->orWhere('email', 'like', '%' . $keyword . '%');

                $query->orWhereHas('roles', function ($query) use ($keyword) {
                    $query->where('name', 'like', '%' . $keyword . '%');
                });
            });
        }

        if ($request->has('sort')) {
            $sort = $request->get('sort');

            $column = data_get($sort, 0, 'created_at');
            $order = data_get($sort, 1, 'ascending');

            $column = $column == 'null' ? 'created_at' : $column;
            $order = $order == 'null' ? 'descending' : $order;

            $query->orderBy($column, str_replace('ending', '', $order));
        }

        $users = $query->paginate();

        return new UserCollection($users);
    }

    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $user = new User();
        $user->name = $data['name'];
        $user->email = strtolower($data['email']);
        $user->password = bcrypt($data['password']);
        $user->save();

        $user->roles()->sync($data['roles']);

        return response()->json([
            'message' => 'New user has been created.',
            'data' => $user,
        ], 201);
    }

    public function update(UpdateRequest $request, $id)
    {
        $data = $request->validated();

        $user = User::findOrFail($id);

        $user->name = $data['name'];
        $user->email = strtolower($data['email']);

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        $user->save();

        $user->roles()->sync($data['roles']);

        return response()->json([
            'message' => 'User has been updated.',
            'data' => $user,
        ], 201);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();

        try {
            $user->delete();

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            Log::error($e);

            return response()->json([
                'message' => 'Cannot delete this user.',
            ], 400);
        }

        return response()->json([
            'message' => 'User has been deleted.',
        ]);
    }
}
