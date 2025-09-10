<?php
include '../database.php';

$conexion = conectarBD();
$sql = "SELECT * FROM mesa";
$resultado = $conexion->query($sql);

$mesas = [];
while ($row = $resultado->fetch_assoc()) {
    $mesas[] = $row;
}

echo json_encode($mesas, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
