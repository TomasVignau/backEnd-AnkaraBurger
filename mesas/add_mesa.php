<?php
include '../database.php';

$conexion = conectarBD();

// Consulta para agregar una nueva mesa con EstadoPedido = 0
$sql = "INSERT INTO mesa (EstadoPedido) VALUES (0)";

if ($conexion->query($sql) === TRUE) {
    echo json_encode([
        "success" => true,
        "message" => "Mesa agregada correctamente",
        "idMesa" => $conexion->insert_id // opcional: devuelve el ID generado
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Error al agregar la mesa: " . $conexion->error
    ], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
}

$conexion->close();
?>
