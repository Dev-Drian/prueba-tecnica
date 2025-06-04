<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;

    protected  $guarded = [];


    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function records()
    {
        return $this->hasMany(PieceRecord::class);
    }

}
