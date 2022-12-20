<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class User
 * @package App\Models
 * @mixin Builder
 */
class Task extends Model
{
    use HasFactory, HasEditHistory, SoftDeletes;

    protected $table = 'tasks';

    protected $hidden = [];
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'start_date',
        'due_date',
        'status',
        'priority'
    ];

    public function menuCategories(): HasMany
    {
        return $this->hasMany(MenuCategoryLink::class, 'menu_id', 'id')->orderBy('weight', 'asc');
    }
}
