<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pintor extends Model
{
    use HasFactory;
    protected $table = 'pintors';
    public $timestamps = false;
    protected $fillable = [
        'nombre', 'descripcion', 'imagen', 'tipo', 'nacimiento', 'fallecimiento', 'nacionalidad',
    ];

    public function estilos()  {
        //decimos que pintores tiene una relacion N:M con estilos
        return $this->belongsToMany('App\Models\Estilo', 'pintor_estilo');
    }

    public function obras() {
        //decimos que tiene relacion 1:N con obras
        return $this->hasMany('App\Models\Obra', 'id_pintor');
    }
}
