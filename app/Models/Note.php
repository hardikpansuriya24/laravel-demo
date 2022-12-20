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
class Note extends Model
{
    use HasFactory, HasEditHistory, SoftDeletes;

    protected $table = 'notes';

    protected $hidden = [];
    protected $primaryKey = 'id';

    protected $fillable = [
        'task_id',
        'subject',
        'note'
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'task_id', 'id');
    }
}
