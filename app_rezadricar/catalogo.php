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
            <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Catálogo</small></p>
        </nav>
        <h1>NUESTRO CATÁLOGO</h1>
        <div id="tituloFiltro">
            <h2>FILTRAR</h2>
            <picture>
                <img src="images/simbolo-de-herramienta-llena-de-filtro.png" alt="filtro" id="filtro" />
            </picture>
        </div>
        <div id="filtro">

            <form method="post" action="catalogo.php">

                <label for="marca">MARCA</label>
                <input type="text" class="estiloinput" name="marca" id="marca" placeholder="MARCA" value="<?php if (isset($_POST["btnFiltro"])) echo $_POST["marca"]; ?>" />
                <label for="modelo">MODELO</label>
                <input type="text" class="estiloinput" name="modelo" id="modelo" placeholder="MODELO" value="<?php if (isset($_POST["btnFiltro"])) echo $_POST["modelo"]; ?>"  />
                <label for="preciomax">PRECIO MÁXIMO</label>
                <input type="number" class="estiloinput" name="preciomax" id="preciomax" placeholder="PRECIO MÁXIMO" value="<?php if (isset($_POST["btnFiltro"])) echo $_POST["preciomax"]; ?>" />
                <label for="combustible">COMBUSTIBLE</label>
                <input type="text" class="estiloinput" name="combustible" id="combustible" placeholder="COMBUSTIBLE" value="<?php if (isset($_POST["btnFiltro"])) echo $_POST["combustible"]; ?>" />
                <label for="anyofabricacion">AÑO FABRICACIÓN</label>
                <input type="text" class="estiloinput" name="anyofabricacion" id="anyofabricacion" placeholder="AÑO FABRICACIÓN" value="<?php if (isset($_POST["btnFiltro"])) echo $_POST["anyofabricacion"]; ?>" />

                <input type="submit" class="boton-estilo" value="FILTRAR" name="btnFiltro" id="btnFiltro">

                </form>

        </div>

        <?php




        if (isset($_POST["btnFiltro"]) && (isset($_POST["marca"]) || isset($_POST["modelo"]) || isset($_POST["preciomax"]) || isset($_POST["combustible"])  || isset($_POST["anyofabricacion"]))) {


            require "../servicios_rezadri/src/cfg_bd.php";
            $conexion = mysqli_connect(SERVIDOR_BD, USUARIO_BD, CLAVE_BD, NOMBRE_BD);

            /*-----------------FILTROS------------*/

            //FILTRO COMPLETO (TODOS LOS CAMPOS)

            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }

            //FILTROS CUÁDRUPLE COMBINACIÓN

            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["preciomax"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["modelo"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["marca"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }

            //FILTROS TRIPLE COMBINACIÓN

            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["combustible"])) && (empty($_POST["preciomax"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["preciomax"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (empty($_POST["modelo"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["preciomax"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and precio<='" . $_POST["preciomax"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["modelo"])) && (empty($_POST["preciomax"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (empty($_POST["anyofabricacion"])) && (empty($_POST["marca"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["combustible"])) && (empty($_POST["marca"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["preciomax"])) && (empty($_POST["marca"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["modelo"])) && (empty($_POST["marca"]))) {
                $consulta = "select * from coches where precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }

            //FILTROS DOBLE COMBINACIÓN

            if ((isset($_POST["marca"])) && (isset($_POST["modelo"])) && (empty($_POST["preciomax"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and modelo='" . $_POST["modelo"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["preciomax"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and precio<='" . $_POST["preciomax"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["combustible"])) && (empty($_POST["preciomax"])) && (empty($_POST["modelo"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["marca"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["preciomax"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["preciomax"])) && (empty($_POST["marca"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and precio<='" . $_POST["preciomax"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["combustible"])) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["modelo"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["preciomax"])) && (isset($_POST["combustible"])) && (empty($_POST["marca"])) && (empty($_POST["modelo"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where precio<='" . $_POST["preciomax"] . "' and combustible='" . $_POST["combustible"] . "'";
            }
            if ((isset($_POST["preciomax"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["marca"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where precio<='" . $_POST["preciomax"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }
            if ((isset($_POST["combustible"])) && (isset($_POST["anyofabricacion"])) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["modelo"]))) {
                $consulta = "select * from coches where combustible='" . $_POST["combustible"] . "' and anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }

            //FILTRO INDIVIDUAL

            //MARCA
            if (isset($_POST["marca"]) && (empty($_POST["modelo"])) && (empty($_POST["preciomax"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where marca='" . $_POST["marca"] . "'";
            }

            //MODELO
            if (isset($_POST["modelo"]) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where modelo='" . $_POST["modelo"] . "'";
            }

            //PRECIO MÁXIMO
            if (isset($_POST["preciomax"]) && (empty($_POST["marca"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where precio<='" . $_POST["preciomax"] . "'";
            }

            //COMBUSTIBLE
            if (isset($_POST["combustible"]) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["modelo"])) && (empty($_POST["anyofabricacion"]))) {
                $consulta = "select * from coches where combustible='" . $_POST["combustible"] . "'";
            }

            //AÑO FABRICACIÓN
            if (isset($_POST["anyofabricacion"]) && (empty($_POST["marca"])) && (empty($_POST["preciomax"])) && (empty($_POST["modelo"])) && (empty($_POST["combustible"]))) {
                $consulta = "select * from coches where anyofabricacion='" . $_POST["anyofabricacion"] . "'";
            }

            /*-----------END FILTROS-------------*/
            $resultado = mysqli_query($conexion, $consulta);
            if ($resultado) {
        ?>
                <div id="productos">

                    <?php
                    while ($fila = mysqli_fetch_assoc($resultado)) {
                        
                        echo "<div class='catalogo'>";
                        echo "<h3>" . $fila["marca"] . " " . $fila["modelo"] . "</h3>";
                        echo "<img value='" . $fila["id"] . "' src='images/" . $fila["foto"] . "'/>";
                        echo "<form id='contenedorboton' action='detallesCoche.php' method='post'>";
                        echo "<button class='sabermas' value='" . $fila["id"] . "' name='btnDetalleCocheCatalogo' >SABER MÁS</button>";
                        echo "</form>";
                        echo "</div>";
                    }
                    ?>

                </div>
            <?php
            } else {
                echo "No se ha encontrado ningún resultado con los criterios de búsqueda";
            }
        } else {
            $url = DIR_SERV . "/obtenerCoches";
            $resp = consumir_servicios_REST($url, "GET");

            if (isset($resp->error)) {
                die($resp->error);
            }
            ?>
            <div id="productos">
                <?php
                foreach ($resp->coches as $fila) {
                    
                    echo "<div class='catalogo'>";
                    echo "<h3>" . $fila->marca . " " . $fila->modelo . "</h3>";
                    echo "<img value='" . $fila->id . "' src='images/" . $fila->foto . "'/>";
                    echo "<form id='contenedorboton' action='detallesCoche.php' method='post'>";
                    echo "<button value='" . $fila->id . "' name='btnDetalleCocheCatalogo' class='sabermas' >SABER MÁS</button>";
                    $_SESSION["id_coche_cat"] = $fila->id;
                    echo "</form>";
                    echo "</div>";
                }
                ?>
            </div>
        <?php
        }

        ?>


       

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