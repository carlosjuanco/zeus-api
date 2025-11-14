<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PageRole extends Pivot
{
    protected $casts = [
        'permissions' => 'array',
    ];
}
