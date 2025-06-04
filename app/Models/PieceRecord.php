<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceRecord extends Model
{
    use HasFactory;

    protected  $guarded = [];


    public function user()
    {   
        return $this->belongsTo(User::class);
    }

    public function piece()
    {
        return $this->belongsTo(Piece::class);
    }

}
