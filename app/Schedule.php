<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Schedule extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'color',
        'start_on',
        'quote',
        'note',
    ];

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
