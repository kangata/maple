<?php

namespace QuetzalStudio\Maple\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $actions = [
            'edit' => true,
            'destroy' => true,
        ];

        return Inertia::render('User/Index', compact('actions'));
    }

    public function show($id)
    {
       $user = User::findOrFail($id);

       return Inertia::render('User/Show', compact('user'));
    }

    public function create()
    {
        $roles = Role::all();

        return Inertia::render('User/Create', compact('roles'));
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();

        return Inertia::render('User/Edit', compact('user', 'roles'));
    }
}
