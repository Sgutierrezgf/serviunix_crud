<?php

require_once "../../config/app.php";
require_once "../views/inc/session.php";
require_once "../../autoload.php";

use app\controllers\userController;

if (isset($_POST['modulo_empleado'])) {

	$insUsuario = new userController();

	if ($_POST['modulo_empleado'] == "registrar") {
		echo $insUsuario->registrarEmpleadoControlador();
	}

	// if($_POST['modulo_empleado']=="eliminar"){
	// 	echo $insUsuario->eliminarUsuarioControlador();
	// }

	// if($_POST['modulo_empleado']=="actualizar"){
	// 	echo $insUsuario->actualizarUsuarioControlador();
	// }

	// if($_POST['modulo_empleado']=="eliminarFoto"){
	// 	echo $insUsuario->eliminarFotoUsuarioControlador();
	// }

	// if($_POST['modulo_empleado']=="actualizarFoto"){
	// 	echo $insUsuario->actualizarFotoUsuarioControlador();
	// }

} else {
	session_destroy();
	header("Location: " . APP_URL . "login/");
}
