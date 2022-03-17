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


function insertarCoche($marca, $modelo, $combustible, $precio, $anyofabricacion, $stock)
{

    $datos["marca"] = $marca;
    $datos["modelo"] = $modelo;
    $datos["combustible"] = $combustible;
    $datos["precio"] = $precio;
    $datos["anyofabricacion"] = $anyofabricacion;
    $datos["stock"] = $stock;

    $url = DIR_SERV . "/insertarCoche";

    $resp = consumir_servicios_REST($url, "POST", $datos);

    if (isset($resp->error))
        die($resp->error);
    else
        return $resp->ultm_id;
}



function actualizarFotoCoche($valor_nuevo_foto, $id_coche)
{
    $url = DIR_SERV . "/actualizarFoto/" . $id_coche;
    $datos["nombre_nuevo"] = $valor_nuevo_foto;

    $resp = consumir_servicios_REST($url, "PUT", $datos);
    if (isset($resp->error))
        die($resp->error);
}



if (isset($_SESSION["administrador"])) {

    if (isset($_POST["btncontBorrarFoto"])) {

        actualizarFotoCoche("no_image.jpg", $_POST["btncontBorrarFoto"]);
        unlink("images/".$_POST["foto_ant"]);
        $_SESSION["accion"] = "FOTO BORRADA CON ÉXITO";
        $_SESSION["borrado_foto"] = $_POST["btncontBorrarFoto"];
        header("Location:administrarCoches.php");
        exit;
    }

    if (isset($_POST["btnContEditar"])) {
        $error_marca = $_POST["marca"] == "";
        $error_modelo = $_POST["modelo"] == "" || repetido("coches", "modelo", $_POST["modelo"], $_POST["id_coche"]);
        $error_combustible = $_POST["combustible"] == "";
        $error_precio = $_POST["precio"] == "";
        $error_anyofabricacion = $_POST["anyofabricacion"] == "";
        $error_stock = $_POST["stock"] == "";

        $error_imagen = $_FILES["foto"]["name"] != "" && ($_FILES["foto"]["error"] || !getimagesize($_FILES["foto"]["tmp_name"]) || $_FILES["foto"]["size"] > 500000);


        $errores_editar = $error_marca || $error_combustible || $error_precio || $error_anyofabricacion || $error_stock|| $error_modelo|| $error_imagen;

        if (!$errores_editar) {

            $datos["marca"] = $_POST["marca"];
            $datos["modelo"] = $_POST["modelo"];
            $datos["combustible"] = $_POST["combustible"];
            $datos["precio"] = $_POST["precio"];
            $datos["anyofabricacion"] = $_POST["anyofabricacion"];
            $datos["stock"] = $_POST["stock"];

            $url = DIR_SERV."/actualizarCoche/" . $_POST["id_coche"];
            $resp = consumir_servicios_REST($url, "PUT", $datos);
            if (isset($resp->error))
                die($resp->error);


            $mensaje = "COCHE EDITADO CON ÉXITO";

            if ($_FILES["foto"]["name"] != "") {

                $array1 = explode(".",$_FILES["foto"]["name"]);
                $extension = end($array1);
                @$mover = move_uploaded_file($_FILES["foto"]["tmp_name"], "images/img_".$_POST["id_coche"].".".$extension);
                if ($mover) {
                    if ($_POST["foto_ant"]!="img_".$_POST["id_coche"].".".$extension) {

                        actualizarFotoCoche("img_".$_POST["id_coche"].".".$extension, $_POST["id_coche"]);
                        if ($_POST["foto_ant"] != "no_image.jpg"){
                            unlink("images/" . $_POST["foto_ant"]);
                        }
                            
                    }
                } else {
                    $mensaje = "COCHE EDITADO CON ÉXITO, SIN FOTO POR PROBLEMA DE CONEXIÓN ";
                }
            }
            $_SESSION["accion"] = $mensaje;
            header("Location:administrarCoches.php");
            exit;
        }
    }





    if (isset($_POST["btnAgregarCoche"])) {
        $error_marca = $_POST["marca"] == "";
        $error_modelo = $_POST["modelo"] == "" || repetido("coches", "modelo", $_POST["modelo"]);
        $error_combustible = $_POST["combustible"] == "";
        $error_precio = $_POST["precio"] == "";
        $error_anyofabricacion = $_POST["anyofabricacion"] == "";
        $error_stock = $_POST["stock"] == "";

        $error_imagen = $_FILES["foto"]["name"] != "" && ($_FILES["foto"]["error"] || !getimagesize($_FILES["foto"]["tmp_name"]) || $_FILES["foto"]["size"] > 500000);


        $errores = $error_marca || $error_modelo || $error_combustible || $error_precio || $error_anyofabricacion || $error_stock || $error_foto;

        if (!$errores) {
            $marca = $_POST["marca"];
            $modelo = $_POST["modelo"];
            $combustible = $_POST["combustible"];
            $precio = $_POST["precio"];
            $anyofabricacion = $_POST["anyofabricacion"];
            $stock = $_POST["stock"];


            $nueva_id = insertarCoche($marca, $modelo, $combustible, $precio, $anyofabricacion, $stock);

            $mensaje = "COCHE AGREGADO CON ÉXITO";

            if ($_FILES["foto"]["name"] != "") //si se ha seleccionado una foto
            {

                $nombre_unico = "img_" . $nueva_id;
                $array_nombre = explode(".", $_FILES["foto"]["name"]);
                $extension = end($array_nombre);

                @$var = move_uploaded_file($_FILES["foto"]["tmp_name"], "images/" . $nombre_unico . "." . $extension);
                if ($var) {
                    actualizarFotoCoche($nombre_unico . "." . $extension, $nueva_id);
                } else {
                    $mensaje = "COCHE AGREGADO CON ÉXITO. PERO CON UNA FOTO POR DEFECTO POR UN PROBLEMA DEL SERVIDOR";
                }
            }


            $_SESSION["accion"] = $mensaje;
            header("Location:administrarCoches.php");
            exit;
        }
    }

    if (isset($_POST["btnBorrar"])) {
        $url = DIR_SERV . "/borrarCoche/" . $_POST["btnBorrar"];
        $resp = consumir_servicios_REST($url, "DELETE");
        if (isset($resp->error))
            die($resp->error);

        if ($_POST["foto"] != "no_image.jpg")
            unlink("images/" . $_POST["foto"]);

        $_SESSION["accion"] = "EL COCHE CON ID: " . $_POST["btnBorrar"] . " SE HA BORRADO CON ÉXITO";
        header("Location:administrarCoches.php");
        exit;
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
                    <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Administrar Coches</small></p>
                </nav>

                <?php
                if (isset($_SESSION["accion"])) {

                    echo "<p class='mensajeAccion'>" . $_SESSION["accion"] . "</p>";
                    unset($_SESSION["accion"]);
                }



                ?>
                <h1>LISTADO DE LOS COCHES DISPONIBLES</h1>

                
                <form action="administrarCoches.php" method="post" id="contenedorAgregar">
                    <h2>AGREGAR COCHE</h2>
                    <button name="btnNuevoCoche" class='botonAgregarUser'>+</button>
                </form>

                <?php
                if (isset($_POST["btnDetalleCoche"])) {

                    $url = DIR_SERV . "/obtenerCoche/" . $_POST['btnDetalleCoche'];
                    $resp = consumir_servicios_REST($url, "GET");
                    if (isset($resp->error))
                        die($resp->error);

                    if (isset($resp->coche)) {
                        echo "<h2>DETALLES COCHE CON ID: " . $_POST["btnDetalleCoche"] . "</h2>";
                        echo "<div id='detalleCoche'>";
                        echo "<p><strong>MARCA: </strong>" . $resp->coche->marca . "</p>";
                        echo "<p><strong>MODELO: </strong>" . $resp->coche->modelo . "</p>";
                        echo "<p><strong>COMBUSTIBLE: </strong>" . $resp->coche->combustible . "</p>";
                        echo "<p><strong>PRECIO: </strong>" . $resp->coche->precio . "€</p>";
                        echo "<p><strong>AÑO FABRICACIÓN: </strong>" . $resp->coche->anyofabricacion . "</p>";
                        echo "<p><strong>STOCK: </strong>" . $resp->coche->stock . "</p>";
                        echo "<img src='images/" . $resp->coche->foto . "'/>";
                    } else
                        echo "<h2>EL COCHE CON ID " . $_POST["btnDetalleCoche"] . " YA NO SE ENCUENTRA REGISTRADO EN LA BASE DE DATOS</h2>";
                ?>

                    <form method="post" action="administrarCoches.php">
                        <input id="btnocultarCoche" type="submit" value="OCULTAR" name="btnOcultar" />
                    </form>
                    </div>

                <?php
                }

                if (isset($_POST["btnEditar"]) || isset($_SESSION["borrado_foto"]) || (isset($_POST["btnContEditar"]) && $errores_editar) || isset($_POST["btnBorrarFoto"]) || isset($_POST["btnNocontBorrarFoto"])) {

                    if (isset($_POST["btnEditar"]) || isset($_SESSION["borrado_foto"])) {
                        if (isset($_POST["btnEditar"]))
                            $id_coche = $_POST["btnEditar"];
                        else {
                            $id_coche = $_SESSION["borrado_foto"];
                            unset($_SESSION["borrado_foto"]);
                        }

                        $url = DIR_SERV . "/obtenerCoche/" . $id_coche;
                        $resp = consumir_servicios_REST($url, "GET");
                        if (isset($resp->error))
                            die($resp->error);
            
            
                        if (isset($resp->coche)) {

                            $marca = $resp->coche->marca;
                            $modelo = $resp->coche->modelo;
                            $combustible = $resp->coche->combustible;
                            $precio = $resp->coche->precio;
                            $anyofabricacion = $resp->coche->anyofabricacion;
                            $foto = $resp->coche->foto;
                            $stock = $resp->coche->stock;
                        } else{
                            $error_concurrencia = true;
                        }
                            
                    } else {
                        $id_coche = $_POST["id_coche"];
                        $marca = $_POST["marca"];
                        $modelo = $_POST["modelo"];
                        $combustible = $_POST["combustible"];
                        $precio = $_POST["precio"];
                        $anyofabricacion = $_POST["anyofabricacion"];
                        $stock = $_POST["stock"];
                        $foto = $_POST["foto_ant"];
                    }
                    if (isset($error_concurrencia)) {
                        echo "<h2>EL COCHE CON ID " . $id_coche . " NO SE ENCUENTRA REGISTRADO EN LA BD</h2>";
                    } else {
            ?>
                        <h2>EDITANDO COCHE CON ID <?php echo $id_coche; ?></h2>
                        <form method="post" action="administrarCoches.php" enctype="multipart/form-data" id="agregarUsuarios">
        
                                <label for="marca"></label>
                                <input class="estiloinput" type="text" name="marca" id="marca" value="<?php echo $marca; ?>" placeholder="MARCA" />
            
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_marca)
                                    if ($_POST["marca"] == "") {
                                        echo "* Campo Vacío *";
                                    }
                                ?>
            
                                <label for="modelo"></label>
                                <input class="estiloinput" type="text" name="modelo" id="modelo" value="<?php echo $modelo; ?>" placeholder="MODELO" />
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_modelo)
                                    if ($_POST["modelo"] == "") {
                                        echo "Campo Vacío *";
                                    } else {
                                        echo "El modelo ya se encuentra registrado*";
                                    }
                                ?>
                                
                                    <img class="imageneditar" src="images/<?php echo $foto;?>" />
                                    <?php
                                    if ($foto != "no_image.jpg" && !isset($_POST["btnBorrarFoto"]))
                                        echo '<button class="estiloeliminar" type="submit" name="btnBorrarFoto" value="' . $id_coche . '">BORRAR</button>';
            
                                    if (isset($_POST["btnBorrarFoto"])) {
                                        echo "<p class='estilopregunta'>¿ESTÁS SEGURO DE QUE QUIERES ELIMINAR LA FOTO?</p>";
                                        echo "<div id='contenidoconfirmar'>";
                                        echo '<button class="confirmarborrado" type="submit" name="btncontBorrarFoto" value="' . $id_coche . '">SI</button>';
                                        echo '<button class="confirmarborrado" type="submit" name="btnNocontBorrarFoto" value="' . $id_coche . '">NO</button>';
                                        echo "</div>";
                                    }
                                    ?>
                                
                                <div id="gestionarFoto">
                                    <label for="foto">INCLUIR IMAGEN (Archivo de tipo imagen Máx. 500KB) </label>
                                    <input type="file" name="foto" id="foto" accept="image/*" />
                                </div>
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_imagen) {
                                    if ($_FILES["foto"]["error"]) {
                                        echo "No se ha podido subir el archivo seleccionado. Vuelva a intentarlo";
                                    } elseif (!getimagesize($_FILES["foto"]["tmp_name"])) {
                                        echo "No has seleccionado un archivo de tipo imagen *";
                                    } else {
                                        echo "El archivo imagen seleccionado supera los 500kB *";
                                    }
                                }
                                ?>
            
            
                                <label for="combustible"></label>
                                <input class="estiloinput" type="text" name="combustible" id="combustible" value="<?php echo $combustible; ?>" placeholder="COMBUSTIBLE" />
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_combustible) {
                                    echo "Campo Vacío *";
                                }
            
                                ?>
            
                                <label for="precio"></label>
                                <input class="estiloinput" type="number" name="precio" id="precio" value="<?php echo $precio; ?>" placeholder="PRECIO" />
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_precio) {
                                    echo "Campo Vacío *";
                                }
            
                                ?>
            
                                <label for="anyofabricacion"></label>
                                <input class="estiloinput" type="number" name="anyofabricacion" id="anyofabricacion" value="<?php echo $anyofabricacion; ?>" placeholder="AÑO FABRICACIÓN" />
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_anyofabricacion) {
                                    echo "Campo Vacío *";
                                }
            
                                ?>
            
                                <label for="stock"></label>
                                <input class="estiloinput" type="number" name="stock" id="stock" value="<?php echo $stock; ?>" placeholder="STOCK" />
                                <?php
                                if (isset($_POST["btnContEditar"]) && $error_stock) {
                                    echo "Campo Vacío *";
                                }
            
                                ?>
            
                                <div id="contenedorBotones">
                                    <input type="hidden" name="id_coche" value="<?php echo $id_coche; ?>" />
                                    <input type="hidden" name="foto_ant" value="<?php echo $foto; ?>" />
                                    <input type="submit" value="EDITAR" name="btnContEditar" />
                                    <input type="submit" value="OCULTAR" name="btnVolver" />
                                </div>
            
                            </form>
            
            
                    <?php
            
            
                    }
                }


                //----AGREGAMOS COCHE----
                if (isset($_POST["btnNuevoCoche"]) || isset($_POST["btnAgregarCoche"])) {
                ?>
                    <form action="administrarCoches.php" method="post" id="agregarUsuarios" enctype="multipart/form-data">

                        <label for="marca"></label>
                        <input class="estiloinput" type="text" name="marca" id="marca" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["marca"]; ?>" placeholder="MARCA" />

                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_marca)
                            if ($_POST["marca"] == "") {
                                echo "* Campo Vacío *";
                            }
                        ?>

                        <label for="modelo"></label>
                        <input class="estiloinput" type="text" name="modelo" id="modelo" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["modelo"]; ?>" placeholder="MODELO" />
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_modelo)
                            if ($_POST["modelo"] == "") {
                                echo "Campo Vacío *";
                            } else {
                                echo "El modelo ya se encuentra registrado*";
                            }
                        ?>
                        <div id="gestionarFoto">
                            <label for="foto">INCLUIR IMAGEN (Archivo de tipo imagen Máx. 500KB) </label>
                            <input type="file" name="foto" id="foto" accept="image/*" />
                        </div>
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_imagen) {
                            if ($_FILES["foto"]["error"]) {
                                echo "No se ha podido subir el archivo seleccionado. Vuelva a intentarlo";
                            } elseif (!getimagesize($_FILES["foto"]["tmp_name"])) {
                                echo "No has seleccionado un archivo de tipo imagen *";
                            } else {
                                echo "El archivo imagen seleccionado supera los 500kB *";
                            }
                        }
                        ?>


                        <label for="combustible"></label>
                        <input class="estiloinput" type="text" name="combustible" id="combustible" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["combustible"]; ?>" placeholder="COMBUSTIBLE" />
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_combustible) {
                            echo "Campo Vacío *";
                        }

                        ?>

                        <label for="precio"></label>
                        <input class="estiloinput" type="number" name="precio" id="precio" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["precio"]; ?>" placeholder="PRECIO" />
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_precio) {
                            echo "Campo Vacío *";
                        }

                        ?>

                        <label for="anyofabricacion"></label>
                        <input class="estiloinput" type="number" name="anyofabricacion" id="anyofabricacion" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["anyofabricacion"]; ?>" placeholder="AÑO FABRICACIÓN" />
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_anyofabricacion) {
                            echo "Campo Vacío *";
                        }

                        ?>

                        <label for="stock"></label>
                        <input class="estiloinput" type="number" name="stock" id="stock" value="<?php if (isset($_POST["btnAgregarCoche"])) echo $_POST["stock"]; ?>" placeholder="STOCK" />
                        <?php
                        if (isset($_POST["btnAgregarCoche"]) && $error_stock) {
                            echo "Campo Vacío *";
                        }

                        ?>



                        <div id="contenedorBotones">
                            <input type="submit" value="AGREGAR COCHE" name="btnAgregarCoche" id="btnAgregarUser" />
                            <input type="submit" value="OCULTAR" name="btnOcultar" />
                        </div>
                    </form>





                <?php

                }




                
                ?>

                <table id="listadoUsuarios">
                    <tr>
                        <th>ID</th>
                        <th>IMAGEN</th>
                        <th>MARCA</th>
                        <th>ACCIONES</th>
                    </tr>

                    <?php

                    $url = DIR_SERV . "/obtenerCoches";
                    $respuesta = consumir_servicios_REST($url, "GET");
                    if (isset($respuesta->error)) {
                        die($respuesta->error);
                    }

                    foreach ($respuesta->coches as $fila) {
                        echo "<tr>";
                        echo "<td>" . $fila->id . "</td>";
                        echo "<td class='contenedorimg'><img src='images/" . $fila->foto . "'/></td>";
                        echo "<td><form action='administrarCoches.php' method='post'><button value='" . $fila->id . "' name='btnDetalleCoche' class='botonAccion transformar'>" . $fila->marca . "</button></form></td>";
                        echo "<td><form class='enlinea' action='administrarCoches.php' method='post'>";
                        echo "<input type='hidden' name='foto' value='" . $fila->foto . "'/>";
                        echo "<button onclick='return confirm(\"Estas seguro de que quieres eliminar este Coche con id " . $fila->id . "? \");'type='submit' class='botonAccion' name='btnBorrar' value='" . $fila->id . "' >BORRAR</button></form> - ";
                        echo "<form action='administrarCoches.php' method='post' class='enlinea' ><button type='submit' class='botonAccion' name='btnEditar' value='" . $fila->id . "'>EDITAR </button></form></td>";
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