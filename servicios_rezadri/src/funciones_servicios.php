<?php
require "cfg_bd.php";


function obtener_usuario($usuario, $clave)
{

    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion) {
        $respuesta["error"] = "No se ha podido establecer conexion";
    } else {


        mysqli_set_charset($conexion, "utf8");

        $consulta = "select * from usuarios where BINARY usuario='" . $usuario . "' and clave='" . $clave . "'";
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $respuesta["error"] = "No se han obtenido tuplas";
        } else {

            if (mysqli_num_rows($resultado) > 0) {

                $respuesta["usuario"] = mysqli_fetch_assoc($resultado);
            } else {
                $respuesta["mensaje"] = "No se han obtenido usuarios";
            }
            mysqli_free_result($resultado);
            mysqli_close($conexion);
        }
    }
    return $respuesta;
}

function repetido($tabla, $columna, $valor, $idUsuario = null)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta['error'] = 'Imposible conectar. Error número: ' . mysqli_connect_errno() . ':' . mysqli_connect_error();
    else {
        $respuesta['repetido'] = false;
        $clausula = '';
        if (isset($idUsuario)) {
            $clausula = ' AND id<>' . $idUsuario;
        }

        mysqli_set_charset($conexion, 'utf8');
        $consulta = "select " . $columna . " from " . $tabla . " where " . $columna . "='" . $valor . "'" . $clausula;
        if ($resultado = mysqli_query($conexion, $consulta)) {

            if (mysqli_num_rows($resultado) > 0) {

                $respuesta['repetido'] = true;
                mysqli_free_result($resultado);
            }
        } else {
            $error = 'Imposible realizar la consulta. Error número: ' . mysqli_errno($conexion) . ':' . mysqli_error($conexion);
            $respuesta['error'] = $error;
        }
        mysqli_close($conexion);
    }
    return $respuesta;
}


function insertarUsuario($usuario, $nombre, $clave, $telefono, $email)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta['error'] = 'Imposible conectar. Error número: ' . mysqli_connect_errno() . ':' . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, 'utf8');
        $consulta = "INSERT INTO usuarios(usuario,nombre,clave,telefono,email) VALUES ('" . $usuario . "','" . $nombre . "','" . $clave . "','" . $telefono . "','" . $email . "')";
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = 'Imposible realizar la consulta. Error número: ' . mysqli_errno($conexion) . ':' . mysqli_error($conexion);
            $respuesta['error'] = $error;
        } else
            $respuesta['ultm_id'] = mysqli_insert_id($conexion);
        mysqli_close($conexion);
    }
    return $respuesta;
}

function obtenerDatosUsuario($id_usuario)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");

        $consulta = "select * from usuarios where id=" . $id_usuario;

        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else {

            if (mysqli_num_rows($resultado) > 0)
                $respuesta["usuario"] = mysqli_fetch_assoc($resultado);
            else
                $respuesta["mensaje"] = "No existe el usuario en la BD";


            mysqli_free_result($resultado);
        }
        mysqli_close($conexion);
    }
    return $respuesta;
}

function obtenerUsuarios()
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "select * from usuarios where tipo<>'admin'";
        $resultado = mysqli_query($conexion, $consulta);

        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else {
            $res = array();
            while ($fila = mysqli_fetch_assoc($resultado))
                $res[] = $fila;

            $respuesta["usuarios"] = $res;
            mysqli_free_result($resultado);
        }
        mysqli_close($conexion);
    }
    return $respuesta;
}

function borrarUsuario($idUsuario)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion) {
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    } else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "delete from usuarios where id=" . $idUsuario;
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else {
            $respuesta["mensaje"] = "Se ha eliminado correctamente";
        }

        mysqli_close($conexion);
    }
    return $respuesta;
}

function actualizarUsuario($idUsuario, $nombre, $usuario, $clave, $telefono, $email)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        if ($clave == "") {
            $consulta = "update usuarios set nombre='" . $nombre . "', usuario='" . $usuario . "',telefono='" . $telefono . "', email='" . $email . "' where id=" . $idUsuario;
        } else {
            $consulta = "update usuarios set nombre='" . $nombre . "', usuario='" . $usuario . "', clave='" . $clave . "', telefono='" . $telefono . "', email='" . $email . "' where id=" . $idUsuario;
        }

        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "Actualizado";
        mysqli_close($conexion);
    }
    return $respuesta;
}


