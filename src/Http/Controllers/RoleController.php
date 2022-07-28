<?php

namespace QuetzalStudio\Maple\Http\Controllers;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use QuetzalStudio\Maple\Models\Role;
use QuetzalStudio\Maple\PermissionRepository;

class RoleController extends Controller
{
    public function index()
    {
        $actions = [
            'edit' => true,
            'destroy' => true,
        ];

        return Inertia::render('Role/Index', compact('actions'));
    }

    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $guards = PermissionRepository::groupByGuards();

       return Inertia::render('Role/Show', compact('role', 'guards'));
    }

    public function create()
    {
        $guards = PermissionRepository::groupByGuards();

        return Inertia::render('Role/Create', compact('guards'));
    }

    public function edit($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        $guards = PermissionRepository::groupByGuards();

        return Inertia::render('Role/Edit', compact('role', 'guards'));
    }
}
