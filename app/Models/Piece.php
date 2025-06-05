<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Piece extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_pieza',
        'nombre',
        'block_id'
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function records()
    {
        return $this->hasMany(PieceRecord::class);
    }
}
