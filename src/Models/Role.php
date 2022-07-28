<?php

namespace QuetzalStudio\Maple\Models;

use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use HasDateFormat;
}
