<?php
include '../database.php';

if (isset($_GET['idMesa'])) {
    $idMesa = intval($_GET['idMesa']);
    $conexion = conectarBD();

    $sql = "SELECT FechaHora 
            FROM pedidos 
            WHERE id_Mesa = $idMesa AND EstadoPedido = 1
            ORDER BY id_Pedido DESC LIMIT 1";

    $resultado = $conexion->query($sql);

    if ($row = $resultado->fetch_assoc()) {
        echo json_encode(["fecha_hora" => $row['FechaHora']]);
    } else {
        echo json_encode(["success" => false, "error" => "No se encontró pedido activo"]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
