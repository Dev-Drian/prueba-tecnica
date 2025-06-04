<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    protected  $guarded = [];

    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }

}
