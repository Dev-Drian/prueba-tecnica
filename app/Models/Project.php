<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'codigo_proyecto',
        'nombre'
    ];

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public static function findByCode($codigo)
    {
        return self::where('codigo_proyecto', $codigo)->first();
    }


}
