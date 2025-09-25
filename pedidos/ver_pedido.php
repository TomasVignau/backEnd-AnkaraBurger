<?php
include '../database.php';

if (isset($_GET['idMesa'])) {
    $idMesa = intval($_GET['idMesa']);
    $conexion = conectarBD();

    // Traer todos los productos del pedido activo de la mesa
    $sql = "SELECT p.id_Producto, p.Nombre, p.Descripcion, p.Imagen, p.Precio, detP.CantPorProducto
            FROM pedidos AS ped
            JOIN detalle_pedido AS detP ON ped.id_Pedido = detP.id_Pedido
            JOIN producto AS p ON detP.id_Producto = p.id_Producto
            WHERE ped.id_Mesa = $idMesa AND ped.EstadoPedido = 1";

    $resultado = $conexion->query($sql);

    $productos = [];

    while ($producto = $resultado->fetch_assoc()) {
        $idProducto = $producto['id_Producto'];

        // Traer los ingredientes de cada producto
        $sqlIng = "SELECT i.NombreIngrediente, iep.Cantidad 
                   FROM ingredientesenproducto iep
                   JOIN ingrediente i ON iep.id_Ingrediente = i.id_Ingrediente
                   WHERE iep.id_Producto = $idProducto";

        $resIng = $conexion->query($sqlIng);

        $ingredientes = [];
        while ($filaIng = $resIng->fetch_assoc()) {
            $ingredientes[$filaIng['NombreIngrediente']] = (int)$filaIng['Cantidad'];
        }

        if (empty($ingredientes)) {
            $ingredientes = new stdClass(); // para que sea un objeto vacío y no un array
        }

        $productos[] = [
            "nombre_Producto" => $producto["Nombre"],
            "imagen" => $producto["Imagen"],
            "descripcion" => $producto["Descripcion"],
            "precio_unitario" => (float)$producto["Precio"],
            "cantidad_seleccionada" => (int)$producto["CantPorProducto"],
            "ingredientes" => $ingredientes
        ];
    }

    echo json_encode($productos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

} else {
    echo json_encode(["success" => false, "error" => "Faltan parámetros"]);
}
