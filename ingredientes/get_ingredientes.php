<?php
include '../database.php';

$conexion = conectarBD();

// Consulta simple: todos los ingredientes
$sql = "SELECT id_Ingrediente, NombreIngrediente FROM ingrediente";
$resultado = $conexion->query($sql);

$ingredientes = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $ingredientes[] = [
            "id_Ingrediente" => (int)$fila["id_Ingrediente"],
            "NombreIngrediente" => $fila["NombreIngrediente"]
        ];
    }
}

// Devuelve un JSON con la lista
echo json_encode($ingredientes, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);