// ACCIONES ADMINISTRAR COCHES

function obtenerCoches()
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");

        $consulta = "select * from coches";

        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else {
            $res = array();
            while ($fila = mysqli_fetch_assoc($resultado))
                $res[] = $fila;

            $respuesta["coches"] = $res;
            mysqli_free_result($resultado);
        }
        mysqli_close($conexion);
    }
    return $respuesta;
}

function insertarCoche($marca, $modelo, $combustible, $precio, $anyofabricacion, $stock)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "INSERT INTO coches(marca,modelo,combustible,precio,anyofabricacion,stock) VALUES ('" . $marca . "','" . $modelo . "','" . $combustible . "','" . $precio . "','" . $anyofabricacion . "'," . $stock . ")";
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["ultm_id"] = mysqli_insert_id($conexion);

        mysqli_close($conexion);
    }
    return $respuesta;
}

function actualizarFotoCoche($valor_nuevo_foto, $id_coche)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "update coches set foto='" . $valor_nuevo_foto . "' where id=" . $id_coche;
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "ACTUALIZADO CON ÉXITO";
        mysqli_close($conexion);
    }
    return $respuesta;
}

function borrarCoche($id_coche)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "delete from coches where id=" . $id_coche;
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "Coche eliminado";
        mysqli_close($conexion);
    }
    return $respuesta;
}

function obtenerDatosCoche($id_coche)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");

        $consulta = "select * from coches where id=" . $id_coche;

        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else {

            if (mysqli_num_rows($resultado) > 0)
                $respuesta["coche"] = mysqli_fetch_assoc($resultado);
            else
                $respuesta["mensaje"] = "No existe el coche en la BD";


            mysqli_free_result($resultado);
        }
        mysqli_close($conexion);
    }
    return $respuesta;
}

function actualizarCoche($idCoche, $marca, $modelo, $combustible, $precio, $anyofabricacion, $stock)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");

        $consulta = "update coches set marca='" . $marca . "', modelo='" . $modelo . "', combustible='" . $combustible . "', precio='" . $precio . "', anyofabricacion='" . $anyofabricacion . "', stock='" . $stock . "' where id=" . $idCoche;


        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "Actualizado";
        mysqli_close($conexion);
    }
    return $respuesta;
}

function insertarPrueba($fecha,$hora,$fk_usuarios_id,$fk_empleados_id,$fk_coches_id){

    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "INSERT INTO pruebas(fecha,hora,fk_usuarios_id,fk_empleados_id,fk_coches_id) VALUES ('" . $fecha . "','" . $hora . "'," . $fk_usuarios_id . "," . $fk_empleados_id . "," . $fk_coches_id . ")";
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["ultm_id"] = mysqli_insert_id($conexion);

        mysqli_close($conexion);
    }
    return $respuesta;
}


function borrarCita($id_cita)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "delete from pruebas where id=" . $id_cita;
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "CITA ELIMINADA";
        mysqli_close($conexion);
    }
    return $respuesta;
}

function borrarCompra($id_coche)
{
    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "delete from ventas where id=" . $id_coche;
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["mensaje"] = "COMPRA ELIMINADA";
        mysqli_close($conexion);
    }
    return $respuesta;
}

function insertarVenta($fecha,$fk_usuarios_id,$fk_coches_id){

    @$conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    if (!$conexion)
        $respuesta["error"] = "Imposible conectar. Error número: " . mysqli_connect_errno() . ":" . mysqli_connect_error();
    else {
        mysqli_set_charset($conexion, "utf8");
        $consulta = "INSERT INTO ventas(fecha,fk_usuarios_id,fk_coches_id) VALUES ('" . $fecha . "'," . $fk_usuarios_id . "," . $fk_coches_id . ")";
        $resultado = mysqli_query($conexion, $consulta);
        if (!$resultado) {
            $error = "Imposible realizar la consulta. Error número: " . mysqli_errno($conexion) . ":" . mysqli_error($conexion);
            $respuesta["error"] = $error;
        } else
            $respuesta["ultm_id"] = mysqli_insert_id($conexion);

        mysqli_close($conexion);
    }
    return $respuesta;

}



?>