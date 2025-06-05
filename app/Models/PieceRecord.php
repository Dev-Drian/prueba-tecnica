<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PieceRecord extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $with = ['piece', 'user'];

    public function user()
    {   
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function piece()
    {
        return $this->belongsTo(Piece::class, 'pieza_id');
    }
}
