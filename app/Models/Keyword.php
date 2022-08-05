<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keyword extends Model
{
    use HasFactory;

    protected $table = 'keywords';
    
    protected $fillable = [
        'group_id',
        'user_id',
        'sender_name',
    ];

    /**
     * Get all of the comments for the Keyword
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function group()
    {
        return $this->belongsToMany(Group::class,'group_id', 'id');
    }
}
