<?php
include '../database.php';

if (isset($_GET['idMesa']) && isset($_GET['productos'])) {
    $idMesa = intval($_GET['idMesa']);
    $productos = json_decode($_GET['productos'], true);

    if (!is_array($productos)) {
        echo json_encode(["success" => false, "error" => "Formato de productos inválido"]);
        exit;
    }

    $conexion = conectarBD();
    $conexion->begin_transaction();

    try {
        // Verificar si ya existe un pedido activo
        $resultado = $conexion->query("SELECT id_Pedido FROM pedidos WHERE id_Mesa = $idMesa AND EstadoPedido = 1 LIMIT 1");

        if ($fila = $resultado->fetch_assoc()) {
            $idPedido = $fila['id_Pedido'];
        } else {
            // Crear nuevo pedido
            $conexion->query("INSERT INTO pedidos (id_Mesa, EstadoPedido, Fecha, PrecioTotal) 
                              VALUES ($idMesa, 1, NOW(), 0)");
            $idPedido = $conexion->insert_id;
        }

        // Insertar o actualizar productos
        foreach ($productos as $p) {
            $cantidad = intval($p['cantidadSeleccionada']);
            if ($cantidad <= 0) continue;

            if (isset($p['id_Producto']) && !empty($p['id_Producto'])) {
                // Producto existente
                $idProd = intval($p['id_Producto']);
            } else {
                // Producto nuevo -> insertarlo en producto primero
                $nombre = $conexion->real_escape_string($p['nombreProducto']);
                $precio = floatval($p['precio']);
                $imagen = $conexion->real_escape_string($p['urlImagen']);
                $descripcion = $conexion->real_escape_string($p['descripcionProducto']);
                $conexion->query("INSERT INTO producto (Nombre, Descripcion, Imagen, Precio) 
                                VALUES ('$nombre', '$descripcion', '$imagen', $precio)");
                $idProd = $conexion->insert_id;
            }

            // Insertar o actualizar en detalle_pedido
            $resCheck = $conexion->query("SELECT CantPorProducto FROM detalle_pedido 
                                        WHERE id_Pedido = $idPedido AND id_Producto = $idProd");
            if ($filaDetalle = $resCheck->fetch_assoc()) {
                $nuevaCantidad = $filaDetalle['CantPorProducto'] + $cantidad;
                $conexion->query("UPDATE detalle_pedido SET CantPorProducto = $nuevaCantidad 
                                WHERE id_Pedido = $idPedido AND id_Producto = $idProd");
            } else {
                $conexion->query("INSERT INTO detalle_pedido (id_Pedido, id_Producto, CantPorProducto) 
                                VALUES ($idPedido, $idProd, $cantidad)");
            }
        }


        // Cambiar estado de la mesa
        $conexion->query("UPDATE mesa SET EstadoPedido = 1 WHERE id_Mesa = $idMesa");

        $conexion->commit();
        echo json_encode(["success" => true, "message" => "Pedido agregado correctamente"]);
    } catch (Exception $e) {
        $conexion->rollback();
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
