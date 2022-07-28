<?php

namespace QuetzalStudio\Maple;

use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;

class PermissionRepository
{
    public static function groupByGuards(): Collection
    {
        $permissions = Permission::orderBy('name')->get();

        return $permissions->groupBy('guard_name')->map(function ($_permissions, $guard) {
            $collections = $_permissions->map(function ($permission) {
                preg_match('/^(.*)?\./', $permission->name, $matches);

                $permission->collection = str_replace('_', ' ', $matches[1] ?? 'other');

                preg_match('/.*\.(.*)$/', $permission->name, $matches);

                $permission->action = str_replace('_', ' ', $matches[1] ?? 'unknown');

                return $permission;
            })->groupBy('collection')->map(function ($permissions, $collection) {
                return [
                    'name' => $collection,
                    'permissions' => $permissions->toArray(),
                ];
            })->values();

            return [
                'name' => $guard,
                'collections' => $collections,
            ];
        })->values();
    }
}
