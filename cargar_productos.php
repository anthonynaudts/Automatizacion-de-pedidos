<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));

$OrdenDeProductosPedir = array();

function productosPreOrden(){
    $sentencia = "SELECT id, codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad,(cantFija - existencia) as cantPedir
    FROM ventas_php.productos p
    left join articulos_pedidos ap on ap.idProd = p.id 
    left join pedidos p2 on p2.idPedido = ap.idPedido 
    where p.existencia <= p.cantMin 
    and (p2.idEstado is null or p2.idEstado = 3)";
    return select($sentencia);
    
}

// Ejecuta todo
// $json = json_encode(productosPreOrden());
// $productosPreOrden = json_decode($json, true);
// foreach ($productosPreOrden as $productoPO) {
//     print_r($productoPO);
//     echo "<br>";
//     elegirProductos(obtenerProductosSuplidores($productoPO["id"]),$productoPO["idPrioridad"], $productoPO["cantFija"]);
// }


function obtenerProductosSuplidores($idProd){
    // $sentencia = "SELECT id, idPrioridad, venta, nombre FROM productos";
    $sentencia = "SELECT ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, sp.nombreSuplidor, p.nombre 
    FROM ventas_php.productos_suplidor ps
    left join productos p on p.id = ps.idProdTienda
    left join suplidor sp on sp.id = ps.idSuplidor
    where ps.idProdTienda = {$idProd}";
    return select($sentencia);
}

function elegirProductos($productosSuplidores, $idPrioridad, $cantPedir){
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
    // $cantidadFija = 60;

    $producto_elegido = null;

    if ($idPrioridad == 2) {
        foreach ($datosProductosSuplidores as $productoSuplidor) {
            if($productoSuplidor["cantDisponible"] >= $cantPedir){
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
            if($productoSuplidor["cantDisponible"] >= $cantPedir){
                if ($producto_elegido === null || ($productoSuplidor["precioProd"] < $producto_elegido["precioProd"])) {
                    $producto_elegido = $productoSuplidor;
                }
            }
        }
    }

    if ($producto_elegido !== null) {
        // echo "<br><br>Producto elegido: " . $producto_elegido["nombre"] . "\n";
        // echo "<br>Precio: " . number_format($producto_elegido["precioProd"],2) . "\n";
        // echo "<br>Tiempo de entrega: " . $producto_elegido["tiempoEntregaProd"] . "\n";
        global $OrdenDeProductosPedir;
        $producto_elegido["cantPedir"] = $cantPedir;
        $OrdenDeProductosPedir[] = $producto_elegido;
    } else {
        // echo "<br>No se encontró ningún producto que cumpla con las condiciones.\n";
    }
    // echo "<hr>";
}

// print_r($OrdenDeProductosPedir);
// echo "<br>Elementos a pedir a los suplidores<br><br>";
// foreach ($OrdenDeProductosPedir as $prod) {
//     print_r($prod);
//     echo "<br>";
// }


echo "<br>";
$gruposPorSuplidor = array();

// Recorrer los arrays y agrupar los elementos por idSuplidor
function gruposPorSuplidor(){

    $json = json_encode(productosPreOrden());
    $productosPreOrden = json_decode($json, true);
    foreach ($productosPreOrden as $productoPO) {
        // print_r($productoPO);
        // echo "<br>";
        elegirProductos(obtenerProductosSuplidores($productoPO["id"]),$productoPO["idPrioridad"], $productoPO["cantPedir"]);
    }

    global $OrdenDeProductosPedir;
    foreach ($OrdenDeProductosPedir as $array) {
        $idSuplidor = $array["idSuplidor"];
        if (!isset($gruposPorSuplidor[$idSuplidor])) {
            $gruposPorSuplidor[$idSuplidor] = [];
        }
        $gruposPorSuplidor[$idSuplidor][] = $array;
    }

    // echo "<hr>";
    // foreach ($gruposPorSuplidor as $prod) {
    //     print_r($prod);
    //     echo "<br>";
    // }

    return $gruposPorSuplidor;
}

// var_dump(gruposPorSuplidor());


// Resultado: $gruposPorSuplidor contiene los arrays agrupados por idSuplidor
// print_r(gruposPorSuplidor());

function ordenesPorSuplidor(){
    $datosPedidos = gruposPorSuplidor();
    foreach ($datosPedidos as $PS) {
        $totalPedido = 0; 
        $idSuplidor = "";
        foreach ($PS as $porSuplidor) {
            // print_r($porSuplidor["cantPedir"] * $porSuplidor["precioProd"]);
            $totalPedido += $porSuplidor["cantPedir"] * $porSuplidor["precioProd"];
            $idSuplidor = $porSuplidor["idSuplidor"];
            $nombreSuplidor = $porSuplidor["nombreSuplidor"];
            // print_r(" ".$porSuplidor["cantPedir"] ." ". $porSuplidor["precioProd"]);
            echo "<br>";
        }
        registrarPedido($PS, $totalPedido, $idSuplidor, $nombreSuplidor);
        // echo $idSuplidor."  " . $totalPedido; 
        echo "<hr>";

    }
}

ordenesPorSuplidor();

function registrarPedido($PS, $totalPedido, $idSuplidor, $nombreSuplidor){
    echo "Total: {$totalPedido} <br> Suplidor: {$idSuplidor}  - {$nombreSuplidor}<br><br>";
    foreach ($PS as $prod) {
        // print_r($prod);
        echo "<style>th,td{border:5px solid transparent; text-align:left;}</style>";
        echo "<table>
            <tr>
                <th>Código</th>
                <th>Nombre producto</th>
                <th>Precio Unitario</th>
                <th>Cantidad</th>
                <th>Subtotal</th>
            </tr>
            <tr>
                <td>{$prod["idProdSuplidor"]}</td>
                <td>{$prod["nombre"]}</td>
                <td>{$prod["precioProd"]}</td>
                <td>{$prod["cantPedir"]}</td>
                <td>".$prod['precioProd'] * $prod['cantPedir']."</td>
            </tr>
        </table>";
        echo "<br>";
    }

    return;
    // $sentencia =  "INSERT INTO pedidos (fechaPedido, montoPedido, idEstado, idSuplidor) VALUES (?,?,?,?)";
    // $parametros = [date("Y-m-d H:i:s"), $total, $idUsuario, $idCliente];

    // $resultadoVenta = insertar($sentencia, $parametros);
    // if($resultadoVenta){
    //     $idVenta = obtenerUltimoIdVenta();
    //     $productosRegistrados = registrarProductosVenta($productos, $idVenta);
    //     return $resultadoVenta && $productosRegistrados;
    // }
}

function registrarProductosPedido($productos, $idVenta){
    $sentencia = "INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?,?,?,?)";
    foreach ($productos as $producto ) {
        $parametros = [$producto->cantidad, $producto->venta, $producto->id, $idVenta];
        insertar($sentencia, $parametros);
        descontarProductos($producto->id, $producto->cantidad);
    }
    return true;
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