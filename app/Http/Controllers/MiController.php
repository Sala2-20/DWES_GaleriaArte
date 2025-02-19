<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Models\Pintor;
use App\Models\Estilo;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;

abstract class MiController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;

    //clase padre de los demas controladores, funciones necesarias para todos

    //comprobar si esta autentificado
    public function comprobar()
    {
        if (!Auth::check()) {
            return "/";
        }
        return null;
    }

    //corroborar que el string es alfanumerico
    protected static function esAlfanumerico($str) {
        return preg_match('/^[a-zA-Z0-9]+$/', $str);
    }

    //corroborar que el string es un correo
    protected static function esEmail($str) {
        return preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,3}$/', $str);
    }

    //busca una variable dentro de un array bidimensional
    protected function buscarDbArray(array $array, $buscar):bool{
        foreach ($array as $value) {
            foreach ($value as $value2) {
                if ($value2 === $buscar) {
                    return true;
                }
            }
        }
        return false;
    }

    //pasar una coleccion a un array
    protected static function collectionArray(Collection|Pintor|Obra|Estilo $collection) : Array {
        return $collection->toArray();
    }

    //obliga a todos los controladores a tener esta funcion
    //usada para buscar todos los registros de una tabla en una base de datos
    abstract public static function findAll();
}
