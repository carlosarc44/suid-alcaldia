//objecto para guardar en la sesión del socket a los que se vayan conectando
var usuariosOnline = {};
 
var io = require('socket.io').listen(3000);
 
//al conectar un usuario||socket, este evento viene predefinido por socketio
io.sockets.on('connection', arranque);

function arranque(socketUsuario) 
{
	//cuando el usuario conecta al chat comprobamos si está logueado
	//el parámetro es la sesión login almacenada con sessionStorage
	//******* ESCUCHA ************************************
	socketUsuario.on("ingresaUsuario", function(username)
	{
		//si existe el nombre de usuario en el chat
		if(usuariosOnline[username])
		{
			socketUsuario.emit("creaNotificacion", "Usuario ya está online");
			return;
		}

		//Guardamos el nombre de usuario en la sesión del socket para este cliente
		socketUsuario.username = username;
		//añadimos al usuario a la lista global donde almacenamos usuarios
		usuariosOnline[username] = socketUsuario.username;

		//******* EMITE ************************************
		//actualizamos la lista de usuarios en el lado del cliente
		//socketUsuario.broadcast.emit("usuariosOnline", Object.keys(usuariosOnline).length);
		//crea la notificación para todos los usuarios conectados excepto el usuario que se está conectando
		socketUsuario.broadcast.emit("creaNotificacion", socketUsuario.username + " se ha conectado.");
		//mostramos al cliente como que se ha conectado
		socketUsuario.emit("creaNotificacion",  "Bienvenido " + socketUsuario.username + ".");

		io.sockets.emit("updateSidebarUsers", usuariosOnline);

		//**************************************************		
	});
 	//**************************************************
 	
 	//******* ESCUCHA ************************************
	socketUsuario.on('actualizarSolicitudNumeros', function(data) 
	{   
	    //Emite para todos los usuarios
		socketUsuario.broadcast.emit("actualizarSolicitudNumerosExec", data);		
	});
	//******* ESCUCHA ************************************

	//******* ESCUCHA ************************************
	socketUsuario.on('pruebaId', function(data) 
	{  
	    //Emite para todos los usuarios
		socketUsuario.broadcast.emit("creaNotificacion", data);
	});

	socketUsuario.on('pruebaId2', (data)=>{
	    console.log(socketUsuario.id);
	});
	//******* ESCUCHA ************************************
	 	
 	//******* ESCUCHA ************************************
	socketUsuario.on('cerrarSesiones', function(data) 
	{   
	    //Emite para todos los usuarios
		//socketUsuario.broadcast.emit("creaNotificacion", " CERRAR SESIONES BROADCAST2");

		//socketUsuario.broadcast.emit("forceDisconnect");

		//Emite para el propio usuario
		io.sockets.emit("creaNotificacion", "CERRAR SESIONES EMIT2444");
		socketUsuario.broadcast.emit("creaNotificacion", "44444444");
	});
	//******* ESCUCHA ************************************

	//cuando un usuario envia un nuevo mensaje, el parámetro es el 
	//mensaje que ha escrito en la caja de texto
	//******* ESCUCHA ************************************
	socketUsuario.on('addNewMessage', function(message) 
	{
		//con socketUsuario.emit, el mensaje es para mi
		socketUsuario.emit("refreshChat", "msg", "Yo : " + message + ".");
		//con socketUsuario.broadcast.emit, es para el resto de usuarios
		socketUsuario.broadcast.emit("refreshChat", "msg", socketUsuario.username + " dice: " + message + ".");
	});
	//******* ESCUCHA ************************************

	//******* ESCUCHA ************************************
	socketUsuario.on('contadorOficios', function(rad) 
	{
		//Emite para todos los usuarios
		io.sockets.emit("incContadorOficios", rad);
	});
	//******* ESCUCHA ************************************
 
	//cuando el usuario cierra o actualiza el navegador
	socketUsuario.on("disconnect", function()
	{
		//si el usuario, por ejemplo, sin estar logueado refresca la
		//página, el typeof del socketUsuario username es undefined, y el mensaje sería 
		//El usuario undefined se ha desconectado del chat, con ésto lo evitamos
		if(typeof(socketUsuario.username) == "undefined")
		{
			return;
		}
		//en otro caso, eliminamos al usuario
		delete usuariosOnline[socketUsuario.username];
		//actualizamos la lista de usuarios en el chat, zona cliente
		io.sockets.emit("updateSidebarUsers", usuariosOnline);
		//emitimos el mensaje global a todos los que están conectados con broadcasts
		//socketUsuario.broadcast.emit("refreshChat", "desconectado", "El usuario " + socketUsuario.username + " se ha desconectado del chat.");
	});
}