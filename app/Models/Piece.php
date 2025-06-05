<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Piece extends Model
{
    use HasFactory;

    protected $fillable = [
        'codigo_pieza',
        'nombre',
        'block_id',
        'peso_teorico',
        'estado',
        'usuario_id'
    ];

    protected $casts = [
        'peso_teorico' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function block()
    {
        return $this->belongsTo(Block::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function records()
    {
        return $this->hasMany(PieceRecord::class);
    }
}
