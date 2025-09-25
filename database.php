<?php
header('Content-Type: application/json; charset=utf-8');

//CONECTAR DE MANERA LOCAL

function conectarBD() {
    $conexion = new mysqli("localhost", "", "", "hamburgueseria");
    if ($conexion->connect_error) {
        die(json_encode(["error" => "Fallo la conexión: " . $conexion->connect_error]));
    }
    return $conexion;
}


//CONECTAR CON RAILWAY

/*function conectarBD() {
    $conexion = new mysqli("shinkansen.proxy.rlwy.net", "root", "KCUuznzRSoPQQHmMCTLunZZYjGexquDH", "hamburgueseria", 35572);
    if ($conexion->connect_error) {
        die(json_encode(["error" => "Fallo la conexión: " . $conexion->connect_error]));
    }
    return $conexion;
}*/