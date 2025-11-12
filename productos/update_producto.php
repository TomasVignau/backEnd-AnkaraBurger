<?php
include '../database.php';

$data = json_decode(file_get_contents("php://input"), true);

if (
    !isset($data['id_Producto']) ||
    !isset($data['nombre_Producto']) ||
    !isset($data['descripcion']) ||
    !isset($data['imagen']) ||
    !isset($data['precio']) ||
    !isset($data['ingredientes']) ||
    !isset($data['tipo'])
) {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
    exit;
}

$conexion = conectarBD();

// Variables del producto
$idProducto = intval($data['id_Producto']);
$nombre = $data['nombre_Producto'];
$descripcion = $data['descripcion'];
$imagen = $data['imagen'];
$precio = floatval($data['precio']);
$ingredientes = $data['ingredientes']; // array de {id, cantidad}
$tipo = $data['tipo'];

// Comenzamos la transacción
$conexion->begin_transaction();

try {
    // Actualizar datos del producto
    $sqlProducto = "UPDATE producto SET Nombre = ?, Descripcion = ?, Tipo = ?, Imagen = ?, Precio = ? WHERE id_Producto = ?";
    $stmtProducto = $conexion->prepare($sqlProducto);
    $stmtProducto->bind_param("ssssdi", $nombre, $descripcion, $tipo, $imagen, $precio, $idProducto);
    $stmtProducto->execute();
    $stmtProducto->close();

    // Borrar ingredientes existentes del producto
    $sqlBorrarIng = "DELETE FROM ingredientesenproducto WHERE id_Producto = ?";
    $stmtBorrar = $conexion->prepare($sqlBorrarIng);
    $stmtBorrar->bind_param("i", $idProducto);
    $stmtBorrar->execute();
    $stmtBorrar->close();

    // Insertar los nuevos ingredientes
    if (!empty($ingredientes)) {
        $sqlInsertIng = "INSERT INTO ingredientesenproducto (id_Producto, id_Ingrediente, Cantidad) VALUES (?, ?, ?)";
        $stmtInsert = $conexion->prepare($sqlInsertIng);
 
        foreach ($ingredientes as $ing) {
            $idIngrediente = intval($ing['id']);
            $cantidad = $ing['cantidad'];
            $stmtInsert->bind_param("iis", $idProducto, $idIngrediente, $cantidad);
            $stmtInsert->execute();
        }

        $stmtInsert->close();
    }

    // Commit de la transacción
    $conexion->commit();
    echo json_encode(["success" => true]);

} catch (Exception $e) {
    $conexion->rollback();
    echo json_encode(["success" => false, "error" => $e->getMessage()]);
}

$conexion->close();
?>
