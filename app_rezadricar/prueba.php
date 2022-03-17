<?php

session_name("proyectorez");
session_start();
define("DIR_SERV", "http://localhost/proyectosphp/proyectofinal/servicios_rezadri");

function consumir_servicios_REST($url, $metodo, $datos = null)
{
    $llamada = curl_init();
    curl_setopt($llamada, CURLOPT_URL, $url);
    curl_setopt($llamada, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($llamada, CURLOPT_CUSTOMREQUEST, $metodo);

    if (isset($datos))
        curl_setopt($llamada, CURLOPT_POSTFIELDS, http_build_query($datos));

    $respuesta = curl_exec($llamada);
    curl_close($llamada);

    if (!$respuesta)
        die("Error consumiendo el servicio Web: " . $url);

    return json_decode($respuesta);
}

function repetido($tabla, $columna, $valor, $idUsuario = null)
{
    if (isset($idUsuario))
        $url = DIR_SERV . '/repetido/' . $tabla . '/' . $columna . '/' . urlencode($valor) . '/' . $idUsuario;
    else
        $url = DIR_SERV . '/repetido/' . $tabla . '/' . $columna . '/' . urlencode($valor);
    $resp = consumir_servicios_REST($url, 'GET');
    if (isset($resp->error))
        die($resp->error);
    else
        return @$resp->repetido;
}


function insertarPrueba($fecha,$hora,$fk_usuarios_id,$fk_empleados_id,$fk_coches_id)
{

    $datos["fecha"] = $fecha;
    $datos["hora"] = $hora;
    $datos["fk_usuarios_id"] = $fk_usuarios_id;
    $datos["fk_empleados_id"] = $fk_empleados_id;
    $datos["fk_coches_id"] = $fk_coches_id;

    $url = DIR_SERV . "/insertarPrueba";

    $resp = consumir_servicios_REST($url, "POST", $datos);
    if (isset($resp->error))
        die($resp->error);
    else
        return $resp->ultm_id;
}



if (isset($_POST["btnPrueba"])) {

    require "../servicios_rezadri/src/cfg_bd.php";
    $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);
    $sql = "SELECT * FROM usuarios where usuario='" . @$_SESSION["usuario"] . "'";
    $datos = mysqli_query($conexion, $sql);

    $idUsuario;
        
    while ($row = mysqli_fetch_array($datos)) { //Todos los campos de usuario
        $idUsuario = $row["id"]; //Obtenemos la id de la fila
    }

    $error_hora = $_POST["hora"] == "";
    $error_fecha = $_POST["fecha"] == "" || repetido("pruebas", "fecha", $_POST["fecha"]);
    $errores = $error_hora || $error_fecha;

    if (!$errores) {
        $fecha = $_POST["fecha"];
        $hora = $_POST["hora"];

        $fk_usuarios_id = $idUsuario;
        $fk_empleados_id = 1;
        $fk_coches_id = $_POST["btncoche"];

        $nueva_id = insertarPrueba($fecha, $hora, $fk_usuarios_id, $fk_empleados_id, $fk_coches_id);

        $mensaje = "PRUEBA AGREGADA CON ÉXITO";

        $_SESSION["accion"] = $mensaje;
        header("Location:prueba.php");
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="es">


<head>
    <title>Rezadri´s car</title>
    <meta charset="utf-8">
    <meta name="lang" content="es-ES" />
    <meta content="Aplicaciones Web" name="rezadricar" />
    <link rel="stylesheet" type="text/css" href="../css/estilorezadri.css">
    <meta name="robots" content="index, follow" />
    <meta name="viewport" content="width=500, initial-scale=1, maximum-scale=1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;1,300&display=swap" rel="stylesheet">
    <script src="../js/jquery-3.5.1.min.js"></script>
    <script src="../js/script-rezadricar.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.css">
    <script src="https://cdn.jsdelivr.net/bxslider/4.2.12/jquery.bxslider.min.js"></script>

</head>

<body>

    <header>

        <nav id="redes-sociales">
            <p>ES&#x25BE;</p>
            <a href="#"><img src="logo/youtube.png" alt="youtube" id="youtube" /></a>
            <a href="#"><img src="logo/twitter.png" alt="twitter" id="twitter" /></a>
            <a href="#"><img src="logo/instagram.png" alt="instagram" id="instagram" /></a>
        </nav>

        <nav id="menu-principal">
            <picture>
                <img src="logo/menublanco.png" alt="hamburguesa" id="hamburguesa">
            </picture>

            <picture id="logo">
                <img src="logo/logo_editado.png" alt="logo" id="imglog" onclick="location.href='index.php'">
            </picture>

            <div id="menu-opciones">
                <div id="cabeceramenu">
                    <picture id="mrlogomenu">
                        <img src="logo/logo_editado.png" alt="logo" id="mrmenu" />
                    </picture>
                    <picture>
                        <img src="logo/boton-cerrarblanco.png" alt="salir" id="salirmenu" />
                    </picture>
                </div>
                <div id="menu-contenido">

                    <nav id="opcionescontenido">
                        <ul id="menu-secciones">
                            <li><a onclick="location.href='index.php'">INICIO</a></li>
                            <li><a onclick="location.href='noticias.php'">NOTICIAS</a></li>
                            <li><a onclick="location.href='contacto.php'">CONTACTO</a></li>
                            <li><a onclick="location.href='catalogo.php'">CATÁLOGO</a></li>
                        </ul>
                    </nav>
                </div>


            </div>


            <nav id="secciondesplegado">
                <ul id="opciondesplegado">
                    <li><a onclick="location.href='index.php'">INICIO</a></li>
                    <li><a onclick="location.href='noticias.php'">NOTICIAS</a></li>
                    <li><a onclick="location.href='contacto.php'">CONTACTO</a></li>
                    <li><a onclick="location.href='catalogo.php'">CATÁLOGO</a></li>
                </ul>


            </nav>


            <picture id="login">
                <img src="logo/login.png" alt="user" id="user" onclick="location.href='login.php'">
            </picture>


        </nav>

    </header>
    <main>

        <nav id="enlacecontacto">
            <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Prueba</small></p>
        </nav>

        <picture id="probarfoto">
            <img src="images/pruebaconduccion.PNG" alt="prueba" id="prueba">
        </picture>

        <?php
        if (isset($_SESSION["accion"])) {

            echo "<p class='mensajeAccion'>" . $_SESSION["accion"] . "</p>";
            unset($_SESSION["accion"]);
        }




        ?>

        <div id="contenedorprueba">

            <?php

            $url = DIR_SERV . "/obtenerCoche/" . $_SESSION["idCoche"];
            $resp = consumir_servicios_REST($url, "GET");
            if (isset($resp->error))
                die($resp->error);

            if (isset($resp->coche)) {
                echo "<h2>" . $resp->coche->marca . " " . $resp->coche->modelo . "</h2>";
                echo "<img src='images/" . $resp->coche->foto . "'/>";

            ?>
                <form method="post" action="prueba.php">
                    <label for="fecha">DIA</label>
                    <input type="date" class="estiloinput" name="fecha" id="fecha" placeholder="DIA" value="<?php if (isset($_POST["btnPrueba"])) echo $_POST["fecha"]; ?>" />
                    <?php
                    if (isset($_POST["btnPrueba"]) && $error_fecha)
                        if ($_POST["fecha"] == "") {
                            echo "<p>CAMPO VACÍO*</p>";
                        } else {
                            echo "<p>EL DÍA DE PRUEBA ESTÁ OCUPADO*</p>";
                        }
                    ?>
    
                    <label for="hora">HORA</label>
                    <input type="time" class="estiloinput" name="hora" id="hora" placeholder="HORA" value="<?php if (isset($_POST["btnPrueba"])) echo $_POST["hora"]; ?>" />
                    <?php
                    if (isset($_POST["btnPrueba"]) && $error_hora)
                        if ($_POST["hora"] == "") {
                            echo "<p>CAMPO VACÍO*</p>";
                        }
                    ?>

                    <input type="hidden" name="btncoche" value="<?php echo $resp->coche->id; ?>" />
                    <input type="submit" class="boton-estilo" value="PROBAR" name="btnPrueba" id="btnPrueba">
                </form>

            <?php


            } else {
                echo "<h2>EL COCHE CON ID " . $_POST["btnProbarCoche"] . " YA NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS</h2>";
            }
            ?>



        </div>




    </main>
    <footer>
        <div id="infocontacto">
            <div id="horariotel">
                <p class="destacado">Horario:</p>
                <span>De Lunes 8:00 Am a Sabado 18:00 Pm</span>
                <p class="destacado" id="destacadotel">Teléfono:</p>
                <span>952 70 30 13</span>
            </div>
            <ul id="menu-contacto">
                <li><a onclick="location.href='informacion.php'">Aviso legal</a></li>
                <li><a onclick="location.href='informacion.php'">Política de privacidad</a></li>
                <li><a onclick="location.href='informacion.php'">Política de cookies</a></li>
                <li><a onclick="location.href='informacion.php'">Modelo semántico digital</a></li>
                <li><a onclick="location.href='contacto.php'">Contacto</a></li>
            </ul>

        </div>
        <div id="ubicacion">
            <picture>
                <img src="logo/ubicacion.png" alt="ubicacion" id="encuentranos" />
            </picture>
            <div id="info-car">
                <p>Rezadri’s Car</p>
                <span>Palacio de Congresos C/Barcelona,10</span>
            </div>

        </div>

        <small>© Copyright Rezadri’s Car.</small>

        <div id="logo">

            <picture>
                <img src="logo/logo_editado.png" alt="logo" id="logo-icono" />
            </picture>

        </div>
    </footer>
    <div id="volverarriba"><a href="#top">VOLVER ARRIBA</a></div>
</body>


</html>