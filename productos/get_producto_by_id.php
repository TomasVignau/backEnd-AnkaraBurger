<?php
include '../database.php';

if (!isset($_GET['id'])) {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
    exit;
}

$idProducto = intval($_GET['id']);
$conexion = conectarBD();

// Traer producto
$sql = "SELECT id_Producto, Nombre, Descripcion, Imagen, Precio 
        FROM producto 
        WHERE Disponible = 1 AND id_Producto = $idProducto";
$resultado = $conexion->query($sql);

if (!$resultado || $resultado->num_rows === 0) {
    echo json_encode(["success" => false, "error" => "Producto no encontrado"]);
    exit;
}

$producto = $resultado->fetch_assoc();

// Traer ingredientes del producto
$sqlIng = "SELECT i.id_Ingrediente, i.NombreIngrediente, iep.Cantidad 
           FROM ingredientesenproducto iep
           JOIN ingrediente i ON iep.id_Ingrediente = i.id_Ingrediente
           WHERE iep.id_Producto = $idProducto";
$resIng = $conexion->query($sqlIng);

$ingredientes = [];
while ($filaIng = $resIng->fetch_assoc()) {
    $ingredientes[] = [
        "id" => (int)$filaIng['id_Ingrediente'],
        "nombre" => $filaIng['NombreIngrediente'],
        "cantidad" => (string)$filaIng['Cantidad']
    ];
}

// Devolver producto con ingredientes
echo json_encode([
    "id_Producto" => (int)$producto["id_Producto"],
    "nombre_Producto" => $producto["Nombre"],
    "imagen" => $producto["Imagen"],
    "descripcion" => $producto["Descripcion"],
    "precio_unitario" => (float)$producto["Precio"],
    "ingredientes" => $ingredientes
], JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
?>
