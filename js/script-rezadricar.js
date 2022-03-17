$(document).ready(function () {

  /*Abrir y cerrar hamburguesa*/

  /*Al hacer clic en la hamburguesa, se desplegará el menú oculto*/
  var hamburguesaabierta = false;
  $("picture #hamburguesa").click(function () {
    $("#menu-opciones").stop(true);
    $("#menu-opciones").animate({ "left": 0 }, 300);
    hamburguesaabierta = true;

    /*Cuando el menú esté abierto, haremos que la cabecerá se quede en posicion static
    Para que el usuario pueda hacer scroll dentro del menu*/
    $(window).scroll(function () {
      if (hamburguesaabierta) {
        $("nav#menu-principal").css({ "position": "static" });

      } else {
        if ($(window).scrollTop() > 3) {
          $("nav#menu-principal").css({ "position": "fixed", "left": 0, "top": 0, "width": "100%" });
        } else {
          $("nav#menu-principal").css({ "position": "static" });
        }
      }
    });

   /*Al hacer clic en la X Ocultaremos el menu*/
    $("picture #salirmenu").click(function () {
      $("#menu-opciones").animate({ "left": "-800vw" }, 300);
      hamburguesaabierta = false;
    });

  });

  /*Ocultar secciones colección*/

  $("li span#colecciones").click(function () {
    $("ul#menu-coleccion").css({ "visibility": "visible" });
  });

  /*Scroll boton volverarriba*/

  /*Mostraremos el boton cuando hagamos scroll y le daremos funcionalidad al boton 
  para que pueda volver hacia arriba de la pagina */
  $(window).scroll(function () {

    if ($(window).scrollTop() > 200) {
      $("div#volverarriba").css({ "display": "block" });
      $("div#volverarriba").click(function (event) {
        event.preventDefault();
        $("html").animate({
          scrollTop: 0
        }, 800);
      });
    } else if ($(window).scrollTop() == 0) {
      $("div#volverarriba").css({ "display": "none" });
      $("html").stop(true);
    }
  });

  /*Scroll cabecera */
  /*Cuando haga scroll, cambiaremos el estilo de la cabecera para hacerla flotante, le daremos un tamaño y una posicon*/
  $(window).scroll(function () {
    if ($(window).scrollTop() > 3) {
      $("nav#menu-principal").css({ "position": "fixed", "left": 0, "top": 0, "width": "100%", "z-index": "10000" });

    } else {
      $("nav#menu-principal").css({ "position": "static" });
    }
  });



/*Cuando redimensionemos la pantalla, si tenemos abierto el login
cerraemos dichos dichos contenedores*/
  $(window).resize(function () {

    $("#menu-opciones").animate({ "left": "-800vw" }, 300);


  })



});

