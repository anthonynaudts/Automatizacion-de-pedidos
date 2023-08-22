<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));
require 'PHPMailer/PHPMailerAutoload.php'; 
require 'PHPMailer/class.phpmailer.php';

$OrdenDeProductosPedir = array();

function productosPreOrden(){
    $sentencia = "SELECT id, codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad,(cantFija - existencia) as cantPedir
    FROM productos p
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
    $sentencia = "SELECT ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, sp.nombreSuplidor, p.nombre, p.idPrioridad
    FROM productos_suplidor ps
    left join productos p on p.id = ps.idProdTienda
    left join suplidor sp on sp.id = ps.idSuplidor
    where ps.idProdTienda = {$idProd}";
    return select($sentencia);
}

function elegirProductos($productosSuplidores, $idPrioridad, $cantPedir){
    $json = json_encode($productosSuplidores);
    // $json = json_encode(obtenerProductosSuplidores(1));
    $datosProductosSuplidores = json_decode($json, true);

    // idPrioridad: 1- Menor costo   2- Menor tiempo entrega

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
$gruposPorPrioridad = array();
// Recorrer los arrays y agrupar los elementos por idSuplidor
function gruposPorSuplidor(){

    $json = json_encode(productosPreOrden());
    $productosPreOrden = json_decode($json, true);
    if ($productosPreOrden == null){
        return $gruposPorSuplidor =null;
    }

    foreach ($productosPreOrden as $productoPO) {
        elegirProductos(obtenerProductosSuplidores($productoPO["id"]),$productoPO["idPrioridad"], $productoPO["cantPedir"]);
    }

    global $OrdenDeProductosPedir;
    foreach ($OrdenDeProductosPedir as $array) {
        $idSuplidor = $array["idSuplidor"];
        $idPrioridad = $array["idPrioridad"];
        $idProdTienda = $array["idProdTienda"];
        
        if ($idPrioridad === 1) {
            // Agrupar por idSuplidor
            if (!isset($gruposPorSuplidor[$idSuplidor])) {
                $gruposPorSuplidor[$idSuplidor] = [];
            }
            $gruposPorSuplidor[$idSuplidor][] = $array;
        } else {
            if (!isset($gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad])) {
                $gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad] = [];
            }
            $gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad][] = $array;
            // Agregar al array sin agrupar
            // if (!isset($gruposPorSuplidor["otros"])) {
            //     $gruposPorSuplidor["otros"] = [];
            // }
            // $gruposPorSuplidor["otros"][] = $array;
        }

    }

    // print_r($gruposPorSuplidor);echo"<hr>";

    // echo "<hr>";
    // foreach ($gruposPorSuplidor as $prod) {
    //     print_r($prod);
    //     // foreach ($prod as $prod2) {print_r($prod2);}
    //     echo "<br><br><br><br><br><br>";
    // }
    // echo "<hr>";
    // foreach ($sinAgrupar as $prod) {
    //         print_r($prod);
    //         foreach ($prod as $prod2) {
    //             print_r($prod2);
    //             echo "<br><br>";
    //         }
    //         echo "<br><br>";
    //     }
    return $gruposPorSuplidor;
}


function ordenesPorSuplidor(){
    $datosPedidos = gruposPorSuplidor();
    // print_r($datosPedidos);
    // echo "<hr>";

    if($datosPedidos == null){
        return;
    }

    foreach ($datosPedidos as $PS) {
        // print_r($PS);
        $totalPedido = 0; 
        $idSuplidor = "";
        foreach ($PS as $porSuplidor) {
            // echo "<br><hr>";
            // print_r($porSuplidor);
            // print_r($porSuplidor["cantPedir"] * $porSuplidor["precioProd"]);
            $totalPedido += $porSuplidor["cantPedir"] * $porSuplidor["precioProd"];
            $idSuplidor = $porSuplidor["idSuplidor"];
            $idPrioridad = $porSuplidor["idPrioridad"];
            $nombreSuplidor = $porSuplidor["nombreSuplidor"];
            // print_r(" ".$porSuplidor["cantPedir"] ." ". $porSuplidor["precioProd"]);
            // echo "<br>";
        }
        

        // echo "{$totalPedido}";



        $idPedido = registrarPedido($PS, $totalPedido, $idSuplidor);
        // ERROR Activar para usar en producción
        // sleep(10);
        // [p] Asignar idPedido de la funcion registrarPedido

        // $idPedido = 1;
        enviarCorreo($PS, $totalPedido, $idSuplidor, $nombreSuplidor,$idPedido,$idPrioridad);
        // return;
        // echo "<hr>";

    }
}

ordenesPorSuplidor();

