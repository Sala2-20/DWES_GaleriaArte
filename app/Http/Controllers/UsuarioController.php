<?php

namespace App\Http\Controllers;

use App\Models\User;
use FFI\Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends MiController
{
    public static function findAll()
    {
        return User::all();
    }
    //funcion que devuelve el nivel del user
    public static function getNivel()
    {
        //devuelve el nivel de un usuario
        return Auth::user()->nivel . '';
    }

    public function __invoke()
    {
        //autentificamos pero con la diferencia que aqui mandamos la vista del index
        $var = ($this->comprobar() != null) ? $this->comprobar() : '/pintores';
        if ($var === '/') {
            $var = 'index';
        } else {
            //si no llamamos a index
            return $this->index();
        }
        return view($var);
    }

    public function index()
    {
        //volvemos a comprobar para que no haya problemas
        if ($this->comprobar() != null) {
            //volvemos a llamar a index para retornar la vista index
            return $this->__invoke();
        } else {
            //retornamos la vista pintores
            return (new PintorControlador)->index();
        }
    }

    public function ajustes(){
        //autentificamos
        if($this->comprobar() != null){
            //redirigimos al index
            return redirect($this->comprobar());
        }else{
            //redirigimos a vista ajustes
            return view('ajustes');
        }
    }

    public function store(Request $request)
    {
        //validamos los datos
        $request->validate([
            'nombre' => 'required|max:30',
            'correo' => 'required|max:100|email',
            'contrasenia' => 'required|max:100',
        ]);
        //guardamos las credenciales en un array
        $credentials = $request->only('nombre', 'correo', 'contrasenia');
        $credentials['password'] = $credentials['contrasenia'];
        unset($credentials['contrasenia']);
        //mira que el user existe y es correcto
        if (Auth::attempt($credentials)) {
            //redirigimos a pintores
            return redirect("/pintores");
        }
        //redirigimos a index
        return redirect("/");
    }

    public function create(Request $request)
    {
        try {
            //validamos la info
            $request->validate([
                'nombre' => 'required|max:30',
                'correo' => 'required|email|max:100',
                'contrasenia' => 'required|max:100'
            ]);
            //miramos si esta seteado el nivel
            if (isset($request->nivel)) {
                //llamamos a otro create para el nivel
                return $this->createNivel($request);
            }
            //comprobamos la informacion
            if (
                !MiController::esAlfanumerico($request->nombre) ||
                !MiController::esAlfanumerico($request->contrasenia) || !MiController::esEmail($request->correo)
            ) {
                //si no es correcta redirigimos a index con error
                return redirect("/")->withErrors(['error' => 'Datos incorrectos']);
            }
            //creamos el usuario
            $usu = User::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'contrasenia' => bcrypt($request->contrasenia),
                'nivel' => 0
            ]);

            if (!$usu) {
                throw new \Exception("No se pudo guardar la obra en la BD");
            }
            //refrescamos los usuarios
            $usu->refresh();
            //autentificamos el usuario (como al acceder en el store)
            $credentials = $request->only('nombre', 'correo', 'contrasenia');
            $credentials['password'] = $credentials['contrasenia'];
            unset($credentials['contrasenia']);

            if (Auth::attempt($credentials)) {
                return redirect("/pintores");
            }
            //redirigimos a pintores
            return redirect("/pintores")->with('correcto', 'Usuario creado con exito');
        } catch (\Throwable $e) {
            return redirect("/")->withErrors(['error' => 'No es posible crear al usuario']);
        }
    }
    public function createNivel(Request $request)
    {
        try {
            //validamos la info
            $request->validate([
                'nivel' => 'required|numeric|max:1',
            ]);
            //validamos que este bien
            if (
                !MiController::esAlfanumerico($request->nombre) ||
                !MiController::esAlfanumerico($request->contrasenia) || !MiController::esEmail($request->correo)
            ) {
                return redirect("/")->withErrors(['error' => 'Datos incorrectos']);
            }
            //creamos el usuario
            $usu = User::create([
                'nombre' => $request->nombre,
                'correo' => $request->correo,
                'contrasenia' => bcrypt($request->contrasenia),
                'nivel' => $request->nivel
            ]);

            if (!$usu) {
                throw new \Exception("No se pudo guardar al usuario en la BD");
            }
            //volvemos atras con mensaje
            return redirect()->back()->with("correcto", "Se ha creado el usuario");
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => 'No es posible crear al usuario']);
        }
    }

    public function update(Request $request)
    {
        try {
            //validamos la info
            $request->validate([
                'nombre' => 'required|max:10',
                'correo' => 'required|email|max:100',
            ]);
            $id = Auth::id();
            //buscamos el usuario y lo actualizamos
            $usu = User::findOrFail($id)->update([
                'nombre' => $request->nombre,
                'correo' => $request->correo
            ]);
            //guardamos
            $usu->save();
            //redirigimos con mensaje
            return redirect()->back()->with('correcto', 'Actualizado correctamente');
        } catch (\Throwable $th) {
            return redirect()->back()->withErrors(['error' => 'Error al actualizar los datos']);
        }
    }

    public function show(Request $request)
    {
        //devolvemos un json con todos los usuarios
        $userArray = MiController::collectionArray(UsuarioController::findAll());
        return response()->json($userArray);
    }

    public function destroy()
    {
        try {
            //cogemos la id del usuario
            $usuario = User::find(Auth::id());

            if ($usuario) {
                //si existe lo eliminamos y mandamos un mensaje
                $usuario->delete();
                return redirect()->back()->with('usuarios.index')->with('correcto', 'Usuario eliminado correctamente.');
            }

            return redirect()->back()->with('usuarios.index')->withErrors(['error', 'Error al encontrar el usuario.']);
        } catch (\Throwable $th) {
            return redirect()->back()->with('usuarios.index')->withErrors(['error', 'Error al intentar borrar usuario.']);
        }
    }

    public function cerrar(Request $request)
    {
        //cerramos la sesion del usuario
        if (Auth::check()) {
            Auth::logout();
        }
        return redirect('/');  // Redirige a la página de inicio
    }
}
