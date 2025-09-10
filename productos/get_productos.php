<?php
include '../database.php';

$conexion = conectarBD();
$sql = "SELECT id_Producto, Nombre, Descripcion, Imagen, Precio 
        FROM producto WHERE Disponible = 1";
$resultado = $conexion->query($sql);

$productos = [];

while ($producto = $resultado->fetch_assoc()) {
    $idProducto = $producto['id_Producto'];

    $sqlIng = "SELECT i.NombreIngrediente, iep.Cantidad 
               FROM IngredientesEnProducto iep
               JOIN Ingrediente i ON iep.id_Ingrediente = i.id_Ingrediente
               WHERE iep.id_Producto = $idProducto";
    $resIng = $conexion->query($sqlIng);

    $ingredientes = [];
    while ($filaIng = $resIng->fetch_assoc()) {
        $ingredientes[$filaIng['NombreIngrediente']] = (int)$filaIng['Cantidad'];
    }
    if (empty($ingredientes)) {
        $ingredientes = new stdClass();
    }

    $productos[] = [
        "nombre_Producto" => $producto["Nombre"],
        "imagen" => $producto["Imagen"],
        "descripcion" => $producto["Descripcion"],
        "precio_unitario" => (float)$producto["Precio"],
        "ingredientes" => $ingredientes
    ];
}

echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
