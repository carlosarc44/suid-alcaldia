<?php  
class AuthController extends BaseController {

    public function showLogin()
    {
        // Verificamos si hay sesión activa
        if (Auth::check())
        {
            // Si tenemos sesión activa mostrará la página de inicio
            return Redirect::to('/procesos/reportes');
        }
        // Si no hay sesión activa mostramos el formulario
        return View::make('login');
    }
 
    public function postLogin()
    {  
        //include $_SERVER['DOCUMENT_ROOT']."/arco/public/js/prueba.php";

        //Obtenemos los datos del formulario
        $data = [
                 'Persona_documentoPersona' => Input::get('usuario'),
                 'password' => Input::get('password')
        ];
 
        // Verificamos los datos
        if (Auth::attempt($data, Input::get('remember'))) // Como segundo parámetro pasámos el checkbox para sabes si queremos recordar la contraseña
        {  
            //$usuario = Usuario::find(Auth::user()->idUsuario);
            $persona = Persona::find(Input::get('usuario'));

            Session::put('perfilUsuario', Auth::user()->Perfil_idPerfil);
            Session::put('documentoUsuario', Auth::user()->Persona_documentoPersona);
            Session::put('idUsuario', Auth::user()->idUsuario);
            Session::put('nombresUsuario', $persona->nombre); 
            Session::put('idAbogado', Util::traerIdAbogadoDocumento(Input::get('usuario')));

            // ===== LOGS ===== //  
            $accion = 1;//1 Inicia sesión
            $descripcion = "El usuario: ".$persona->nombre." inició sesión.";
            Util::almacenaLog($accion, $descripcion);
            // # LOGS ********* //

            //return Redirect::to('/inicio');
            if(Auth::user()->activoUsuario == 1)//Si el usuario está activo
            { 
                $mensaje = 1;//1 Acceso autorizado                    
                $array = array(Auth::user()->idUsuario, $persona->nombre, $mensaje, Auth::user()->Perfil_idPerfil);
                return json_encode($array);
            }
            else//Si el usuario está inactivo
            {
                $mensaje = 2;//2 Usuario Inactivo                
                $array = array(0, "", $mensaje);
                return json_encode($array);
            }

        }
        // Si los datos no son los correctos volvemos al login y mostramos un error
        else//Si el usuario está inactivo
        {
            $mensaje = 3;//3 Datos inválidos                
            $array = array(0, "", $mensaje);
            return json_encode($array);
        }
    }
 
    public function logOut()
    {
        // Cerramos la sesión
        Auth::logout();
        // Volvemos al login y mostramos un mensaje indicando que se cerró la sesión
        return Redirect::to('login')->with('error_message', 'Sesión cerrada correctamente');
    }
 
}

?>