<?php
header('Content-Type: application/json; charset=utf-8');

function conectarBD() {
    $conexion = new mysqli("localhost", "", "", "hamburgueseria");
    if ($conexion->connect_error) {
        die(json_encode(["error" => "Fallo la conexión: " . $conexion->connect_error]));
    }
    return $conexion;
}
