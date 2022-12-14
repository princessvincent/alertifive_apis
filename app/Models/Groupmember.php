<?php

namespace App\Models;

use App\Models\Group;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Groupmember extends Model
{
    use HasFactory;

    protected $table = 'groupmembers';

    protected $fillable = [
        'group_id',
        'invite_username',
    ];

    /**
     * Get the user that owns the Groupmember
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }

    public function keywords(){
        return $this->hasMany(Keyword::class, 'group_id', 'group_id');
    }


    public function api(){
        return $this->hasOne(GroupApi::class, 'group_id', 'group_id');
    }


}
