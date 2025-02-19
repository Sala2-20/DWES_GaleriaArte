<?php

namespace App\Http\Controllers;

use App\Models\Estilo;
use Illuminate\Http\Request;

class EstiloControlador extends MiController
{
    public static function findAll(){
        return Estilo::all();
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //autentificacion
        if ($this->comprobar() != null) {
            return redirect($this->comprobar());
        }
        //obtenemos todos los estilos y los niveles
        $estilos = MiController::collectionArray(EstiloControlador::findAll());
        $nivel = UsuarioController::getNivel();
        //devolvemos la vista con la informacion a usar
        return view('estilo', compact('nivel', 'estilos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        try {
            //validamos la informacion
            $request->validate([
                'nombre' => 'required|max:50',
                'epoca_inicio' => 'required|date',
                'epoca_final' => 'nullable|date',
                'caracteristicas' => 'required',
            ]);
            //cogemos solo año de la epoca
            $epoca_inicio = intval(explode('-', $request->epoca_inicio)[0]);
            $epoca_final = intval(explode('-', $request->epoca_final)[0]);
            //creamos el estilo
            $estilo = Estilo::create([
                'epoca_final' => $epoca_final,
                'nombre' => $request->nombre,
                'caracteristicas' => $request->caracteristicas,
                'epoca_inicio' => $epoca_inicio,
            ]);
            //si estilo no es nada lanzamos una excepcion
            if (!$estilo) {
                throw new \Exception("No se pudo guardar el estilo en la BD");
            }
            //redirigimos a los estilos con un mensaje
            return redirect('/estilo')->with('correcto', 'Estilo creado correctamente');
        } catch (\Throwable $e) {
            return redirect('/estilo')->withErrors(['error' => 'Error al crear el estilo']);
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
    public function showObras(Request $request)
    {
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        //buscamos si existe mod(para modificar) y obtenemos el id
        $mod = isset($request->modificar)? $request->modificar : 0;
        $id = $request->id;
        //si vista es false es que mostramos obras, obtenemos el nivel
        $vista = false;
        $nivel = UsuarioController::getNivel();

        //obtenemos el estilo
        $infoEstilo = Estilo::find($id);
        //obtenemos las obras del estilo
        $info = MiController::collectionArray($infoEstilo->obras()->get());

        if ($mod === 1) {
            //si modificamos sacamos todos los pintores
            $pintores = MiController::collectionArray(EstiloControlador::findAll());
        } else {
            //si no solo los pintores del estilo
            $pintores = MiController::collectionArray($infoEstilo->pintors()->get());
        }
        //pasamos el estilo a un array
        $infoEstilo = MiController::collectionArray($infoEstilo);
        return view('detalleEstilo', compact('vista', 'info', 'infoEstilo', 'nivel', 'pintores', 'mod'));
    }

    public function showPintor(Request $request)
    {
        //autentificacion
        if($this->comprobar() != null){
            return redirect($this->comprobar());
        }
        //buscamos si existe mod(para modificar) y obtenemos el id
        $mod = isset($request->modificar)? $request->modificar : 0;
        $id = $request->id;
        //obtenemos el nivel y decimos que vista es true
        $vista = true;
        $nivel = UsuarioController::getNivel();
        //obtenemos el estilo
        $infoEstilo = Estilo::find($id);
        //obtenemos los pintores del estilo
        $info = MiController::collectionArray($infoEstilo->pintors()->get());

        if ($mod === 1) {
            //si modificamos sacamos todos los pintores
            $pintores = MiController::collectionArray(EstiloControlador::findAll());
        } else {
            //si no solo los pintores del estilo
            $pintores = MiController::collectionArray($infoEstilo->pintors()->get());
        }
        //pasamos el estilo a un array
        $infoEstilo = MiController::collectionArray($infoEstilo);
        //retornamos la vista con la informacion
        return view('detalleEstilo', compact('vista', 'info', 'infoEstilo' ,'nivel', 'pintores', 'mod'));
    }

    public static function getNombres()
    {
        //buscamos solo el id y el nombre de los estilos y lo pasamos a array
        return MiController::collectionArray(Estilo::select('id', 'nombre')->get());
    }

    public static function getEstiloId($id): array
    {
        //sacamos el estilo con el id y lo pasamos a array
        $estiloTabla =  MiController::collectionArray(Estilo::find($id));
        //modificamos la fecha para que aparezca en la vista como una fecha
        $estiloTabla['epoca_final'] = $estiloTabla['epoca_final'] .'-01-01';
        $estiloTabla['epoca_inicio'] = $estiloTabla['epoca_inicio'].'-01-01';

        return $estiloTabla;
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Estilo $estilo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id)
    {
        try {
            //validamos
            $request->validate([
                'nombre' => 'required|max:50',
                'epoca_inicio' => 'required|date',
                'epoca_final' => 'nullable|date',
                'caracteristicas' => 'required|max:50',
                'id_pintor' => 'nullable|numeric',
            ]);
            //si esta seteado el id_pintor relacionamos el estilo con el pintor
            if (isset($request->id_pintor)) {
                !is_array(PintorControlador::getPintorId($request->id_pintor)) ? redirect()->back()->withErrors(['error' => 'Estilo no encontrado']): null;
                $estilo = Estilo::find($id);
                $estilo->pintors()->syncWithoutDetaching($request->id_pintor);
            }
            //Actualizamos el estilo
            Estilo::findOrFail($id)->update([
                'nombre' => $request->nombre,
                'descripcion' => $request->descripcion,
                'nacimiento' => $request->nacimiento,
                'fallecimiento' => $request->fallecimiento,
                'nacionalidad' => $request->nacionalidad,
            ]);
            //volvemos a donde estabamos con un mensaje
            return redirect()->back()->with('correcto', 'Estilo actualizado correctamente.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar estilo']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        try {
            //nos llega un json con una id
            $request->input('id');
            //buscamos el estilo y lo eliminamos
            $obra = Estilo::findOrFail($request->id);
            $obra->delete();
            //devolvemos un json con mensaje
            return response()->json([
                'mensaje' => 'Estilo borrada correctamente'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'mensaje' => 'No se ha podido borrar'
            ], 200);
        }
    }
}
