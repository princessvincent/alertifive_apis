<?php

namespace App\Models;

use App\Models\Groupmember;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Group extends Model
{
    use HasFactory;

    protected $table = 'groups';

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    /**
     * Get all of the comments for the Group
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Groupmember(): HasMany
    {
        return $this->hasMany(Groupmember::class, 'group_id', 'id');
    }

    public function keywords(){
        return $this->hasMany(Keyword::class, 'group_id', 'id');
    }


}
