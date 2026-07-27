<!DOCTYPE html>
<html>
<head>
<title>SUID © :: Control Disciplinario</title>
<meta charset="utf-8">
<link rel="icon" href="{{ asset('favicon.ico')}}">   
<link rel="stylesheet" href="{{asset('css/bootstrap/css/bootstrap.min.css')}}">
<link href="{{asset('css/login.css?v=2')}}" rel='stylesheet' type='text/css' />
<meta name="viewport" content="width=device-width, initial-scale=1">
<script type="application/x-javascript"> addEventListener("load", function() { setTimeout(hideURLbar, 0); }, false); function hideURLbar(){ window.scrollTo(0,1); } </script>
<style>
.login-alert{
	margin: 0 0 20px 0;
}
.alert-error{
	color: #11a14e;
}
</style>

<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>

<!-- Socket io -->
<!--
<script src="http://127.0.0.1:3000/socket.io/socket.io.js"></script>
<script src="https://cdn.socket.io/socket.io-1.0.0.js"></script>-->
<script src="https://suid.manizales.gov.co/socket.io/socket.io.js"></script>
<!-- #Socket io -->

<script src="{{ asset('realtime/listens_realtime.js') }}"></script>
</head>
 
<body>
<!--<div class="bg-overlay"></div>-->
<div style="position: absolute; top: 70px; right:40px; padding-left:40px; z-index: 999999;">
	<img src="{{asset('img/logo-2024-b.png')}}" height="80">
</div>
	<div class="main">
		<form id="login">
    		<h1><img src="{{asset('img/logoSuidWebBlanco.png')}}" height="70"> </h1>          
  			<div class="inset">
  			  	@if(Session::has('error_message'))
                	<div class="alert-error">
                    	{{ Session::get('error_message') }}                    
                	</div>
            	@endif
	  			<p>
	    		 	<label for="email" style="color:#fff">Usuario:</label>
   	 				<input type="text" placeholder="Usuario" id="usuario" name="usuario" autofocus required/>
				</p>
  				<p>
				    <label for="password" style="color:#fff">Contraseña:</label>
				    <input type="password" placeholder="Contraseña" id="password" name="password" required/>
  				</p>
 			</div>
 	 
			<p class="p-container">			    
			    <input type="submit" class="btn btn-success btn-block" value="Ingresar">
			</p>

			<div class="login-alert" id="mensaje-error"></div>
		</form>
	</div>  
</body>
<script>
const element = document.querySelector('form');
element.addEventListener('submit', event => {
  event.preventDefault();
  ingresar();
});
    
function ingresar()
{  
    var usuario = document.getElementById("usuario").value;
    var password = document.getElementById("password").value;

    if(usuario == "")
    {
        alert("Ingrese el usuario"); 
        document.getElementById('usuario').focus();
        return false;
    }
    else if(password == "")
    {
        alert("Ingrese la contraseña"); 
        document.getElementById('password').focus();
        return false;
    }

    var ruta = "{{URL::to('login/')}}";

    var parametros = { 
    "usuario" : usuario,
    "password" : password
    };

    $.ajax({                
        data:  parametros,
        url:   ruta,
        type:  'post',
        success:  function (responseText) {  
            var arrayJS = JSON.parse(responseText);
   
            // extrae los valores del array
            const idUsuario = arrayJS[0];
            const nombresPersona = arrayJS[1];
            const mensaje = arrayJS[2]; 
            const perfil = arrayJS[3]; 

            if(mensaje == 1) //1 Autorizado el acceso
            { 
                //Creamos la sesión login y lanzamos el evento loginUser pasando el nombre del usuario que se ha conectado
                //manageSessions.set("login", idUsuario);
                //Llamado al evento ingresaUsuario, el cuál creará un nuevo socket asociado al usuario
                //socket.emit("ingresaUsuario", nombresPersona);

                //alert(manageSessions.get("login"));

                /*var loc = window.location;
                var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
                var url = loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length));      
              
                window.location = url+"index";*/

                //2 Director
                if (perfil === 2) {
                    window.location = "procesos/reportes";                    
                }
                //6 Líder juzgamiento
                else if (perfil === 6) {
                    window.location = "procesos/reparto-juzgamiento";                    
                }
                //3 Secretaria
                else if (perfil === 3) {
                    window.location = "quejas/quejasEnviar";                    
                } 
                else {
                    window.location = "procesos/activos";
                }
            }             
            else if(mensaje == 2)//2 Usuario Inactivo 
            {
                $("#mensaje-error").html('<p class="alert-error"><b>Usuario inactivo.  Contacte al Administrador</b></p>');
            }
            else if(mensaje == 3)//3 Login o contraseña inválida 
            {
                $("#mensaje-error").html('<p class="alert-error"><b>Usuario o contraseña inválidos</b></p>');
            }
        },
        error: function(responseText) {
          alert("Error.  Contacte al administrador (Cod.Error.login 116)");
        }
    });
}
</script>
</html>