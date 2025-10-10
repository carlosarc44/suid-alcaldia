// Usuarios conectados en la sesión del socket
const usuariosOnline = {};

// Requerir Socket.IO v4+
const { Server } = require('socket.io');

// Crear nueva instancia del servidor en puerto 3000
const io = new Server(3000);

console.log('Socket.IO corriendo en puerto 3000');

io.on('connection', arranque);

function arranque(socketUsuario) {
    // Escucha usuario que ingresa
    socketUsuario.on("ingresaUsuario", function(username) {
        // Si ya existe el usuario
        if (usuariosOnline[username]) {
            socketUsuario.emit("creaNotificacion", "Usuario ya está online");
            return;
        }
        socketUsuario.username = username;
        usuariosOnline[username] = socketUsuario.username;

        // Notificación global y bienvenida
        socketUsuario.broadcast.emit("creaNotificacion", `${socketUsuario.username} se ha conectado.`);
        socketUsuario.emit("creaNotificacion", `Bienvenido ${socketUsuario.username}.`);
        io.emit("updateSidebarUsers", usuariosOnline);
    });

    // Actualiza solicitud de números
    socketUsuario.on('actualizarSolicitudNumeros', function(data) {
        socketUsuario.broadcast.emit("actualizarSolicitudNumerosExec", data);
    });

    // Prueba id
    socketUsuario.on('pruebaId', function(data) {
        socketUsuario.broadcast.emit("creaNotificacion", data);
    });

    socketUsuario.on('pruebaId2', (data) => {
        console.log(socketUsuario.id);
    });

    // Cerrar sesiones
    socketUsuario.on('cerrarSesiones', function(data) {
        io.emit("creaNotificacion", "CERRAR SESIONES EMIT2444");
        socketUsuario.broadcast.emit("creaNotificacion", "44444444");
    });

    // Nuevo mensaje
    socketUsuario.on('addNewMessage', function(message) {
        socketUsuario.emit("refreshChat", "msg", "Yo : " + message + ".");
        socketUsuario.broadcast.emit("refreshChat", "msg", socketUsuario.username + " dice: " + message + ".");
    });

    // Contador oficios
    socketUsuario.on('contadorOficios', function(rad) {
        io.emit("incContadorOficios", rad);
    });

    // Desconexión
    socketUsuario.on("disconnect", function() {
        if (typeof(socketUsuario.username) === "undefined") return;
        delete usuariosOnline[socketUsuario.username];
        io.emit("updateSidebarUsers", usuariosOnline);
    });
}
