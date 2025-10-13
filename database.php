<?php
// Configuración de la cabecera para JSON y CORS
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *'); // permite cualquier origen
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');


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