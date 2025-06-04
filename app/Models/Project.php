<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected  $guarded = [];


    public function blocks()
    {
        return $this->hasMany(Block::class);
    }

    public static function findByCode($codigo)
    {
        return self::where('codigo_proyecto', $codigo)->first();
    }


}
