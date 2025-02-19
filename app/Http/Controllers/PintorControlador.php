<?php

namespace App\Http\Controllers;

use App\Models\Pintor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ObraControlador;

class PintorControlador extends MiController
{
    public static function findAll()
    {
        return Pintor::all();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        //obtenemos la informacion a mostrar
        $nivel = UsuarioController::getNivel();
        $pintores = $this->showAll();
        $filtrar = $this->filtrar();
        //retornamos la vista y los datos
        return view('pintores', compact('pintores', 'nivel', 'filtrar'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        try {
            //validamos el request
            $request->validate([
                'nombre' => 'required|max:40',
                'descripcion' => 'required',
                'imagen' => 'required|image|mimes:jpg,jpeg,png',
                'nacimiento' => 'required|max:100',
                'fallecimiento' => 'required|date',
                'nacionalidad' => 'required'
            ]);
            //cogemos el archivo
            $archivo = $request->file('imagen');
            //la extendion y la informacion de la imagen
            $extension = $archivo->getClientOriginalExtension();
            $imagen = file_get_contents($archivo->getRealPath());
            //si imagen no existe lanza una excepcion
            if (!$imagen) {
                throw new \Exception("No se recibió el archivo correctamente.");
            }
            //creamos el pintor
            Pintor::create([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'imagen' => $imagen,
                'tipo' => $extension ?? 'png',
                'nacimiento' => $request->nacimiento,
                'fallecimiento' => $request->fallecimiento,
                'nacionalidad' => $request->nacionalidad,
            ]);
            //retornamos la vista
            return redirect('/pintores')->with("correcto", "Pintor creado correctamente");
        } catch (\Throwable $e) {
            //retornamos la vista con el error
            return redirect('/pintores')->withErrors(['error' => "Error al crear el pintor"]);;
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
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        //obtenemos el id, y miramos si existe modificar
        $id = $request->id;
        $mod = isset($request->modificar) ? $request->modificar : 0;
        $nivel = UsuarioController::getNivel();
        //obtenemos el pintor
        $infoPintor = Pintor::find($id);
        //buscamos las obras con este pintor
        $obras = MiController::collectionArray($infoPintor->obras()->get());
        //pasamos el pintor a array
        $infoPintor = MiController::collectionArray($infoPintor);
        /*
           Si tenemos mod devolvemos todos los estilos
           si no devolvemos solo los que tiene el pintor
        */
        if ($mod === 1) {
            $estilos = MiController::collectionArray(EstiloControlador::findAll());
        } else {
            $pintor = Pintor::find($id);
            $estilos = MiController::collectionArray($pintor->estilos()->get());
        }

        //si el pintor no tiene obras, no podemos verlo
        if (empty($obras)) {
            abort(404, 'No se encontraron datos.');
        }
        //retornamos la vista
        return view('detallePintores', compact('obras', 'infoPintor', 'mod', 'nivel', 'estilos'));
    }

    public function showAll()
    {
        //cogemos todos los pintores
        $pintoresTabla =  PintorControlador::findAll();
        $pintoresArray = [];
        $i = 0;
        //recorremos el collection y vamos guardando la informacion en el array
        foreach ($pintoresTabla as $pintor) {
            $pintoresArray[$i]['id'] = $pintor->id;
            $pintoresArray[$i]['nombre'] = $pintor->nombre;
            $pintoresArray[$i]['imagen'] = $pintor->imagen ? 'data:image/' . $pintor->tipo . '; base64,' . base64_encode($pintor->imagen) : null;
            $pintoresArray[$i]['epoca'] = $this->epoca($pintor->id);
            $pintoresArray[$i]['nacionalidad'] = $this->nacionalidad($pintor->id);
            $i++;
        }
        return $pintoresArray;
    }

    private function filtrar()
    {
        //obtenemos los id de todos los pintores
        $pintores = Pintor::select('id')->get();
        $filtrar = [];
        foreach ($pintores as $pintor) {
            //llamamos al metodo epoca que nos devuelve la epoca de cada pintor
            $epoca = $this->epoca($pintor->id);
            //llamamos al metodo nacionalidad que nos devuelve la nacionalidad de cada pintor
            $nacionalidad = $this->nacionalidad($pintor->id);
            //si la epoca o la nacionalidad no han sido guardadas en el array las guardamos
            if (!$this->buscarDbArray($filtrar, $epoca)) {
                $filtrar[$pintor->id]["epoca"] = $epoca;
            }
            if (!$this->buscarDbArray($filtrar, $nacionalidad)) {
                $filtrar[$pintor->id]["nacionalidad"] = $nacionalidad;
            }
        }
        return $filtrar;
    }

    public static function getPintorId($id): array
    {
        //obtenemos el pintor
        $pintor =  MiController::collectionArray(Pintor::find($id));
        //pasamos la imagen al src a mostrar
        $pintor['imagen'] = $pintor['imagen'] ? 'data:image/' . $pintor['tipo'] . '; base64,' . base64_encode($pintor['imagen']) : null;

        return $pintor;
    }

    public static function getNombres()
    {
        $pintoresTabla =  MiController::collectionArray(Pintor::select('id', 'nombre')->get());
        return $pintoresTabla;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pintor $pintor)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        try {
            //validacion
            $request->validate([
                'nombre' => 'required|max:20',
                'descripcion' => 'required',
                'imagen' => 'nullable|image|mimes:jpg,jpeg,png',
                'nacimiento' => 'required|date',
                'id_estilo' => 'nullable|numeric',
                'fallecimiento' => 'required|date',
                'nacionalidad' => 'nullable|max:20',
            ]);
            //si esta seteado el id_estilo creamos el enclace
            if (isset($request->id_estilo)) {
                //si al llamar al estilo no nos devuelve un array el estilo no existe
                !is_array(EstiloControlador::getEstiloId($request->id_estilo)) ? redirect()->back()->withErrors(['error' => 'Estilo no encontrado']): null;
                //buscamos al pintor
                $pintor = Pintor::find($id);
                //creamos el sync pero sin eliminar ningun estilo
                $pintor->estilos()->syncWithoutDetaching($request->id_estilo);
            }
            //si esta seteado la imagen la subimos
            if (isset($request->imagen)) {
                //cogemos el archivo
                $archivo = $request->file('imagen');
                //guardamos la extension y la informacion
                $extension = $archivo->getClientOriginalExtension();
                $imagen = file_get_contents($archivo->getRealPath());
                //si no se existe imagen no guardamos nada
                if (!$imagen) {
                    throw new \Exception("No se recibió el archivo correctamente.");
                }
                //modificamos el pintor seleccionado
                Pintor::findOrFail($id)->update([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'imagen' => $imagen,
                    'tipo' => $extension ?? 'png',
                    'nacimiento' => $request->nacimiento,
                    'fallecimiento' => $request->fallecimiento,
                    'nacionalidad' => $request->nacionalidad,
                ]);
            } else {
                //modificamos el pintor seleccionado
                Pintor::findOrFail($id)->update([
                    'nombre' => $request->nombre,
                    'descripcion' => $request->descripcion,
                    'nacimiento' => $request->nacimiento,
                    'fallecimiento' => $request->fallecimiento,
                    'nacionalidad' => $request->nacionalidad,
                ]);
            }
            //devolvemos con mensaje
            return redirect()->back()->with('correcto', 'Pintor actualizado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'No es posible actualizar el pintor.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            //nos llega un json con un id
            $request->input('id');
            //buscamos al pintor
            $pintor = Pintor::findOrFail($request->id);
            //eliminamos el pintor
            $pintor->delete();
            //devolvemos un json con un mensaje
            return response()->json([
                'mensaje' => 'Pintor borrada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            //devolvemos un json con un mensaje
            return response()->json([
                'mensaje' => 'No se ha podido borrar'
            ], 200);
        }
    }

    private function nacionalidad($id)
    {
        //buscamos el pintor y guardamos la nacionalidad
        $pintor = Pintor::where('id', $id)->select('nacionalidad')->get()[0]->nacionalidad;
        return $pintor;
    }

    private function epoca($id): int
    {
        //buscamos el pintor y guardamos la epoca con formato
        $pintor = Pintor::where('id', $id)->select('fallecimiento')->get();
        $pintor = intval(str_split(explode('-', $pintor[0]->fallecimiento)[0] . '', 2)[0]) + 1;
        return $pintor;
    }
}
