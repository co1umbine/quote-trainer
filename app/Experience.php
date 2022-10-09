<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Experience extends Model
{
    protected $fillable = [
        'user_id',
        'schedule_id',
        'name',
        'color',
        'start_on',
        'end_on',
        'quote',
        'period',
        'efficiency',
        'note',
    ];
    
    public function tags(){
        return $this->belongsToMany(Tag::class);
    }
}
