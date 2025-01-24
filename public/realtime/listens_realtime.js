//var socket = io.connect('http://127.0.0.1:3000');
var socket = io.connect('http://181.143.243.206:3000');
 
//al actualizar la página eliminamos la sesión del usuario de sessionStorage
/*$(document).ready(function()
{  
    manageSessions.unset("login");
    message = "Dios";
    var alert = generaNotificacion('warning', message);
    alert.setTimeout(5000); 
});*/

//función anónima donde vamos añadiendo toda la funcionalidad del chat
$(function()
{  
    //actualizamos el sidebar que contiene los usuarios conectados cuando
    //alguno se conecta o desconecta, el parámetro son los usuarios online actualmente
    socket.on("updateSidebarUsers", function(usersOnline)
    {
        //limpiamos el sidebar donde almacenamos usuarios
        //$("#chatUsers").html("");
        //si hay usuarios conectados, para evitar errores
        if(!isEmptyObject(usersOnline))
        {
            //recorremos el objeto y los mostramos en el sidebar, los datos
            //están almacenados con {clave : valor}
            $.each(usersOnline, function(key, val)
            {
                $("#chatUsers").html('<h4>'+ key +'</h4>'); 
            })
        }
    });    
});


//cuando se emite el evento creaNotificacion
socket.on("actualizarSolicitudNumerosExec", function(data)
{
    var vistaAutos = data.vistaAutos; 
    var nombresUsuario = data.nombresUsuario; 

    $("#resultadoAutos").fadeOut().html(vistaAutos).fadeIn(); 

    notificacion("Notificación", nombresUsuario+" ha solicitado un número de auto");

    $.gritter.add({
        // (string | mandatory) the heading of the notification
        //title: 'This is a notice without an image!',
        // (string | mandatory) the text inside the notification
        text: nombresUsuario+" acaba de solicitar un número de auto",
        fade_in_speed: 100,
        fade_out_speed: 100,
        time: '4000'
    });  
    return false;
});   
 

//cuando se emite el evento creaNotificacion
socket.on("creaNotificacion", function(message)
{
    notificacion("Notificación", message);

    $.gritter.add({
        // (string | mandatory) the heading of the notification
        //title: 'This is a notice without an image!',
        // (string | mandatory) the text inside the notification
        text: message,
        fade_in_speed: 100,
        fade_out_speed: 100,
        time: '2000'
    });   
    return false;
});

socket.on('forceDisconnect', function(){
    socket.disconnect();
});

//cuando se emite el evento cerrarSesiones
socket.on("cerrarSesionesExec", function(data)
{
    prueba(data);  
});

//cuando se emite el evento usuariosOnline
socket.on("usuariosOnline", function(usuariosOnline)
{
  $.gritter.add({
        // (string | mandatory) the heading of the notification
        title: usuariosOnline,
        // (string | mandatory) the text inside the notification
        text: message,
        fade_in_speed: 100,
        fade_out_speed: 100,
        time: '2000'
    });

    return false;
});


//cuando se emite el evento creaNotificacion
socket.on("incContadorOficios", function(rad)
{
    $("#contadorOficios").html("<strong>"+rad+"</strong>");
});



//objeto para el manejo de sesiones
var manageSessions = {
    //obtenemos una sesión //getter
    get: function(key) {
        return sessionStorage.getItem(key);
    },
    //creamos una sesión //setter
    set: function(key, val) {
        return sessionStorage.setItem(key, val);
    },
    //limpiamos una sesión
    unset: function(key) {
        return sessionStorage.removeItem(key);
    }
};
 
//función que comprueba si un objeto está vacio, devuelve un boolean
function isEmptyObject(obj) 
{
    var name;
    for (name in obj) 
    {
        return false;
    }
    return true;
}
