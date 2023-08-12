<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));

function obtenerClientePorId($id){
    $sentencia = "SELECT * FROM clientes WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if($cliente) return $cliente[0];
}

function obtenerClientes(){
    $sentencia = "SELECT * FROM productos";
    return select($sentencia);
}

// print_r((array)obtenerClientes());

$json = json_encode(obtenerClientes());
$datosArray = json_decode($json, true);
// print_r($datosArray);
// print_r($datosArray[0]["id"]);

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

// prioridad = readline("Ingrese 'entrega' si prioriza la entrega o 'precio' si prioriza el precio: ");
$prioridad = "entrega";

$producto_elegido = null;

if ($prioridad == "entrega") {
    // tiempo_minimo_esperado = intval(readline("Ingrese el tiempo mínimo esperado para recibir el producto: "));
    $tiempo_minimo_esperado = 0;
    foreach ($productos as $producto) {
        if ($producto_elegido === null || ($producto["tiempo_entrega"] <= $tiempo_minimo_esperado)) {
            $producto_elegido = $producto;
            if ($producto["precio"] < $producto_elegido["precio"]) {
                $producto_elegido = $producto;
            }
        }
    }
} else {
    foreach ($productos as $producto) {
        if ($producto_elegido === null || ($producto["precio"] < $producto_elegido["precio"])) {
            $producto_elegido = $producto;
        }
    }
}

if ($producto_elegido !== null) {
    echo "<br><br>Producto elegido: " . $producto_elegido["nombre"] . "\n";
    echo "<br>Precio: " . $producto_elegido["precio"] . "\n";
    echo "<br>Tiempo de entrega: " . $producto_elegido["tiempo_entrega"] . "\n";
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