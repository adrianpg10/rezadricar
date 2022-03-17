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



if (isset($_SESSION["administrador"])) {


    if (isset($_POST["btnBorrar"])) {
        $url = DIR_SERV . "/borrarUsuario/" . $_POST["btnBorrar"];
        $resp = consumir_servicios_REST($url, "DELETE");
        if (isset($resp->error))
            die($resp->error);

        $_SESSION["accion"] = "EL USUARIO CON ID: " . $_POST["btnBorrar"] . " SE HA BORRADO CON ÉXITO";
        header("Location:administrarUsuarios.php");
        exit;
    }

    if (isset($_POST["btnAgregarUser"])) {

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


            $mensaje = "USUARIO AGREGADO CON ÉXITO";
            $_SESSION["accion"] = $mensaje;

            header("Location:administrarUsuarios.php");
            exit;
        }
    }
    if (isset($_POST["btnContEditar"])) {

        $error_usuario = $_POST["usuario"] == "" || repetido("usuarios","usuario",$_POST["usuario"],$_POST["idUsuario"]);
        $error_nombre = $_POST["nombre"] == "";

        $error_email = $_POST["email"] == "" || repetido("usuarios","email",$_POST["email"],$_POST["idUsuario"]);
        $error_telefono = $_POST["telefono"] == "";

        $erroresEditar = $error_nombre || $error_usuario  || $error_email || $error_telefono;

        if(!$erroresEditar)
        {
        
            
            $datos["nombre"]=$_POST["nombre"];
            $datos["usuario"]=$_POST["usuario"];

            if($_POST["clave"]=="")
                $datos["clave"]=$_POST["clave"];  
            else
                $datos["clave"]=md5($_POST["clave"]);
            
            $datos["email"]=$_POST["email"];
            $datos["telefono"]=$_POST["telefono"];

            $url=DIR_SERV."/actualizarUsuario/".$_POST["idUsuario"];
            $resp=consumir_servicios_REST($url,"PUT",$datos);
            if(isset($resp->error))
                die($resp->error);

            
            $mensaje="USUARIO EDITADO CON ÉXITO";
    
            $_SESSION["accion"]=$mensaje;
            header("Location:administrarUsuarios.php");
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
                <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Administrar Usuarios</small></p>
            </nav>
            <?php
            if (isset($_SESSION["accion"])) {

                echo "<p class='mensajeAccion'>" . $_SESSION["accion"] . "</p>";
                unset($_SESSION["accion"]);
            }
            ?>
            <h1>LISTADO DE LOS USUARIOS</h1>

            <form action="administrarUsuarios.php" method="post" id="contenedorAgregar">
                <h2>AGREGAR USUARIO</h2>
                <button name="btnNuevoUsuario" class='botonAgregarUser'>+</button>
            </form>

            <?php

            //----AGREGAMOS USUARIO----
            if (isset($_POST["btnNuevoUsuario"]) || isset($_POST["btnAgregarUser"])) {
            ?>
                <form action="administrarUsuarios.php" method="post" id="agregarUsuarios">

                    <label for="usuario"></label>
                    <input class="estiloinput" type="text" name="usuario" id="usuario" value="<?php if (isset($_POST["btnAgregarUser"])) echo $_POST["usuario"]; ?>" placeholder="USUARIO" />

                    <?php
                    if (isset($_POST["btnAgregarUser"]) && $error_usuario)
                        if ($_POST["usuario"] == "") {
                            echo "* Campo Vacío *";
                        } else {
                            echo "El usuario ya se encuentra registrado*";
                        }
                    ?>

                    <label for="nombre"></label>
                    <input class="estiloinput" type="text" name="nombre" id="nombre" value="<?php if (isset($_POST["btnAgregarUser"])) echo $_POST["nombre"]; ?>" placeholder="NOMBRE" />
                    <?php
                    if (isset($_POST["btnAgregarUser"]) && $error_nombre) {
                        echo "Campo Vacío *";
                    }

                    ?>


                    <label for="email"></label>
                    <input type="email" class="estiloinput" name="email" id="email-login" placeholder="CORREO ELECTRONICO" pattern="^[-\w.]+@{1}[-a-z0-9]+[.]{1}[a-z]{2,5}$" value="<?php if (isset($_POST["btnAgregarUser"])) echo $_POST["email"]; ?>" />

                    <?php
                    if (isset($_POST["btnAgregarUser"]) && $error_email)
                        if ($_POST["email"] == "") {
                            echo "Campo Vacío *";
                        } else {
                            echo "El email ya se encuentra registrado*";
                        }
                    ?>

                    <label for="clave"></label>
                    <input type="password" class="estiloinput" name="clave" id="clave" placeholder="CONTRASEÑA" />
                    <?php
                    if (isset($_POST["btnAgregarUser"]) && $error_clave) {
                        echo "Campo Vacío*";
                    }

                    ?>

                    <label for="telefono"></label>
                    <input class="estiloinput" type="text" name="telefono" id="telefono" value="<?php if (isset($_POST["btnAgregarUser"])) echo $_POST["telefono"]; ?>" placeholder="TELEFONO" />
                    <?php
                    if (isset($_POST["btnAgregarUser"]) && $error_telefono) {
                        echo "Campo Vacío *";
                    }

                    ?>

                    <nav id="aceptarterminos">
                        <input type="checkbox" id="acepto" name="acepto[]" />
                        <label for="acepto"> Acepto la política de privacidad</label>
                    </nav>
                    <div id="contenedorBotones">
                        <input type="submit" value="AGREGAR USUARIO" name="btnAgregarUser" id="btnAgregarUser" />
                        <input type="submit" value="OCULTAR" name="btnOcultar" />
                    </div>
                </form>





                <?php

            }


            //----EDITAMOS USUARIO----

            if (isset($_POST["btnEditar"]) || (isset($_POST["btnContEditar"]) && $erroresEditar)) {

                if (isset($_POST["btnEditar"])) {

                    $idUsuario = $_POST["btnEditar"];

                    $url = DIR_SERV . "/obtenerUsuario/" . $idUsuario;
                    $resp = consumir_servicios_REST($url, "GET");
                    if (isset($resp->error)) {
                        die($resp->error);
                    }

                    if (isset($resp->usuario)) {
                        $usuario = $resp->usuario->usuario;
                        $nombre = $resp->usuario->nombre;
                        $telefono = $resp->usuario->telefono;
                        $email = $resp->usuario->email;
                    } else {
                        $error_concurrencia = true;
                    }
                } else {

                    $idUsuario = $_POST["idUsuario"];
                    $usuario = $_POST["usuario"];
                    $nombre = $_POST["nombre"];
                    $telefono = $_POST["telefono"];
                    $email = $_POST["email"];
                }

                if (isset($error_concurrencia)) {

                    echo "<h2>EL USUARIO ID: " . $idUsuario . " NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS.</h2>";
                } else {

                ?>
                    <h2>EDITANDO EL USUARIO CON ID <?php echo $idUsuario; ?></h2>
                    <form action="administrarUsuarios.php" method="post" id="agregarUsuarios">

                        <label for="usuario"></label>
                        <input class="estiloinput" type="text" name="usuario" id="usuario" value="<?php echo $usuario; ?>" placeholder="USUARIO" />

                        <?php
                        if (isset($_POST["btnContEditar"]) && $error_usuario)
                            if ($_POST["usuario"] == "") {
                                echo "* Campo Vacío *";
                            } else {
                                echo "Usuario repetido*";
                            }
                        ?>

                        <label for="nombre"></label>
                        <input class="estiloinput" type="text" name="nombre" id="nombre" value="<?php echo $nombre; ?>" placeholder="NOMBRE" />
                        <?php
                        if (isset($_POST["btnContEditar"]) && $error_nombre) {
                            echo "Campo Vacío *";
                        }

                        ?>


                        <label for="email"></label>
                        <input type="email" class="estiloinput" name="email" id="email-login" placeholder="CORREO ELECTRONICO" pattern="^[-\w.]+@{1}[-a-z0-9]+[.]{1}[a-z]{2,5}$" value="<?php echo $email; ?>" />

                        <?php
                        if (isset($_POST["btnContEditar"]) && $error_email)
                            if ($_POST["email"] == "") {
                                echo "Campo Vacío *";
                            } else {
                                echo "Email repetido*";
                            }
                        ?>

                        <label for="telefono"></label>
                        <input class="estiloinput" type="text" name="telefono" id="telefono" value="<?php echo $telefono; ?>" placeholder="TELEFONO" />
                        <?php
                        if (isset($_POST["btnContEditar"]) && $error_telefono) {
                            echo "Campo Vacío *";
                        }

                        ?>
                        
                        <div id="contenedorBotones">

                            <input type="submit" value="EDITAR USUARIO" name="btnContEditar" />
                            <input type="submit" value="OCULTAR" name="btnOcultar" />
                            <input type="hidden" name="idUsuario" value="<?php echo $idUsuario; ?>" />
                        </div>
                    </form>



            <?php

                }
            }



            ?>

            <table id="listadoUsuarios">
                <tr>
                    <th>ID</th>
                    <th>USUARIO</th>
                    <th>NOMBRE</th>
                    <th>TELÉFONO</th>
                    <th>EMAIL</th>
                    <th>ACCIONES</th>
                </tr>

                <?php

                $url = DIR_SERV . "/obtenerUsuarios";
                $respuesta = consumir_servicios_REST($url, "GET");
                if (isset($respuesta->error)) {
                    die($respuesta->error);
                }

                foreach ($respuesta->usuarios as $fila) {
                    echo "<tr>";
                    echo "<td>" . $fila->id . "</td>";
                    echo "<td>" . $fila->usuario . "</td>";
                    echo "<td>" . $fila->nombre . "</td>";
                    echo "<td>" . $fila->telefono . "</td>";
                    echo "<td>" . $fila->email . "</td>";

                    echo "<td><form class='enlinea' action='administrarUsuarios.php' method='post'>";
                    echo "<button onclick='return confirm(\"Estas seguro de que quieres eliminar este Usuario con id " . $fila->id . "? \");'type='submit' class='botonAccion' name='btnBorrar' value='" . $fila->id . "' >BORRAR</button></form> - ";
                    echo "<form action='administrarUsuarios.php' method='post' class='enlinea' ><button type='submit' class='botonAccion' name='btnEditar' value='" . $fila->id . "'>EDITAR </button></form></td>";
                    echo "</tr>";
                }


                ?>
            </table>




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

<?php

} else {
    $_SESSION["restringida"] = true;
    header("location:index.php");
    exit();
}

?>