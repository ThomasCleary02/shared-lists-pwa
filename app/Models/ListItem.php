<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\SharedList;
use App\Models\User;


#[Fillable(['list_id', 'content', 'is_complete', 'created_by'])]
class ListItem extends Model
{
    /** @use HasFactory<\Database\Factories\ListItemFactory> */
    use HasFactory;

    protected $table = 'list_items';

    public function list(): BelongsTo
    {
        return $this->belongsTo(SharedList::class, 'list_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

}
