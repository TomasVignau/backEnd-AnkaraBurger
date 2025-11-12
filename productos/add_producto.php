<?php
include '../database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    isset($data['nombre_Producto']) &&
    isset($data['descripcion']) &&
    isset($data['imagen']) &&
    isset($data['precio']) &&
    isset($data['tipo']
) {
    $nombre = $data['nombre_Producto'];
    $descripcion = $data['descripcion'];
    $disponible = 1;
    $imagen = $data['imagen'];
    $precio = floatval($data['precio']);
    $tipo = $data['tipo'];

    $conexion = conectarBD();

    $sql = "INSERT INTO producto (Nombre, Descripcion, Tipo, Disponible, Imagen, Precio)
            VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $conexion->prepare($sql);
    if (!$stmt) {
        echo json_encode(["success" => false, "error" => $conexion->error]);
        exit;
    }

    $stmt->bind_param("ssisd", $nombre, $descripcion, $tipo, $disponible, $imagen, $precio);

    if ($stmt->execute()) {
        // Obtengo el ID del producto recién insertado
        $idProducto = $conexion->insert_id;

        // Insertar los ingredientes si vienen
        if (isset($data['ingredientes']) && is_array($data['ingredientes'])) {
            $sqlIng = "INSERT INTO ingredientesenproducto (id_Producto, id_Ingrediente, Cantidad) VALUES (?, ?, ?)";
            $stmtIng = $conexion->prepare($sqlIng);
            if (!$stmtIng) {
                echo json_encode(["success" => false, "error" => $conexion->error]);
                exit;
            }

            foreach ($data['ingredientes'] as $ing) {
                $stmtIng->bind_param("iis", $idProducto, $ing['id'], $ing['cantidad']);
                $stmtIng->execute();
            }
            $stmtIng->close();
        }

        echo json_encode(["success" => true]);
    } else {
        echo json_encode(["success" => false, "error" => $stmt->error]);
    }

    $stmt->close();
    $conexion->close();

} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
?>
