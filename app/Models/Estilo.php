<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estilo extends Model
{
    use HasFactory;
    //decimos que tabla modificamos
    protected $table = 'estilos';
    //no creamos las columnas de creacion y modificacion
    public $timestamps = false;
    //decimos que columnas rellenar
    protected $fillable = [
        'nombre',
        'obras_principales',
        'epoca_inicio',
        'epoca_final',
        'caracteristicas'
    ];

    public function pintors()  {
        //decimos que estilos tiene una relacion N:M con pintores
        return $this->belongsToMany('App\Models\Pintor', 'pintor_estilo');
    }

    public function obras() {
        //decimos que tiene relacion 1:N con obras
        return $this->hasMany('App\Models\Obra', 'id_estilo');
    }
}
