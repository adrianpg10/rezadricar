<?php
require 'Slim/autoload.php';
require "src/funciones_servicios.php";
$app = new \Slim\App;

//Comienzo a crear los Servicios

$app->post("/login", function ($request) {

    $usuario = $request->getParam("usuario");
    $clave = $request->getParam("clave");
    echo json_encode(obtener_usuario($usuario, $clave), JSON_FORCE_OBJECT);
});

$app->get("/repetido/{tabla}/{columna}/{valor}",function($request){
    $tabla=$request->getAttribute("tabla");
    $columna=$request->getAttribute("columna");
    $valor=$request->getAttribute("valor");
    echo json_encode(repetido($tabla,$columna,$valor),JSON_FORCE_OBJECT);
});

$app->get("/repetido/{tabla}/{columna}/{valor}/{id}",function($request){
    $tabla=$request->getAttribute("tabla");
    $columna=$request->getAttribute("columna");
    $valor=$request->getAttribute("valor");
    $id=$request->getAttribute("id");
    echo json_encode(repetido($tabla,$columna,$valor,$id),JSON_FORCE_OBJECT);
});

// ACCIONES ADMINISTRAR USUARIOS

$app->post("/insertarUsuario",function($request){
    $usuario=$request->getParam("usuario");
    $nombre=$request->getParam("nombre");
    $clave=$request->getParam("clave");
    $telefono=$request->getParam("telefono");
    $email=$request->getParam("email");
    
    echo json_encode(insertarUsuario($usuario,$nombre,$clave,$telefono,$email),JSON_FORCE_OBJECT);
});

$app->get("/obtenerUsuario/{id}",function($request){
    
    echo json_encode(obtenerDatosUsuario($request->getAttribute("id")),JSON_FORCE_OBJECT);
});

$app->get("/obtenerUsuarios",function(){
    
    echo json_encode(obtenerUsuarios(),JSON_FORCE_OBJECT);
});

$app->delete("/borrarUsuario/{id}",function($request){
    $idUsuario= $request->getAttribute("id");
    echo json_encode(borrarUsuario($idUsuario),JSON_FORCE_OBJECT);
});

$app->put("/actualizarUsuario/{id}",function($request){
    
    $idUsuario=$request->getAttribute("id");
    $nombre=$request->getParam("nombre");
    $usuario=$request->getParam("usuario");
    $clave=$request->getParam("clave");
    $telefono=$request->getParam("telefono");
    $email=$request->getParam("email");
    
    echo json_encode(actualizarUsuario($idUsuario,$nombre,$usuario,$clave,$telefono,$email),JSON_FORCE_OBJECT);
});

// ACCIONES ADMINISTRAR COCHES

$app->get("/obtenerCoches",function(){
    
    echo json_encode(obtenerCoches(),JSON_FORCE_OBJECT);
});


$app->post("/insertarCoche",function($request){
    $marca=$request->getParam("marca");
    $modelo=$request->getParam("modelo");
    $combustible=$request->getParam("combustible");
    $precio=$request->getParam("precio");
    $anyofabricacion=$request->getParam("anyofabricacion");
    $stock=$request->getParam("stock");
    echo json_encode(insertarCoche($marca,$modelo,$combustible,$precio,$anyofabricacion,$stock),JSON_FORCE_OBJECT);
});

$app->put("/actualizarFoto/{id}",function($request){

    $nombre_nuevo=$request->getParam("nombre_nuevo");
    $id_coche=$request->getAttribute("id");
    echo json_encode(actualizarFotoCoche($nombre_nuevo,$id_coche),JSON_FORCE_OBJECT);
});


$app->delete("/borrarCoche/{id}",function($request){

    echo json_encode(borrarCoche($request->getAttribute("id")),JSON_FORCE_OBJECT);
});

$app->get("/obtenerCoche/{id}",function($request){
    
    echo json_encode(obtenerDatosCoche($request->getAttribute("id")),JSON_FORCE_OBJECT);
});

$app->put("/actualizarCoche/{id}",function($request){
    
    $idCoche=$request->getAttribute("id");
    $marca=$request->getParam("marca");
    $modelo=$request->getParam("modelo");
    $combustible=$request->getParam("combustible");
    $precio=$request->getParam("precio");
    $anyofabricacion=$request->getParam("anyofabricacion");
    $stock=$request->getParam("stock");
    
    echo json_encode(actualizarCoche($idCoche,$marca,$modelo,$combustible,$precio,$anyofabricacion,$stock),JSON_FORCE_OBJECT);
});

$app->post("/insertarPrueba",function($request){
    $fecha=$request->getParam("fecha");
    $hora=$request->getParam("hora");
    $fk_usuarios_id=$request->getParam("fk_usuarios_id");
    $fk_coches_id=$request->getParam("fk_coches_id");
    $fk_empleados_id=$request->getParam("fk_empleados_id");

    echo json_encode(insertarPrueba($fecha,$hora,$fk_usuarios_id,$fk_empleados_id,$fk_coches_id),JSON_FORCE_OBJECT);
});

$app->delete("/borrarCita/{id}",function($request){
    $idCita= $request->getAttribute("id");
    echo json_encode(borrarCita($idCita),JSON_FORCE_OBJECT);
});

$app->post("/insertarVenta",function($request){
    $fecha=$request->getParam("fecha");
    $fk_usuarios_id=$request->getParam("fk_usuarios_id");
    $fk_coches_id=$request->getParam("fk_coches_id");

    echo json_encode(insertarVenta($fecha,$fk_usuarios_id,$fk_coches_id),JSON_FORCE_OBJECT);
});

$app->delete("/borrarCompra/{id}",function($request){
    $idCoche= $request->getAttribute("id");
    echo json_encode(borrarCompra($idCoche),JSON_FORCE_OBJECT);
});


$app->run();
