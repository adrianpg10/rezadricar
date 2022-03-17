<?php
session_name("proyectorez");
session_start();

if (isset($_POST["cerrarsesion"])) {
    session_destroy();
    header("Location:index.php");
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

        <picture>
            <img src="images/porsche-1851246_1920.jpg" alt="portada" id="portada">
        </picture>

        <nav id="secciones">
            <article class="item">
                <picture>
                    <img src="images/audi-1636320_1920.jpg" alt="noticias" id="noticias">
                </picture>
                <a onclick="location.href='noticias.php'">
                    <h2>NOTICIAS</h2>
                </a>
                <span>Toda la informacion</span>
            </article>
            <article class="item">
                <picture>
                    <img src="images/car-5774774_1920.jpg" alt="catalogo" id="catalogo">
                </picture>
                <a onclick="location.href='catalogo.php'">
                    <h2>CATÁLOGO</h2>
                </a>
                <span>Vehículos exclusivos</span>

            </article>
            <article class="item">
                <picture>
                    <img src="images/car-1281640_1920.jpg" alt="prueba" id="prueba">
                </picture>
                <a onclick="location.href='catalogo.php'">
                    <h2>PRUEBA</h2>
                </a>
                <span>Prueba nuestras marcas</span>

            </article>

        </nav>


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