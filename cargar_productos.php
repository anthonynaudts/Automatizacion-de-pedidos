<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));

function obtenerClientePorId($id){
    $sentencia = "SELECT * FROM clientes WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if($cliente) return $cliente[0];
}

function obtenerProductosSuplidores($idProd){
    // $sentencia = "SELECT id, idPrioridad, venta, nombre FROM productos";
    $sentencia = "SELECT ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, p.nombre 
    FROM ventas_php.productos_suplidor ps
    left join productos p on p.id = ps.idProdTienda where ps.idProdTienda = {$idProd}";
    return select($sentencia);
}


-- Buscar para pedir productos
SELECT id, codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad
FROM ventas_php.productos p
left join articulos_pedidos ap on ap.idProd = p.id 
left join pedidos p2 on p2.idPedido = ap.idPedido 
where p.existencia <= p.cantMin 
and (p2.idEstado is null or p2.idEstado = 3)



-- Buscar productos suplidores
select ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, p.nombre 
FROM ventas_php.productos_suplidor ps
left join productos p on p.id = ps.idProdTienda
where idProdTienda = 1


$json = json_encode(obtenerProductosSuplidores(1));
$datosArray = json_decode($json, true);
// print_r($datosArray);
// print_r($datosArray[0]["id"]);

//Eliminar el ultimo elemento del array
// array_pop($datosArray);
foreach ($datosArray as $cliente) {
    print_r($cliente);
    echo "<br>";
}


$productos = array(
    array("nombre" => "Producto A", "precio" => 8.99, "tiempo_entrega" => 2),
    array("nombre" => "Producto B", "precio" => 15.99, "tiempo_entrega" => 1),
    array("nombre" => "Producto C", "precio" => 8.99, "tiempo_entrega" => 3),
    array("nombre" => "Producto D", "precio" => 10.99, "tiempo_entrega" => 1)
);
print("<br><br><br><br><br><br><br>");
// var_dump($productos);

$prioridad = "";

$producto_elegido = null;

if ($prioridad == "entrega") {
    // tiempo_minimo_esperado = intval(readline("Ingrese el tiempo mínimo esperado para recibir el producto: "));
    $tiempo_minimo_esperado = 3;
    foreach ($datosArray as $producto) {
        // print_r($producto);echo "<br>";
        if ($producto_elegido === null || ($producto["tiempoEntregaProd"] <= $tiempo_minimo_esperado && $producto["tiempoEntregaProd"] <= $producto_elegido["tiempoEntregaProd"])) {
            // $producto_elegido = $producto;
            if ($producto_elegido === null || $producto["precioProd"] < $producto_elegido["precioProd"]) {
                $producto_elegido = $producto;
            }
        }
    }
} else {
    foreach ($datosArray as $producto) {
        if ($producto_elegido === null || ($producto["precioProd"] < $producto_elegido["precioProd"])) {
            $producto_elegido = $producto;
        }
    }
}

if ($producto_elegido !== null) {
    echo "<br><br>Producto elegido: " . $producto_elegido["nombre"] . "\n";
    echo "<br>Precio: " . number_format($producto_elegido["precioProd"],2) . "\n";
    echo "<br>Tiempo de entrega: " . $producto_elegido["tiempoEntregaProd"] . "\n";
} else {
    echo "<br>No se encontró ningún producto que cumpla con las condiciones.\n";
}






function select($sentencia, $parametros = []){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    $respuesta->execute($parametros);
    return $respuesta->fetchAll();
}

function insertar($sentencia, $parametros ){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}

function eliminar($sentencia, $id){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute([$id]);
}

function editar($sentencia, $parametros ){
    $bd = conectarBaseDatos();
    $respuesta = $bd->prepare($sentencia);
    return $respuesta->execute($parametros);
}

function conectarBaseDatos() {
	$host = "localhost";
	$db   = "ventas_php";
	$user = "root";
	$pass = "";
	$charset = 'utf8mb4';

	$options = [
	    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
	    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
	    \PDO::ATTR_EMULATE_PREPARES   => false,
	];
	$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
	try {
	     $pdo = new \PDO($dsn, $user, $pass, $options);
	     return $pdo;
	} catch (\PDOException $e) {
	     throw new \PDOException($e->getMessage(), (int)$e->getCode());
	}
}