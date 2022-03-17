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

function repetido($tabla, $columna, $valor, $referencia = null)
{
    if (isset($referencia))
        $url = DIR_SERV . '/repetido/' . $tabla . '/' . $columna . '/' . urlencode($valor) . '/' . $referencia;
    else
        $url = DIR_SERV . '/repetido/' . $tabla . '/' . $columna . '/' . urlencode($valor);
    $resp = consumir_servicios_REST($url, 'GET');
    if (isset($resp->error))
        die($resp->error);
    else
        return @$resp->repetido;
}

function insertarUsuario($usuario, $nombre, $clave, $telefono, $email)
{
    $datos["usuario"] = $usuario;
    $datos["clave"] = $clave;
    $datos["nombre"] = $nombre;
    $datos["telefono"] = $telefono;
    $datos["email"] = $email;

    $url = DIR_SERV . "/insertarUsuario";

    $resp = consumir_servicios_REST($url, "POST", $datos);

    if (isset($resp->error))
        die($resp->error);
    else
        return $resp->ultm_id;
}


if (isset($_POST["btnCrearCuenta"])) {

    $error_usuario = $_POST["usuario"] == "" || repetido("usuarios", "usuario", $_POST["usuario"]);
    $error_clave = $_POST["clave"] == "";
    $error_nombre = $_POST["nombre"] == "";

    $error_email = $_POST["email"] == "" || repetido("usuarios", "email", $_POST["email"]);
    $error_telefono = $_POST["telefono"] == "";

    $errores = $error_nombre || $error_usuario || $error_clave || $error_email || $error_telefono;


    if (!$errores) {
        $usuario = $_POST["usuario"];
        $clave = md5($_POST["clave"]);
        $nombre = $_POST["nombre"];

        $telefono = $_POST["telefono"];
        $email = $_POST["email"];


        $nueva_id = insertarUsuario($usuario, $nombre, $clave, $telefono, $email);

        $mensaje = "Usuario registrado con éxito";

        $_SESSION["usuario"] = $usuario;
        $_SESSION["clave"] = $clave;
        $_SESSION["ultimo_acceso"] = time();

        header("Location:login.php");
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
            <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Registro</small></p>
        </nav>
        <div id="login">
            <h1>REGISTRO</h1>

            <form action="registro.php" method="post">

                <label for="usuario"></label>
                <input class="estiloinput" type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["btnCrearCuenta"])) echo $_POST["usuario"]; ?>" placeholder="USUARIO" />

                <?php
                if (isset($_POST["btnCrearCuenta"]) && $error_usuario)
                    if ($_POST["usuario"] == "") {
                        echo "* Campo Vacío *";
                    } else {
                        echo "El usuario ya se encuentra registrado*";
                    }
                ?>

                <label for="nombre"></label>
                <input class="estiloinput" type="text" name="nombre" id="nombre" value="<?php if (isset($_POST["btnCrearCuenta"])) echo $_POST["nombre"]; ?>" placeholder="NOMBRE" />
                <?php
                if (isset($_POST["btnCrearCuenta"]) && $error_nombre) {
                    echo "Campo Vacío *";
                }

                ?>


                <label for="email"></label>
                <input type="email" class="estiloinput" name="email" id="email-login" placeholder="CORREO ELECTRONICO" pattern="^[-\w.]+@{1}[-a-z0-9]+[.]{1}[a-z]{2,5}$" required value="<?php if (isset($_POST["btnCrearCuenta"])) echo $_POST["email"]; ?>" />

                <?php
                if (isset($_POST["btnCrearCuenta"]) && $error_email)
                    if ($_POST["email"] == "") {
                        echo "Campo Vacío *";
                    } else {
                        echo "El email ya se encuentra registrado*";
                    }
                ?>

                <label for="clave"></label>
                <input type="password" class="estiloinput" name="clave" id="clave" placeholder="CONTRASEÑA" required />
                <?php
                if (isset($_POST["btnCrearCuenta"]) && $error_clave) {
                    echo "Campo Vacío*";
                }

                ?>

                <label for="telefono"></label>
                <input class="estiloinput" type="text" name="telefono" id="telefono" value="<?php if (isset($_POST["btnCrearCuenta"])) echo $_POST["telefono"]; ?>" placeholder="TELEFONO" />
                <?php
                if (isset($_POST["btnCrearCuenta"]) && $error_telefono) {
                    echo "Campo Vacío *";
                }

                ?>

                <nav id="aceptarterminos">
                    <input type="checkbox" id="acepto" name="acepto[]" />
                    <label for="acepto"> Acepto la política de privacidad</label>
                </nav>
                <input type="submit" class="boton-estilo" value="REGÍSTRATE" name="btnCrearCuenta" id="btnCrearCuenta" />


            </form>


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