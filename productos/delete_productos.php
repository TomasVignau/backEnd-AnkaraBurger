<?php
include '../database.php';

if (isset($_GET['id'])) {
    $idProducto = intval($_GET['id']);
    $conexion = conectarBD();

    // Primero borrar los ingredientes asociados
    $sqlIng = "DELETE FROM ingredientesenproducto WHERE id_Producto = $idProducto";
    $conexion->query($sqlIng);

    // Luego borrar el producto
    $sql = "DELETE FROM producto WHERE id_Producto = $idProducto";
    $resultado = $conexion->query($sql);

    if ($resultado) { // la consulta se ejecutó
        if ($conexion->affected_rows > 0) {
            // Se borró al menos una fila
            echo json_encode(["success" => true]);
        } else {
            // No se encontró el producto con ese id
            echo json_encode(["success" => false, "error" => "Producto no encontrado"]);
        }
    } else {
        // Error en la consulta
        echo json_encode(["success" => false, "error" => "Error en la consulta"]);
    }

} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
?>
