<?php
include '../database.php';

function realizarCambioDeMesa($idMesaActual, $idMesaNueva) {
    $conexion = conectarBD();

    // Iniciar la transacción
    $conexion->begin_transaction();

    header('Content-Type: application/json; charset=utf-8');

    try {
        // Liberar la mesa actual
        $sql = "UPDATE mesa SET EstadoPedido = 0 WHERE id_Mesa = $idMesaActual";
        if (!$conexion->query($sql)) {
            throw new Exception("Error liberando mesa actual: " . $conexion->error);
        }

        // Ocupar la mesa nueva
        $sql1 = "UPDATE mesa SET EstadoPedido = 1 WHERE id_Mesa = $idMesaNueva";
        if (!$conexion->query($sql1)) {
            throw new Exception("Error ocupando mesa nueva: " . $conexion->error);
        }

        // Pasar los pedidos de la mesa actual a la nueva
        $sql2 = "UPDATE pedidos 
                 SET id_Mesa = $idMesaNueva 
                 WHERE id_Mesa = $idMesaActual AND EstadoPedido = 1";
        if (!$conexion->query($sql2)) {
            throw new Exception("Error reasignando pedidos: " . $conexion->error);
        }

        // Confirmar cambios
        $conexion->commit();

        echo json_encode([
            "success" => true,
            "message" => "Cambio de mesa realizado correctamente"
        ]);

    } catch (Exception $e) {
        // Revertir cambios en caso de error
        $conexion->rollback();

        echo json_encode([
            "success" => false,
            "error" => $e->getMessage()
        ]);
    }
}

// Llamada a la función si vienen los parámetros
if (isset($_GET['idMesaActual']) && isset($_GET['idMesaNueva'])) {
    $idMesaActual = intval($_GET['idMesaActual']);
    $idMesaNueva = intval($_GET['idMesaNueva']);
    realizarCambioDeMesa($idMesaActual, $idMesaNueva);
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
