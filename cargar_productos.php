<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));

$OrdenDeProductosPedir = array();

function productosPreOrden(){
    $sentencia = "SELECT id, codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad
    FROM ventas_php.productos p
    left join articulos_pedidos ap on ap.idProd = p.id 
    left join pedidos p2 on p2.idPedido = ap.idPedido 
    where p.existencia <= p.cantMin 
    and (p2.idEstado is null or p2.idEstado = 3)";
    return select($sentencia);
    
}


$json = json_encode(productosPreOrden());
$productosPreOrden = json_decode($json, true);
foreach ($productosPreOrden as $productoPO) {
    print_r($productoPO);
    // echo "Pr: ".$productoPO["idPrioridad"] . " CaF: ".$productoPO["cantFija"] . " -".$productoPO["nombre"];
    echo "<br>";
    elegirProductos(obtenerProductosSuplidores($productoPO["id"]),$productoPO["idPrioridad"], $productoPO["cantFija"]);
}



// echo "<hr>";

function obtenerProductosSuplidores($idProd){
    // $sentencia = "SELECT id, idPrioridad, venta, nombre FROM productos";
    $sentencia = "SELECT ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, p.nombre 
    FROM ventas_php.productos_suplidor ps
    left join productos p on p.id = ps.idProdTienda where ps.idProdTienda = {$idProd}";
    return select($sentencia);
}



// -- Buscar para pedir productos
// SELECT id, codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad
// FROM ventas_php.productos p
// left join articulos_pedidos ap on ap.idProd = p.id 
// left join pedidos p2 on p2.idPedido = ap.idPedido 
// where p.existencia <= p.cantMin 
// and (p2.idEstado is null or p2.idEstado = 3)



// -- Buscar productos suplidores
// select ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, p.nombre 
// FROM ventas_php.productos_suplidor ps
// left join productos p on p.id = ps.idProdTienda
// where idProdTienda = 1




function elegirProductos($productosSuplidores, $idPrioridad, $cantFija){
    // echo "<br>";
    $json = json_encode($productosSuplidores);
    // $json = json_encode(obtenerProductosSuplidores(1));
    $datosProductosSuplidores = json_decode($json, true);


    // foreach ($datosProductosSuplidores as $cliente) {
    //     print_r($cliente);
    //     echo "<br>";
    // }
    // idPrioridad: 1- Menor costo   2- Menor tiempo entrega
    // $prioridad = "entrega";
    $cantidadFija = 60;

    $producto_elegido = null;

    if ($idPrioridad == 2) {
        // $tiempo_minimo_esperado = 3;
        foreach ($datosProductosSuplidores as $productoSuplidor) {
            if($productoSuplidor["cantDisponible"] >= $cantFija){
                if ($producto_elegido === null || ($productoSuplidor["tiempoEntregaProd"] <= $producto_elegido["tiempoEntregaProd"])) {
                    $producto_elegido = $productoSuplidor;
                    if ($producto_elegido === null || $productoSuplidor["precioProd"] < $producto_elegido["precioProd"]) {
                        $producto_elegido = $productoSuplidor;
                    }
                }
            }
        }
    } else {
        foreach ($datosProductosSuplidores as $productoSuplidor) {
            if($productoSuplidor["cantDisponible"] >= $cantFija){
                if ($producto_elegido === null || ($productoSuplidor["precioProd"] < $producto_elegido["precioProd"])) {
                    $producto_elegido = $productoSuplidor;
                }
            }
        }
    }

    if ($producto_elegido !== null) {
        echo "<br><br>Producto elegido: " . $producto_elegido["nombre"] . "\n";
        echo "<br>Precio: " . number_format($producto_elegido["precioProd"],2) . "\n";
        echo "<br>Tiempo de entrega: " . $producto_elegido["tiempoEntregaProd"] . "\n";
        global $OrdenDeProductosPedir;
        $OrdenDeProductosPedir[] = $producto_elegido;
    } else {
        echo "<br>No se encontró ningún producto que cumpla con las condiciones.\n";
    }
    echo "<hr>";
}

// print_r($OrdenDeProductosPedir);
echo "<br>Elementos a pedir a los suplidores<br><br>";
foreach ($OrdenDeProductosPedir as $prod) {
    print_r($prod);
    echo "<br>";
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