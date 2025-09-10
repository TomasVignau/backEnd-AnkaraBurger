<?php
include '../database.php';

if (isset($_GET['id'])) {
    $idMesa = intval($_GET['id']);
    $conexion = conectarBD();

    $sql = "SELECT EstadoPedido FROM mesa WHERE id_Mesa = $idMesa";
    $resultado = $conexion->query($sql);

    if ($fila = $resultado->fetch_assoc()) {
        echo json_encode(["estado" => intval($fila['EstadoPedido'])]);
    } else {
        echo json_encode(["success" => false, "error" => "Mesa no encontrada"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
