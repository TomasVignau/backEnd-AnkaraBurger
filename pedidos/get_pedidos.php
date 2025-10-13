<?php
include '../database.php';

$conexion = conectarBD();

// Consultamos todos los pedidos
$sqlPedidos = "SELECT id_Pedido, id_Mesa, EstadoPedido, PrecioTotal, Fecha 
               FROM pedidos";
$resultPedidos = $conexion->query($sqlPedidos);

$pedidos = [];

while ($pedido = $resultPedidos->fetch_assoc()) {
    $pedidos[] = [
        "id_Pedido" => $pedido['id_Pedido'],
        "id_Mesa" => $pedido['id_Mesa'],
        "EstadoPedido" => $pedido['EstadoPedido'],
        "PrecioTotal" => (float)$pedido['PrecioTotal'],
        "Fecha" => $pedido['Fecha']
    ];
}

echo json_encode($pedidos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
