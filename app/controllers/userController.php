<?php

namespace app\controllers;

use app\models\mainModels;

class userController extends mainModels
{

    /*----------  Controlador registrar empleado  ----------*/
    public function registrarEmpleadoControlador()
    {

        echo "<pre>";
        print_r($_POST);  // Imprime los datos enviados por POST
        echo "</pre>";

        # Almacenando datos#
        $nickname = $this->limpiarCadena($_POST['nickname']);
        $nombre = $this->limpiarCadena($_POST['nombre']);
        $apellido = $this->limpiarCadena($_POST['apellido']);
        $salario = $this->limpiarCadena($_POST['salario']);
        $comentarios = $this->limpiarCadena($_POST['comentarios']);
        $departamento = $this->limpiarCadena($_POST['departamento']);
        $genero = $this->limpiarCadena($_POST['genero']);
        $clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
        $clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);


        # Verificando campos obligatorios #
        if ($nickname == "" || $nombre == "" || $apellido == "" || $salario == "" || $comentarios == "" || $departamento == "" || $genero == "" || $clave1 == "" || $clave2 == "") {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }


        # Verificando integridad de los datos #
        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $nickname)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }
        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El NOMBRE no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El APELLIDO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $salario)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $comentarios)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $departamento)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $genero)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO no coincide con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }


        if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Las CLAVES no coinciden con el formato solicitado",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Verificando claves #
        if ($clave1 != $clave2) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Las contraseñas que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        } else {
            $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
        }

        # Verificando empleado #
        $check_empleado = $this->ejecutarConsulta("SELECT nickname FROM empleados WHERE nickname='$nickname'");
        if ($check_empleado->rowCount() > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "El USUARIO ingresado ya se encuentra registrado, por favor elija otro",
                "icono" => "error"
            ];
            return json_encode($alerta);
            exit();
        }

        # Directorio de imagenes #
        $img_dir = "../views/photos/";

        # Comprobar si se selecciono una imagen #
        if ($_FILES['empleado_foto']['name'] != "" && $_FILES['empleado_foto']['size'] > 0) {

            # Creando directorio #
            if (!file_exists($img_dir)) {
                if (!mkdir($img_dir, 0777)) {
                    $alerta = [
                        "tipo" => "simple",
                        "titulo" => "Ocurrió un error inesperado",
                        "texto" => "Error al crear el directorio",
                        "icono" => "error"
                    ];
                    return json_encode($alerta);
                    exit();
                }
            }

            # Verificando formato de imagenes #
            if (mime_content_type($_FILES['empleado_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['empleado_foto']['tmp_name']) != "image/png") {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado es de un formato no permitido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Verificando peso de imagen #
            if (($_FILES['empleado_foto']['size'] / 1024) > 5120) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen que ha seleccionado supera el peso permitido",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }

            # Nombre de la foto #
            $foto = str_ireplace(" ", "_", $nombre);
            $foto = $foto . "_" . rand(0, 100);

            # Extension de la imagen #
            switch (mime_content_type($_FILES['empleado_foto']['tmp_name'])) {
                case 'image/jpeg':
                    $foto = $foto . ".jpg";
                    break;
                case 'image/png':
                    $foto = $foto . ".png";
                    break;
            }

            chmod($img_dir, 0777);

            # Moviendo imagen al directorio #
            if (!move_uploaded_file($_FILES['empleado_foto']['tmp_name'], $img_dir . $foto)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "No podemos subir la imagen al sistema en este momento",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                exit();
            }
        } else {
            $foto = "";
        }


        $usuario_datos_reg = [
            [
                "campo_nombre" => "nickname",
                "campo_marcador" => ":Nickname",
                "campo_valor" => $nickname
            ],
            [
                "campo_nombre" => "nombre",
                "campo_marcador" => ":Nombre",
                "campo_valor" => $nombre
            ],
            [
                "campo_nombre" => "apellido",
                "campo_marcador" => ":Apellido",
                "campo_valor" => $apellido
            ],
            [
                "campo_nombre" => "salario",
                "campo_marcador" => ":Salario",
                "campo_valor" => $salario
            ],
            [
                "campo_nombre" => "comentarios",
                "campo_marcador" => ":Comentarios",
                "campo_valor" => $comentarios
            ],
            [
                "campo_nombre" => "genero",
                "campo_marcador" => ":Genero",
                "campo_valor" => $genero
            ],
            [
                "campo_nombre" => "departamento",
                "campo_marcador" => ":Departamento",
                "campo_valor" => $genero
            ],
            [
                "campo_nombre" => "empleado_clave",
                "campo_marcador" => ":Clave",
                "campo_valor" => $clave
            ],
            [
                "campo_nombre" => "empleado_foto",
                "campo_marcador" => ":Foto",
                "campo_valor" => $foto
            ],
            [
                "campo_nombre" => "empleado_creado",
                "campo_marcador" => ":Creado",
                "campo_valor" => date("Y-m-d H:i:s")
            ],
            [
                "campo_nombre" => "empleado_actualizado",
                "campo_marcador" => ":Actualizado",
                "campo_valor" => date("Y-m-d H:i:s")
            ]
        ];

        # Verificando si la imagen fue cargada #
        echo "<pre>";
        print_r($_FILES);
        echo "</pre>";

        $registrar_empleado = $this->guardarDatos("empleado", $usuario_datos_reg);

        echo "<pre>";
        print_r($registrar_empleado); // Para ver si se ejecutó correctamente
        echo "</pre>";

        if ($registrar_empleado->rowCount() == 1) {
            $alerta = [
                "tipo" => "limpiar",
                "titulo" => "Empleado registrado",
                "texto" => "El empleado " . $nombre . " " . $apellido . " se registro con exito",
                "icono" => "success"
            ];
        } else {

            if (is_file($img_dir . $foto)) {
                chmod($img_dir . $foto, 0777);
                unlink($img_dir . $foto);
            }

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No se pudo registrar el empleado, por favor intente nuevamente",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);
    }



    /*----------  Controlador listar empleado  ----------*/
    public function listarEmpleadoControlador($pagina, $registros, $url, $busqueda)
    {

        $pagina = $this->limpiarCadena($pagina);
        $registros = $this->limpiarCadena($registros);

        $url = $this->limpiarCadena($url);
        $url = APP_URL . $url . "/";

        $busqueda = $this->limpiarCadena($busqueda);
        $tabla = "";

        $pagina = (isset($pagina) && $pagina > 0) ? (int) $pagina : 1;
        $inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;

        if (isset($busqueda) && $busqueda != "") {

            $consulta_datos = "SELECT * FROM empleados WHERE ((id!='" . $_SESSION['id'] . "' AND id!='1') AND (nombre LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%')) ORDER BY apellido ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(empleado_id) FROM empleados WHERE ((id!='" . $_SESSION['id'] . "' AND id!='1') AND (apellido LIKE '%$busqueda%' OR apellido LIKE '%$busqueda%'))";
        } else {

            $consulta_datos = "SELECT * FROM empleados WHERE id!='" . $_SESSION['id'] . "' AND id!='1' ORDER BY apellido ASC LIMIT $inicio,$registros";

            $consulta_total = "SELECT COUNT(id) FROM empleados WHERE id!='" . $_SESSION['id'] . "' AND id!='1'";
        }

        $datos = $this->ejecutarConsulta($consulta_datos);
        $datos = $datos->fetchAll();

        $total = $this->ejecutarConsulta($consulta_total);
        $total = (int) $total->fetchColumn();

        $numeroPaginas = ceil($total / $registros);

        $tabla .= '
    	        <div class="table-container">
    	        <table class="table is-bordered is-striped is-narrow is-hoverable is-fullwidth">
    	            <thead>
    	                <tr>
    	                    <th class="has-text-centered">#</th>
    	                    <th class="has-text-centered">Nombre</th>
    	                    <th class="has-text-centered">Apellido</th>
    	                    <th class="has-text-centered">Salario</th>
    	                    <th class="has-text-centered">Fecha_ingreso</th>
    	                    <th class="has-text-centered">Actualizado</th>
    	                    <th class="has-text-centered">Comentario</th>
    	                    <th class="has-text-centered">Genero</th>
    	                    <th class="has-text-centered">Departamento</th>
    	                    <th class="has-text-centered" colspan="3">Opciones</th>
    	                </tr>
    	            </thead>
    	            <tbody>
    	    ';

        if ($total >= 1 && $pagina <= $numeroPaginas) {
            $contador = $inicio + 1;
            $pag_inicio = $inicio + 1;
            foreach ($datos as $rows) {
                $tabla .= '
    					<tr class="has-text-centered" >
    						<td>' . $contador . '</td>
    						<td>' . $rows['apellido'] . ' ' . $rows['apellido'] . '</td>
    						<td>' . $rows['salario'] . '</td>
    						<td>' . $rows['comentario'] . '</td>
    						<td>' . $rows['genero'] . '</td>
    						<td>' . $rows['departamento'] . '</td>
    						<td>' . date("d-m-Y  h:i:s A", strtotime($rows['empleado_creado'])) . '</td>
    						<td>' . date("d-m-Y  h:i:s A", strtotime($rows['empleado_actualizado'])) . '</td>
    						<td>
    		                    <a href="' . APP_URL . 'userPhoto/' . $rows['id'] . '/" class="button is-info is-rounded is-small">Foto</a>
    		                </td>
    		                <td>
    		                    <a href="' . APP_URL . 'userUpdate/' . $rows['id'] . '/" class="button is-success is-rounded is-small">Actualizar</a>
    		                </td>
    		                <td>
    		                	<form class="FormularioAjax" action="' . APP_URL . 'app/ajax/empleadoAjax.php" method="POST" autocomplete="off" >

    		                		<input type="hidden" name="modulo_empleado" value="eliminar">
    		                		<input type="hidden" name="id" value="' . $rows['id'] . '">

    		                    	<button type="submit" class="button is-danger is-rounded is-small">Eliminar</button>
    		                    </form>
    		                </td>
    					</tr>
    				';
                $contador++;
            }
            $pag_final = $contador - 1;
        } else {
            if ($total >= 1) {
                $tabla .= '
    					<tr class="has-text-centered" >
    		                <td colspan="7">
    		                    <a href="' . $url . '1/" class="button is-link is-rounded is-small mt-4 mb-4">
    		                        Haga clic acá para recargar el listado
    		                    </a>
    		                </td>
    		            </tr>
    				';
            } else {
                $tabla .= '
    					<tr class="has-text-centered" >
    		                <td colspan="7">
    		                    No hay registros en el sistema
    		                </td>
    		            </tr>
    				';
            }
        }

        $tabla .= '</tbody></table></div>';

        ### Paginacion ###
        if ($total > 0 && $pagina <= $numeroPaginas) {
            $tabla .= '<p class="has-text-right">Mostrando empleados <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';

            $tabla .= $this->paginadorTablas($pagina, $numeroPaginas, $url, 7);
        }

        return $tabla;
    }


    // /*----------  Controlador eliminar empleado  ----------*/
    // public function eliminarUsuarioControlador()
    // {

    //     $id = $this->limpiarCadena($_POST['empleado_id']);

    //     if ($id == 1) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No podemos eliminar el empleado principal del sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Verificando empleado #
    //     $datos = $this->ejecutarConsulta("SELECT * FROM empleado WHERE empleado_id='$id'");
    //     if ($datos->rowCount() <= 0) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos encontrado el empleado en el sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     } else {
    //         $datos = $datos->fetch();
    //     }

    //     $eliminarUsuario = $this->eliminarRegistro("nombre", "empleado_id", $id);

    //     if ($eliminarUsuario->rowCount() == 1) {

    //         if (is_file("../views/fotos/" . $datos['empleado_foto'])) {
    //             chmod("../views/fotos/" . $datos['empleado_foto'], 0777);
    //             unlink("../views/fotos/" . $datos['empleado_foto']);
    //         }

    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Usuario eliminado",
    //             "texto" => "El empleado " . $datos['apellido'] . " " . $datos['apellido'] . " ha sido eliminado del sistema correctamente",
    //             "icono" => "success"
    //         ];
    //     } else {

    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos podido eliminar el empleado " . $datos['apellido'] . " " . $datos['apellido'] . " del sistema, por favor intente nuevamente",
    //             "icono" => "error"
    //         ];
    //     }

    //     return json_encode($alerta);
    // }


    // /*----------  Controlador actualizar empleado  ----------*/
    // public function actualizarUsuarioControlador()
    // {

    //     $id = $this->limpiarCadena($_POST['empleado_id']);

    //     # Verificando empleado #
    //     $datos = $this->ejecutarConsulta("SELECT * FROM empleado WHERE empleado_id='$id'");
    //     if ($datos->rowCount() <= 0) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos encontrado el empleado en el sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     } else {
    //         $datos = $datos->fetch();
    //     }

    //     $admin_usuario = $this->limpiarCadena($_POST['administrador_usuario']);
    //     $admin_clave = $this->limpiarCadena($_POST['administrador_clave']);

    //     # Verificando campos obligatorios admin #
    //     if ($admin_usuario == "" || $admin_clave == "") {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No ha llenado todos los campos que son obligatorios, que corresponden a su USUARIO y CLAVE",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $admin_usuario)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "Su USUARIO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $admin_clave)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "Su CLAVE no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Verificando administrador #
    //     $check_admin = $this->ejecutarConsulta("SELECT * FROM empleado WHERE empleado_rol='$admin_usuario' AND empleado_id='" . $_SESSION['id'] . "'");
    //     if ($check_admin->rowCount() == 1) {

    //         $check_admin = $check_admin->fetch();

    //         if ($check_admin['empleado_rol'] != $admin_usuario || !password_verify($admin_clave, $check_admin['empleado_clave'])) {

    //             $alerta = [
    //                 "tipo" => "simple",
    //                 "titulo" => "Ocurrió un error inesperado",
    //                 "texto" => "USUARIO o CLAVE de administrador incorrectos",
    //                 "icono" => "error"
    //             ];
    //             return json_encode($alerta);
    //             exit();
    //         }
    //     } else {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "USUARIO o CLAVE de administrador incorrectos",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }


    //     # Almacenando datos#
    //     $nombre = $this->limpiarCadena($_POST['nombre']);
    //     $apellido = $this->limpiarCadena($_POST['apellido']);
    //     $salario = $this->limpiarCadena($_POST['salario']);
    //     $comentarios = $this->limpiarCadena($_POST['comentarios']);
    //     $departamento = $this->limpiarCadena($_POST['departamento']);
    //     $genero = $this->limpiarCadena($_POST['genero']);
    //     $clave1 = $this->limpiarCadena($_POST['usuario_clave_1']);
    //     $clave2 = $this->limpiarCadena($_POST['usuario_clave_2']);

    //     # Verificando campos obligatorios #
    //     if ($nombre == "" || $apellido == "" || $salario == "" || $comentarios == "" || $departamento == "" | $genero == "") {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No has llenado todos los campos que son obligatorios",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Verificando integridad de los datos #
    //     if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El NOMBRE no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El APELLIDO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $salario)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El USUARIO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $comentarios)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El USUARIO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $genero)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El USUARIO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }


    //     if ($this->verificarDatos("[a-zA-Z0-9]{4,20}", $departamento)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "El USUARIO no coincide con el formato solicitado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }


    //     # Verificando claves #
    //     if ($clave1 != "" || $clave2 != "") {
    //         if ($this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave1) || $this->verificarDatos("[a-zA-Z0-9$@.-]{7,100}", $clave2)) {

    //             $alerta = [
    //                 "tipo" => "simple",
    //                 "titulo" => "Ocurrió un error inesperado",
    //                 "texto" => "Las CLAVES no coinciden con el formato solicitado",
    //                 "icono" => "error"
    //             ];
    //             return json_encode($alerta);
    //             exit();
    //         } else {
    //             if ($clave1 != $clave2) {

    //                 $alerta = [
    //                     "tipo" => "simple",
    //                     "titulo" => "Ocurrió un error inesperado",
    //                     "texto" => "Las nuevas CLAVES que acaba de ingresar no coinciden, por favor verifique e intente nuevamente",
    //                     "icono" => "error"
    //                 ];
    //                 return json_encode($alerta);
    //                 exit();
    //             } else {
    //                 $clave = password_hash($clave1, PASSWORD_BCRYPT, ["cost" => 10]);
    //             }
    //         }
    //     } else {
    //         $clave = $datos['empleado_clave'];
    //     }

    //     $usuario_datos_up = [
    //         [
    //             "campo_nombre" => "nombre",
    //             "campo_marcador" => ":Nombre",
    //             "campo_valor" => $nombre
    //         ],
    //         [
    //             "campo_nombre" => "apellido",
    //             "campo_marcador" => ":Apellido",
    //             "campo_valor" => $apellido
    //         ],
    //         [
    //             "campo_nombre" => "salario ",
    //             "campo_marcador" => ":Salario",
    //             "campo_valor" => $salario
    //         ],
    //         [
    //             "campo_nombre" => "comentarios",
    //             "campo_marcador" => ":Comentarios",
    //             "campo_valor" => $comentarios
    //         ],
    //         [
    //             "campo_nombre" => "genero",
    //             "campo_marcador" => ":Genero",
    //             "campo_valor" => $genero
    //         ],
    //         [
    //             "campo_nombre" => "departamento",
    //             "campo_marcador" => ":Departamento",
    //             "campo_valor" => $genero
    //         ],
    //         [
    //             "campo_nombre" => "empleado_clave",
    //             "campo_marcador" => ":Clave",
    //             "campo_valor" => $clave
    //         ],
    //         [
    //             "campo_nombre" => "empleado_actualizado",
    //             "campo_marcador" => ":Actualizado",
    //             "campo_valor" => date("Y-m-d H:i:s")
    //         ]
    //     ];

    //     $condicion = [
    //         "condicion_campo" => "empleado_id",
    //         "condicion_marcador" => ":ID",
    //         "condicion_valor" => $id
    //     ];

    //     if ($this->actualizarDatos("empleado", $usuario_datos_up, $condicion)) {

    //         if ($id == $_SESSION['id']) {
    //             $_SESSION['nombre'] = $nombre;
    //             $_SESSION['apellido'] = $apellido;
    //         }

    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Usuario actualizado",
    //             "texto" => "Los datos del empleado " . $datos['apellido'] . " " . $datos['apellido'] . " se actualizaron correctamente",
    //             "icono" => "success"
    //         ];
    //     } else {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos podido actualizar los datos del empleado " . $datos['apellido'] . " " . $datos['apellido'] . ", por favor intente nuevamente",
    //             "icono" => "error"
    //         ];
    //     }

    //     return json_encode($alerta);
    // }


    // /*----------  Controlador eliminar foto empleado  ----------*/
    // public function eliminarFotoUsuarioControlador()
    // {

    //     $id = $this->limpiarCadena($_POST['empleado_id']);

    //     # Verificando empleado #
    //     $datos = $this->ejecutarConsulta("SELECT * FROM empleado WHERE empleado_id='$id'");
    //     if ($datos->rowCount() <= 0) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos encontrado el empleado en el sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     } else {
    //         $datos = $datos->fetch();
    //     }

    //     # Directorio de imagenes #
    //     $img_dir = "../views/fotos/";

    //     chmod($img_dir, 0777);

    //     if (is_file($img_dir . $datos['empleado_foto'])) {

    //         chmod($img_dir . $datos['empleado_foto'], 0777);

    //         if (!unlink($img_dir . $datos['empleado_foto'])) {
    //             $alerta = [
    //                 "tipo" => "simple",
    //                 "titulo" => "Ocurrió un error inesperado",
    //                 "texto" => "Error al intentar eliminar la foto del empleado, por favor intente nuevamente",
    //                 "icono" => "error"
    //             ];
    //             return json_encode($alerta);
    //             exit();
    //         }
    //     } else {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos encontrado la foto del empleado en el sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     $usuario_datos_up = [
    //         [
    //             "campo_nombre" => "empleado_foto",
    //             "campo_marcador" => ":Foto",
    //             "campo_valor" => ""
    //         ],
    //         [
    //             "campo_nombre" => "empleado_actualizado",
    //             "campo_marcador" => ":Actualizado",
    //             "campo_valor" => date("Y-m-d H:i:s")
    //         ]
    //     ];

    //     $condicion = [
    //         "condicion_campo" => "empleado_id",
    //         "condicion_marcador" => ":ID",
    //         "condicion_valor" => $id
    //     ];

    //     if ($this->actualizarDatos("empleado", $usuario_datos_up, $condicion)) {

    //         if ($id == $_SESSION['id']) {
    //             $_SESSION['foto'] = "";
    //         }

    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Foto eliminada",
    //             "texto" => "La foto del empleado " . $datos['apellido'] . " " . $datos['apellido'] . " se elimino correctamente",
    //             "icono" => "success"
    //         ];
    //     } else {
    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Foto eliminada",
    //             "texto" => "No hemos podido actualizar algunos datos del empleado " . $datos['apellido'] . " " . $datos['apellido'] . ", sin embargo la foto ha sido eliminada correctamente",
    //             "icono" => "warning"
    //         ];
    //     }

    //     return json_encode($alerta);
    // }


    // /*----------  Controlador actualizar foto empleado  ----------*/
    // public function actualizarFotoUsuarioControlador()
    // {

    //     $id = $this->limpiarCadena($_POST['empleado_id']);

    //     # Verificando empleado #
    //     $datos = $this->ejecutarConsulta("SELECT * FROM empleado WHERE empleado_id='$id'");
    //     if ($datos->rowCount() <= 0) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No hemos encontrado el empleado en el sistema",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     } else {
    //         $datos = $datos->fetch();
    //     }

    //     # Directorio de imagenes #
    //     $img_dir = "../views/fotos/";

    //     # Comprobar si se selecciono una imagen #
    //     if ($_FILES['empleado_foto']['name'] == "" && $_FILES['empleado_foto']['size'] <= 0) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No ha seleccionado una foto para el empleado",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Creando directorio #
    //     if (!file_exists($img_dir)) {
    //         if (!mkdir($img_dir, 0777)) {
    //             $alerta = [
    //                 "tipo" => "simple",
    //                 "titulo" => "Ocurrió un error inesperado",
    //                 "texto" => "Error al crear el directorio",
    //                 "icono" => "error"
    //             ];
    //             return json_encode($alerta);
    //             exit();
    //         }
    //     }

    //     # Verificando formato de imagenes #
    //     if (mime_content_type($_FILES['empleado_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['empleado_foto']['tmp_name']) != "image/png") {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "La imagen que ha seleccionado es de un formato no permitido",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Verificando peso de imagen #
    //     if (($_FILES['empleado_foto']['size'] / 1024) > 5120) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "La imagen que ha seleccionado supera el peso permitido",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Nombre de la foto #
    //     if ($datos['empleado_foto'] != "") {
    //         $foto = explode(".", $datos['empleado_foto']);
    //         $foto = $foto[0];
    //     } else {
    //         $foto = str_ireplace(" ", "_", $datos['apellido']);
    //         $foto = $foto . "_" . rand(0, 100);
    //     }


    //     # Extension de la imagen #
    //     switch (mime_content_type($_FILES['empleado_foto']['tmp_name'])) {
    //         case 'image/jpeg':
    //             $foto = $foto . ".jpg";
    //             break;
    //         case 'image/png':
    //             $foto = $foto . ".png";
    //             break;
    //     }

    //     chmod($img_dir, 0777);

    //     # Moviendo imagen al directorio #
    //     if (!move_uploaded_file($_FILES['empleado_foto']['tmp_name'], $img_dir . $foto)) {
    //         $alerta = [
    //             "tipo" => "simple",
    //             "titulo" => "Ocurrió un error inesperado",
    //             "texto" => "No podemos subir la imagen al sistema en este momento",
    //             "icono" => "error"
    //         ];
    //         return json_encode($alerta);
    //         exit();
    //     }

    //     # Eliminando imagen anterior #
    //     if (is_file($img_dir . $datos['empleado_foto']) && $datos['empleado_foto'] != $foto) {
    //         chmod($img_dir . $datos['empleado_foto'], 0777);
    //         unlink($img_dir . $datos['empleado_foto']);
    //     }

    //     $usuario_datos_up = [
    //         [
    //             "campo_nombre" => "empleado_foto",
    //             "campo_marcador" => ":Foto",
    //             "campo_valor" => $foto
    //         ],
    //         [
    //             "campo_nombre" => "empleado_actualizado",
    //             "campo_marcador" => ":Actualizado",
    //             "campo_valor" => date("Y-m-d H:i:s")
    //         ]
    //     ];

    //     $condicion = [
    //         "condicion_campo" => "empleado_id",
    //         "condicion_marcador" => ":ID",
    //         "condicion_valor" => $id
    //     ];

    //     if ($this->actualizarDatos("empleado", $usuario_datos_up, $condicion)) {

    //         if ($id == $_SESSION['id']) {
    //             $_SESSION['foto'] = $foto;
    //         }

    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Foto actualizada",
    //             "texto" => "La foto del empleado " . $datos['apellido'] . " " . $datos['apellido'] . " se actualizo correctamente",
    //             "icono" => "success"
    //         ];
    //     } else {

    //         $alerta = [
    //             "tipo" => "recargar",
    //             "titulo" => "Foto actualizada",
    //             "texto" => "No hemos podido actualizar algunos datos del empleado " . $datos['apellido'] . " " . $datos['apellido'] . " , sin embargo la foto ha sido actualizada",
    //             "icono" => "warning"
    //         ];
    //     }

    //     return json_encode($alerta);
    // }
}
