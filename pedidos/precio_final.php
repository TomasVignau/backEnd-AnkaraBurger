<?php
include '../database.php';

if (isset($_GET['idMesa'])) {
    $idMesa = intval($_GET['idMesa']);
    $conexion = conectarBD();

    $sql = "SELECT SUM(dp.Cantidad * pr.Precio) as Total
            FROM pedidos p
            JOIN detalle_pedido dp ON p.id_Pedido = dp.id_Pedido
            JOIN producto pr ON dp.id_Producto = pr.id_Producto
            WHERE p.id_Mesa = $idMesa AND p.EstadoPedido = 1";

    $resultado = $conexion->query($sql);
    $row = $resultado->fetch_assoc();

    $total = $row ? (float)$row['Total'] : 0;

    echo json_encode(["total" => $total]);
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
