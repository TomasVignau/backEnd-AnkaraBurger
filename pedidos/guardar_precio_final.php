<?php
include '../database.php';

if (isset($_GET['idMesa']) && isset($_GET['precioFinal'])) {
    $idMesa = intval($_GET['idMesa']);
    $precioFinal = floatval($_GET['precioFinal']);
    $conexion = conectarBD();

    $conexion->begin_transaction();

    try {
        // Actualizar el precio final del pedido activo
        $sql = "UPDATE pedidos 
                SET PrecioTotal = $precioFinal 
                WHERE id_Mesa = $idMesa AND EstadoPedido = 1";
        $conexion->query($sql);

        $conexion->commit();
        echo json_encode(["success" => true, "message" => "Precio final guardado correctamente"]);
    } catch (Exception $e) {
        $conexion->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
