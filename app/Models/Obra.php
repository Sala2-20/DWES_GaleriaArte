<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obra extends Model
{
    use HasFactory;
    //decimos que tabla modificamos
    protected $table = 'obras';
    //no creamos las columnas de creacion y modificacion
    public $timestamps = false;
    //decimos que columnas rellenar
    protected $fillable = [
        'id_pintor',
        'id_estilo',
        'nombre_obra',
        'descripcion',
        'imagen',
        'tipo',
        'anio',
        'tamanio'
    ];

    public function estilo()
    {
        //decimos que tiene una relacion 1:N con estilo
        return $this->belongsTo('App\Models\Estilo', 'id_estilo');
    }

    public function pintor()
    {
        //decimos que tiene relacion 1:N con pintor
        return $this->belongsTo('App\Models\Pintor', 'id_pintor');
    }
}
