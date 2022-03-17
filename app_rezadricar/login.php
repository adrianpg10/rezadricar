 <?php

    session_name("proyectorez");
    session_start();
    define("DIR_SERV", "http://localhost/proyectosphp/proyectofinal/servicios_rezadri");
    define("MINUTOS", 3);


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

    function obtener_usuario($usuario, $clave)
    {
        $datos["usuario"] = $usuario;
        $datos["clave"] = $clave;
        $url = DIR_SERV . "/login";
        $resp = consumir_servicios_REST($url, "POST", $datos);

        if (isset($resp->error))
            die($resp->error);
        elseif (isset($resp->mensaje))
            return false;
        else
            return $resp->usuario;
    }



    if (isset($_POST["btnLogin"])) {
        $error_usuario = $_POST["usuario"] == "";
        $error_clave = $_POST["clave"] == "";
        if (!$error_usuario && !$error_clave) {
            $usuario = obtener_usuario($_POST["usuario"], md5($_POST["clave"]));
            if ($usuario) {

                $_SESSION["usuario"] = $_POST["usuario"];
                $_SESSION["clave"] = md5($_POST["clave"]);
                $_SESSION["ultimo_acceso"] = time();
                header("Location:login.php");
                exit;
            }
        }
    }

    if (isset($_POST["cerrarsesion"])) {
        session_destroy();
        header("Location:login.php");
        exit;
    }


    if (isset($_SESSION["usuario"]) && isset($_SESSION["clave"]) && isset($_SESSION["ultimo_acceso"])) {

        $datos_usu_logueado = obtener_usuario($_SESSION["usuario"], $_SESSION["clave"]);
    
        if ($datos_usu_logueado) {
            $tiempo_transc = time() - $_SESSION["ultimo_acceso"];
            if ($tiempo_transc > MINUTOS * 60) {
                session_unset();
                $_SESSION["tiempo"] = true;
                header("Location:index.php");
                exit;
            } else {
                $_SESSION["ultimo_acceso"] = time();
    
                if ($datos_usu_logueado->tipo == "normal") {
                    $_SESSION["id_user"] = $datos_usu_logueado->id;
                    header("Location:vistas/vista_normal.php");
                    
                } else {
                    $_SESSION["administrador"] = $datos_usu_logueado->id;
                    header("Location:vistas/vista_admin.php");
                   
                }
            }
        } else {
            session_unset();
            $_SESSION["restringida"] = true;
            header("Location:index.php");
            exit;
        }
    } elseif (isset($_POST["btnCrearCuenta"]) || isset($_POST["btnCrearCuenta"])) {
        header("Location:registro.php");
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
             <p><a onclick="location.href='index.php'">Inicio</a> &#x25B6; <small>Iniciar sesión</small></p>
         </nav>

         <?php


            if (isset($_SESSION["tiempo"])) {
                echo "Su tiempo de sesión ha caducado.Vuelve a loguearse";
                unset($_SESSION["tiempo"]);
            }
            if (isset($_SESSION["restringida"])) {
                echo "Zona restringida.Vuelve a loguearse";
                unset($_SESSION["restringida"]);
            }

            ?>

         <div id="login">
             <h1>INICIO DE SESIÓN</h1>

             <form action="login.php" method="post">
                 <label for="usuario"></label>
                 <input type="text" class="estiloinput" name="usuario" id="usuario" placeholder="USUARIO" value="<?php if (isset($_POST["btnLogin"])) echo $_POST["usuario"]; ?>" required />
                 <?php

                    if (isset($_POST["btnLogin"]) && $error_usuario)
                        if ($_POST["usuario"] == "") {
                            echo "* Campo Vacío *";
                        } else {
                            echo "El usuario no se encuentra registrado en la BD *";
                            echo "<br/><br/>";
                        }

                    ?>
                 <label for="clave"></label>
                 <input type="password" class="estiloinput" name="clave" id="clave" placeholder="CONTRASEÑA" required />
                 <input type="submit" class="boton-estilo" value="INICIAR SESIÓN" name="btnLogin" />

                  <button id="registrarse" name="registrarse" onclick="location.href='registro.php'" class="boton-registro">REGÍSTRATE</button>

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