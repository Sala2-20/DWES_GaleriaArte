<?php

namespace App\Http\Controllers;

use App\Models\Obra;
use App\Http\Controllers\PintorControlador;
use App\Http\Controllers\EstiloControlador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObraControlador extends MiController
{
    public static function findAll()
    {
        return Obra::all();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //autentificamos
        if ($this->comprobar() != null) {
            return redirect($this->comprobar());
        }
        //obtenemos el nivel y las obras
        $nivel = UsuarioController::getNivel();
        $obras = MiController::collectionArray(ObraControlador::findAll());
        //si nivel es mayor a cero añadimos los estilos y los pintores para crear obras
        if ($nivel > 0) {
            $pintores = PintorControlador::getNombres();
            $estilos = EstiloControlador::getNombres();
            return view('obras', compact('nivel', 'obras', 'pintores', 'estilos'));
        }
        //si no devolvemos la vista solo con obras
        return view('obras', compact('nivel', 'obras'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //autentificamos
        if ($this->comprobar() != null) {
            return redirect($this->comprobar());
        }
        try {
            //validamos los datos
            $request->validate([
                'nombre' => 'required|max:50',
                'id_pintor' => 'required|numeric',
                'descripcion' => 'required',
                'imagen' => 'required|image|mimes:jpg,jpeg,png',
                'ancho' => 'required|max:100',
                'alto' => 'required|max:100',
                'anio' => 'required|date',
                'id_estilo' => 'required|numeric',
            ]);
            //obtenemos el archivo
            $archivo = $request->file('imagen');
            //obtenemos la extension y la info de la imagen
            $extension = $archivo->getClientOriginalExtension();
            $imagen = file_get_contents($archivo->getRealPath());
            //si no existe la imagen lanzamos excepcion
            if (!$imagen) {
                throw new \Exception("No se recibió el archivo correctamente.");
            }
            //obtenemos el anio
            $anio = explode('-', $request->anio)[0];
            //pasamos el tamanio a string
            $tamanio = $request->ancho . ' x ' . $request->alto;
            //creamos la obra
            $obra = Obra::create([
                'id_pintor' => $request->id_pintor,
                'id_estilo' => $request->id_estilo,
                'nombre_obra' => $request->nombre,
                'descripcion' => $request->descripcion,
                'anio' => $anio,
                'tamanio' => $tamanio,
                'tipo' => $extension,
                'imagen' => $imagen,
            ]);
            //si obra no existe lanzamos una excepcion
            if (!$obra) {
                throw new \Exception("No se pudo guardar la obra en la BD");
            }
            //devolvemos la ubicacion donde se crea la obra con un mensaje
            return redirect('/obras')->with('correcto', 'La obra se ha creado correctamente');
        } catch (\Throwable $e) {
            return redirect('/obras')->withErrors(['error' => 'No es posible crear la obra']);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */

    public function show(Request $request)
    {
        //autentificamos
        if ($this->comprobar() != null) {
            return redirect($this->comprobar());
        }
        //obtenemos el id
        $id = $request->id;
        //obtenemos mod y el nivel
        $mod = isset($request->modificar) ? $request->modificar : 0;
        $nivel = UsuarioController::getNivel();
        $estilos = EstiloControlador::getNombres();
        $pintores = PintorControlador::getNombres();
        //cogemos la info de la obra
        $info = $this->showId($id);
        //si no devuelve nada damos error 404
        if (empty($info)) {
            abort(404, 'No se encontraron datos.');
        }
        //devolvemos la vista con la informacion
        return view('detalleObra', compact('info', 'mod', 'nivel','estilos', 'pintores'));
    }

    private function showId(int $id)
    {
        //obtenemos la obra
        $obraTabla = Obra::find($id);
        $obraArray = [];
        //pasamos la obra a array
        $obraArray['nombre'] = $obraTabla->nombre_obra;
        $obraArray['imagen'] = $obraTabla->imagen ? 'data:image/' . $obraTabla->tipo . '; base64,' . base64_encode($obraTabla->imagen) : null;
        $obraArray['descripcion'] = $obraTabla->descripcion;
        $obraArray['anio'] = $obraTabla->anio;
        $obraArray['tamanio'] = $obraTabla->tamanio;
        $obraArray['id_estilo'] = $obraTabla->id_estilo;
        $obraArray['id_pintor'] = $obraTabla->id_pintor;
        //añadimos el pintor y el estilo
        $obraArray['pintor'] =  $obraTabla->pintor->nombre;
        $obraArray['estilo'] = $obraTabla->estilo->nombre;
        return $obraArray;
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Obra $obra)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        //autentificamos
        if ($this->comprobar() != null) {
            return redirect($this->comprobar());
        }
        try {
            //validamos
            $request->validate([
                'nombre' => 'required|max:30',
                'id_pintor' => 'required|numeric',
                'id_estilo' => 'required|numeric',
                'descripcion' => 'required',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png',
                'ancho' => 'required|max:100',
                'alto' => 'required|max:100',
                'anio' => 'required|date'
            ]);
            //SI existe imagenn actualizamos con una imagen
            if (isset($request->imagen)) {
                //obtenemos la imagen
                $archivo = $request->file('imagen');
                //obtenemos la extension y la informacion
                $extension = $archivo->getClientOriginalExtension();
                $imagen = file_get_contents($archivo->getRealPath());
                //si no existe la imagen lanzamos una excepcion
                if (!$imagen) {
                    throw new \Exception("No se recibió el archivo correctamente.");
                }
                //creamos la obra
                Obra::findOrFail($id)->update([
                    'nombre_obra' => $request->nombre,
                    'id_pintor' => $request->id_pintor,
                    'imagen' => $imagen,
                    'tipo' => $extension ?? 'png',
                    'tamanio' => $request->tamanio,
                    'id_estilo' => $request->id_estilo,
                    'descripcion' => $request->descripcion,
                ]);
            } else {
                Obra::findOrFail($id)->update([
                    'nombre_obra' => $request->nombre,
                    'id_pintor' => $request->id_pintor,
                    'tamanio' => $request->tamanio,
                    'id_estilo' => $request->id_estilo,
                    'descripcion' => $request->descripcion,
                ]);
            }
            //volvemos a la vista de obras con un mensaje
            return redirect()->back()->with('correcto', 'Obra actualizada correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar la obra.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            //obtenemos el id de la obra
            $request->input('id');
            //eliminamos la obra
            $obra = Obra::findOrFail($request->id);
            $obra->delete();
            //devolvemos un json con el mensaje2
            return response()->json([
                'mensaje' => 'Obra borrada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'No se ha podido borrar'
            ], 200);
        }
    }
}