function registrarPedido($PS, $totalPedido, $idSuplidor){
    
    return;
    $sentencia =  "INSERT INTO pedidos (fechaPedido, montoPedido, idEstado, idSuplidor) VALUES (?,?,?,?)";
    $parametros = [date("Y-m-d H:i:s"), $totalPedido, '1', $idSuplidor];

    $resultadoPedido = insertar($sentencia, $parametros);
    if($resultadoPedido){
        $idPedido = obtenerUltimoIdPedido();
        $productosRegistrados = registrarProductosPedido($PS, $idPedido);
        // return $resultadoPedido && $productosRegistrados;
        return $idPedido;
    }
}

function obtenerUltimoIdPedido(){
    $sentencia  = "SELECT idPedido FROM pedidos ORDER BY idPedido DESC LIMIT 1";
    return select($sentencia)[0]->idPedido;
}

function obtenerCorreoSuplidor($idSuplidor){
    $sentencia  = "SELECT emailPedidos FROM suplidor WHERE {$idSuplidor}";
    return select($sentencia)[0]->emailPedidos;
}

function registrarProductosPedido($productos, $idPedido){
    $sentencia = "INSERT INTO articulos_pedidos (idPedido, idProd, cantidad, precioUnitario) VALUES (?,?,?,?)";
    foreach ($productos as $producto) {
        $parametros = [$idPedido, $producto["idProdTienda"], $producto["cantPedir"], $producto['precioProd']];
        insertar($sentencia, $parametros);
    }
    return true;
}


function enviarCorreo($PS, $totalPedido, $idSuplidor, $nombreSuplidor,$idPedido, $idPrioridad){
    // require 'PHPMailer/PHPMailerAutoload.php'; 
    // require 'PHPMailer/class.phpmailer.php';

    $mail = new PHPMailer;
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->SMTPDebug = 0;
    $mail->CharSet = 'UTF-8';
    $mail->isSMTP();
    $mail->Host = 'smtp.office365.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'xtreme1208@hotmail.com';
    $mail->Password = 'Xtr3m31516!!';
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->setFrom('xtreme1208@hotmail.com', 'Sport Zone');


    $correoSuplidor = obtenerCorreoSuplidor($idSuplidor);

    $mail->addAddress($correoSuplidor, $nombreSuplidor);

    $mail->Subject = "Nuevo Pedido - {$nombreSuplidor}!";
    $mail->IsHTML(true);

    $tablaProductos = "";
    foreach ($PS as $prod) { 
        $tablaProductos .= "<tr>
        <td>{$prod["idProdSuplidor"]}</td>
        <td>{$prod["nombre"]}</td>
        <td>".number_format($prod["precioProd"],2)."</td>
        <td>{$prod["cantPedir"]}</td>
        <td>".number_format($prod['precioProd'] * $prod['cantPedir'], 2)."</td>
    </tr>";
    }

    $mensaje_email = "<!DOCTYPE html>
    <html lang='en'>
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <style>
            body{
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            *{
                box-sizing: border-box;
                font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            .bd-sp{
                border-spacing: 0;
            }
            .bor-tb *{
                border: 1px solid #383838;
                
            }
            caption{
                font-weight: bold;
                background-color: #fe7112;
                color: white;
                padding: 4px 1px;
            }
            .bor-tb th{
                background-color: #f8d2b9;
            }
    
            .bor-tb td{
                padding: 2px 4px;
            }
            .btn-confirmar{
                width: 100%;
                margin: 10px;
                display: flex;
                justify-content: center;
                align-items: center;
            }
            p{
                text-align: left;
                margin-bottom: 6px;
            }
        </style>
    </head>
    <body>
        <p>Saludos, distinguidos {$nombreSuplidor}, estamos requiriendo de los siguientes productos</p>
        <p>".($idPrioridad == 2) ? "Estos productos son requeridos lo más pronto posible":""."</p>
        <table class='bd-sp bor-tb'>
            <caption>Orden de compra SportZone</caption>
            <tr>
              <th>Código</th>
              <th>Nombre producto</th>
              <th>Precio Unitario</th>
              <th>Cantidad</th>
              <th>Sub-Total</th>
            </tr>
            {$tablaProductos}
    </table>
    <br>
    <table border:0;>
      <tr style='font-size:20px;'>
        <th>Monto total:</th>
        <td>".number_format($totalPedido,2)."</td>
      </tr>
    </table>
    <div class='btn-confirmar'>
      <a href='http://localhost/confirmar_pedido.php?idPedido={$idPedido}' style='text-decoration: none;padding: 6px 10px; background-color: #fe7112; color:white; font-weight:bold; border-radius:3px;'>¡Confirmar pedido!</a>
    </div>
    </body>
    </html>";




    // $mensaje_email = 'Mensaje de prueba';

    $mail->Body = $mensaje_email;
    
    // TODO Activar envío de correos
    // $mail->send();
    // if($mail->send()) {
    //     echo "Enviado correctamente";
    // } else {
    //     echo "Error al enviar";
    // }

    $mail->clearAddresses();
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
	$db   = "auto_pedido";
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