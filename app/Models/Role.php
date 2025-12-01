<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class)
            ->select(['pages.id', 'name', 'name_component', 'pages.page_id', 'human_id'])
            ->withPivot('permissions');
    }
}
