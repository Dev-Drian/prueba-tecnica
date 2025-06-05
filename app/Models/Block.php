<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Block extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_bloque',
        'nombre',
        'project_id'
    ];
    
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function pieces()
    {
        return $this->hasMany(Piece::class);
    }
}
