<?php
include '../database.php';

if (isset($_GET['idMesa'])) {
    $idMesa = intval($_GET['idMesa']);
    $conexion = conectarBD();

    $conexion->begin_transaction();

    try {
        // Cerrar pedido
        $conexion->query("UPDATE pedidos SET EstadoPedido = 0 
                          WHERE id_Mesa = $idMesa AND EstadoPedido = 1");

        // Liberar mesa
        $conexion->query("UPDATE mesa SET EstadoPedido = 0 WHERE id_Mesa = $idMesa");

        $conexion->commit();
        echo json_encode(["success" => true, "message" => "Pedido finalizado"]);
    } catch (Exception $e) {
        $conexion->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
