<?php
include '../database.php';

if (isset($_GET['id']) && isset($_GET['estado'])) {
    $idMesa = intval($_GET['id']);
    $estadoNuevo = intval($_GET['estado']);

    $conexion = conectarBD();
    $sql = "UPDATE mesa SET EstadoPedido = $estadoNuevo WHERE id_Mesa = $idMesa";
    $resultado = $conexion->query($sql);

    if ($resultado === TRUE) {
        echo json_encode(["success" => true, "message" => "Estado actualizado correctamente"]);
    } else {
        echo json_encode(["success" => false, "error" => $conexion->error]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
