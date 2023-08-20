<?php
date_default_timezone_set("America/Santo_Domingo");
define("PASSWORD_PREDETERMINADA", "12345678");
define("HOY", date("Y-m-d"));
require 'PHPMailer/PHPMailerAutoload.php'; 
require 'PHPMailer/class.phpmailer.php';

function iniciarSesion($usuario, $password){
    $sentencia = "SELECT id, usuario FROM usuarios WHERE usuario  = ?";
    $resultado = select($sentencia, [$usuario]);
    if($resultado){
        $usuario = $resultado[0];
        $verificaPass = verificarPassword($usuario->id, $password);
        if($verificaPass) return $usuario;
    }
}

function verificarPassword($idUsuario, $password){
    $sentencia = "SELECT password FROM usuarios WHERE id = ?";
    $contrasenia = select($sentencia, [$idUsuario])[0]->password;
    $verifica = password_verify($password, $contrasenia);
    if($verifica) return true;
}

function cambiarPassword($idUsuario, $password){
    $nueva = password_hash($password, PASSWORD_DEFAULT);
    $sentencia = "UPDATE usuarios SET password = ? WHERE id = ?";
    return editar($sentencia, [$nueva, $idUsuario]);
}

function eliminarUsuario($id){
    $sentencia = "DELETE FROM usuarios WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarUsuario($usuario, $nombre, $telefono, $direccion, $id){
    $sentencia = "UPDATE usuarios SET usuario = ?, nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
    $parametros = [$usuario, $nombre, $telefono, $direccion, $id];
    return editar($sentencia, $parametros);
}

function obtenerUsuarioPorId($id){
    $sentencia = "SELECT id, usuario, nombre, telefono, direccion FROM usuarios WHERE id = ?";
    return select($sentencia, [$id])[0];
}

function obtenerUsuarios(){
    $sentencia = "SELECT id, usuario, nombre, telefono, direccion FROM usuarios";
    return select($sentencia);
}

function registrarUsuario($usuario, $nombre, $telefono, $direccion){
    $password = password_hash(PASSWORD_PREDETERMINADA, PASSWORD_DEFAULT);
    $sentencia = "INSERT INTO usuarios (usuario, nombre, telefono, direccion, password) VALUES (?,?,?,?,?)";
    $parametros = [$usuario, $nombre, $telefono, $direccion, $password];
    return insertar($sentencia, $parametros);
}


function eliminarCliente($id){
    $sentencia = "DELETE FROM clientes WHERE id = ?";
    return eliminar($sentencia, $id);
}

function editarCliente($nombre, $telefono, $direccion, $id){
    $sentencia = "UPDATE clientes SET nombre = ?, telefono = ?, direccion = ? WHERE id = ?";
    $parametros = [$nombre, $telefono, $direccion, $id];
    return editar($sentencia, $parametros);
}

function obtenerClientePorId($id){
    $sentencia = "SELECT * FROM clientes WHERE id = ?";
    $cliente = select($sentencia, [$id]);
    if($cliente) return $cliente[0];
}

function obtenerClientes(){
    $sentencia = "SELECT * FROM clientes";
    return select($sentencia);
}

function obtenerSuplidor(){
    $sentencia = "SELECT * FROM suplidor";
    return select($sentencia);
}

function obtenerEstados(){
    $sentencia = "SELECT * FROM estados";
    return select($sentencia);
}

function registrarCliente($nombre, $telefono, $direccion){
    $sentencia = "INSERT INTO clientes (nombre, telefono, direccion) VALUES (?,?,?)";
    $parametros = [$nombre, $telefono, $direccion];
    return insertar($sentencia, $parametros);
}

function obtenerNumeroVentas(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM ventas";
    return select($sentencia)[0]->total;
}

function obtenerNumeroUsuarios(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM usuarios";
    return select($sentencia)[0]->total;
}

function obtenerNumeroClientes(){
    $sentencia = "SELECT IFNULL(COUNT(*),0) AS total FROM clientes";
    return select($sentencia)[0]->total;
}


function obtenerVentasPorUsuario(){
    $sentencia = "SELECT SUM(ventas.total) AS total, usuarios.usuario, COUNT(*) AS numeroVentas 
    FROM ventas
    INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
    GROUP BY ventas.idUsuario
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerVentasPorCliente(){
    $sentencia = "SELECT SUM(ventas.total) AS total, IFNULL(clientes.nombre, 'MOSTRADOR') AS cliente,
    COUNT(*) AS numeroCompras
    FROM ventas
    LEFT JOIN clientes ON clientes.id = ventas.idCliente
    GROUP BY ventas.idCliente
    ORDER BY total DESC";
    return select($sentencia);
}

function obtenerProductosMasVendidos(){
    $sentencia = "SELECT SUM(productos_ventas.cantidad * productos_ventas.precio) AS total, SUM(productos_ventas.cantidad) AS unidades,
    productos.nombre FROM productos_ventas INNER JOIN productos ON productos.id = productos_ventas.idProducto
    GROUP BY productos_ventas.idProducto
    ORDER BY total DESC
    LIMIT 10";
    return select($sentencia);
}

function obtenerTotalVentas($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas";
    if(isset($idUsuario)){
        $sentencia .= " WHERE idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasHoy($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas WHERE DATE(fecha) = CURDATE() ";
    if(isset($idUsuario)){
        $sentencia .= " AND idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasSemana($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas  WHERE WEEK(fecha) = WEEK(NOW())";
    if(isset($idUsuario)){
        $sentencia .= " AND  idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function obtenerTotalVentasMes($idUsuario = null){
    $parametros = [];
    $sentencia = "SELECT IFNULL(SUM(total),0) AS total FROM ventas  WHERE MONTH(fecha) = MONTH(CURRENT_DATE()) AND YEAR(fecha) = YEAR(CURRENT_DATE())";
    if(isset($idUsuario)){
        $sentencia .= " AND  idUsuario = ?";
        array_push($parametros, $idUsuario);
    }
    $fila = select($sentencia, $parametros);
    if($fila) return $fila[0]->total;
}

function calcularTotalVentas($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        $total += $venta->total;
    }
    return $total;
}

function calcularProductosVendidos($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad;
        }
    }
    return $total;
}

function obtenerGananciaVentas($ventas){
    $total = 0;
    foreach ($ventas as $venta) {
        foreach ($venta->productos as $producto) {
            $total += $producto->cantidad * ($producto->precio - $producto->compra);
        }
    }
    return $total;
}

function obtenerVentas($fechaInicio, $fechaFin, $cliente, $usuario){
    $parametros = [];
    $sentencia  = "SELECT ventas.*, usuarios.usuario, IFNULL(clientes.nombre, 'MOSTRADOR') AS cliente
    FROM ventas 
    INNER JOIN usuarios ON usuarios.id = ventas.idUsuario
    LEFT JOIN clientes ON clientes.id = ventas.idCliente";

    if(isset($usuario)){
        $sentencia .= " WHERE ventas.idUsuario = ?";
        array_push($parametros, $usuario);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(isset($cliente)){
        $sentencia .= " WHERE ventas.idCliente = ?";
        array_push($parametros, $cliente);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(empty($fechaInicio) && empty($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) = ? ";
        array_push($parametros, HOY);
        $ventas = select($sentencia, $parametros);
        return agregarProductosVendidos($ventas);
    }

    if(isset($fechaInicio) && isset($fechaFin)){
        $sentencia .= " WHERE DATE(ventas.fecha) >= ? AND DATE(ventas.fecha) <= ?";
        array_push($parametros, $fechaInicio, $fechaFin);
    }

    $ventas = select($sentencia, $parametros);
   
    return agregarProductosVendidos($ventas);
}

function agregarProductosVendidos($ventas){
    foreach($ventas as $venta){
        $venta->productos = obtenerProductosVendidos($venta->id);
    }
    return $ventas;
}

function obtenerProductosVendidos($idVenta){
    $sentencia = "SELECT productos_ventas.cantidad, productos_ventas.precio, productos.nombre,
    productos.compra
    FROM productos_ventas
    INNER JOIN productos ON productos.id = productos_ventas.idProducto
    WHERE idVenta  = ? ";
    return select($sentencia, [$idVenta]);
}

function calcularTotalPedidos($pedidos){
    $total = 0;
    foreach ($pedidos as $pedido) {
        $total += $pedido->montoPedido;
    }
    return $total;
}

function calcularProductosPedidos($pedidos){
    $total = 0;
    foreach ($pedidos as $pedido) {
        foreach ($pedido->productos as $producto) {
            $total += $producto->cantidad;
        }
    }
    return $total;
}

function obtenerPedidos($fechaInicio, $fechaFin, $estado, $suplidor,$nPedido){
    $parametros = [];
    $sentencia  = "SELECT pedidos.*, suplidor.nombreSuplidor, estados.estado
    FROM pedidos 
    INNER JOIN suplidor ON suplidor.id = pedidos.idSuplidor
    LEFT JOIN estados ON estados.idEstado = pedidos.idEstado";

    if(isset($nPedido)){
        $sentencia .= " WHERE pedidos.idPedido = ?";
        array_push($parametros, $nPedido);
        $pedidos = select($sentencia, $parametros);
        return agregarProductosPedido($pedidos);
    }

    if(isset($estado)){
        $sentencia .= " WHERE pedidos.idEstado = ?";
        array_push($parametros, $estado);
        $pedidos = select($sentencia, $parametros);
        return agregarProductosPedido($pedidos);
    }

    if(isset($suplidor)){
        $sentencia .= " WHERE pedidos.idSuplidor = ?";
        array_push($parametros, $suplidor);
        $pedidos = select($sentencia, $parametros);
        return agregarProductosPedido($pedidos);
    }

    if(empty($fechaInicio) && empty($fechaFin)){
        $sentencia .= " WHERE DATE(pedidos.fechaPedido) = ? ";
        array_push($parametros, HOY);
        $pedidos = select($sentencia, $parametros);
        return agregarProductosPedido($pedidos);
    }

    if(isset($fechaInicio) && isset($fechaFin)){
        $sentencia .= " WHERE DATE(pedidos.fechaPedido) >= ? AND DATE(pedidos.fechaPedido) <= ?";
        array_push($parametros, $fechaInicio, $fechaFin);
    }

    $pedidos = select($sentencia, $parametros);
   
    return agregarProductosPedido($pedidos);
}


function agregarProductosPedido($pedidos){
    foreach($pedidos as $pedido){
        $pedido->productos = obtenerProductosPedidos($pedido->idPedido);
    }
    return $pedidos;
}


function obtenerProductosPedidos($idPedido){
    $sentencia = "SELECT articulos_pedidos.cantidad, articulos_pedidos.precioUnitario, productos.nombre,
    productos.compra
    FROM articulos_pedidos
    INNER JOIN productos ON productos.id = articulos_pedidos.idProd
    WHERE idPedido  = ? ";
    return select($sentencia, [$idPedido]);
}

function registrarVenta($productos, $idUsuario, $idCliente, $total){
    $sentencia =  "INSERT INTO ventas (fecha, total, idUsuario, idCliente) VALUES (?,?,?,?)";
    $parametros = [date("Y-m-d H:i:s"), $total, $idUsuario, $idCliente];

    $resultadoVenta = insertar($sentencia, $parametros);
    if($resultadoVenta){
        $idVenta = obtenerUltimoIdVenta();
        $productosRegistrados = registrarProductosVenta($productos, $idVenta);
        //TODO Verificar reorden para pedidos
        ordenesPorSuplidor();
        return $resultadoVenta && $productosRegistrados;
    }
}

function registrarProductosVenta($productos, $idVenta){
    $sentencia = "INSERT INTO productos_ventas (cantidad, precio, idProducto, idVenta) VALUES (?,?,?,?)";
    foreach ($productos as $producto ) {
        $parametros = [$producto->cantidad, $producto->venta, $producto->id, $idVenta];
        insertar($sentencia, $parametros);
        descontarProductos($producto->id, $producto->cantidad);
    }
    return true;
}

function descontarProductos($idProducto, $cantidad){
    $sentencia =  "UPDATE productos SET existencia  = existencia - ? WHERE id = ?";
    $parametros = [$cantidad, $idProducto];
    return editar($sentencia, $parametros);
}

function obtenerUltimoIdVenta(){
    $sentencia  = "SELECT id FROM ventas ORDER BY id DESC LIMIT 1";
    return select($sentencia)[0]->id;
}

function calcularTotalLista($lista){
    $total = 0;
    foreach($lista as $producto){
        $total += floatval($producto->venta * $producto->cantidad);
    }
    return $total;
}

function agregarProductoALista($producto, $listaProductos){
    if($producto->existencia < 1) return $listaProductos;
    $producto->cantidad = 1;
    
    $existe = verificarSiEstaEnLista($producto->id, $listaProductos);

    if(!$existe){
        array_push($listaProductos, $producto);
    } else{
        $existenciaAlcanzada = verificarExistencia($producto->id, $listaProductos, $producto->existencia);
        
        if($existenciaAlcanzada)return $listaProductos;

        $listaProductos = agregarCantidad($producto->id, $listaProductos);
        }
        
    return $listaProductos;
    
}

function verificarExistencia($idProducto, $listaProductos, $existencia){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
           if($existencia <= $producto->cantidad) return true; 
        }
    }
    return false;
}

function verificarSiEstaEnLista($idProducto, $listaProductos){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
            return true;
        }
    }
    return false;
}

function agregarCantidad($idProducto, $listaProductos){
    foreach($listaProductos as $producto){
        if($producto->id == $idProducto){
            $producto->cantidad++;
        }
    }
    return $listaProductos;
}

function obtenerProductoPorCodigo($codigo){
    $sentencia = "SELECT * FROM productos WHERE codigo = ?";
    $producto = select($sentencia, [$codigo]);
    if($producto) return $producto[0];
    return [];
}

function obtenerNumeroProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function obtenerTotalInventario(){
    $sentencia = "SELECT IFNULL(SUM(existencia * venta),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function calcularGananciaProductos(){
    $sentencia = "SELECT IFNULL(SUM(existencia*venta) - SUM(existencia*compra),0) AS total FROM productos";
    $fila = select($sentencia);
    if($fila) return $fila[0]->total;
}

function generarNumeroUnico() {
    $microtime = microtime();
    list($microseconds, $seconds) = explode(' ', $microtime);
    $milliseconds = round($microseconds * 1000);

    $fecha = date("Ymd");
    $diaSemana = date("N"); // 1 (lunes) a 7 (domingo)

    $numeroUnico = sprintf("%s%s%03d", $fecha, $diaSemana, $milliseconds);

    return $numeroUnico;
}


function confirmarPedido($idPedido){
    $sentencia = "update pedidos set idEstado = 2 WHERE idPedido = ?";
    return eliminar($sentencia, $idPedido);
}

function eliminarProducto($id){
    $sentencia = "DELETE FROM productos WHERE id = ?";
    return eliminar($sentencia, $id);
}

function eliminarPrioridad($id){
    $sentencia = "DELETE FROM prioridad_productos WHERE idPrioridad = ?";
    return eliminar($sentencia, $id);
}

function editarProducto($codigo, $nombre, $compra, $venta, $existencia,$cantMin, $cantFija,$idPrioridad , $id){
    $sentencia = "UPDATE productos SET codigo = ?, nombre = ?, compra = ?, venta = ?, existencia = ?, cantMin = ?,cantFija = ?,idPrioridad  = ? WHERE id = ?";
    $parametros = [$codigo, $nombre, $compra, $venta, $existencia, $cantMin, $cantFija,$idPrioridad , $id];
    return editar($sentencia, $parametros);
}

function editarPrioridad($idPrioridad, $prioridad){
    $sentencia = "UPDATE productos SET prioridad = ? WHERE idPrioridad = ?";
    $parametros = [$idPrioridad, $prioridad];
    return editar($sentencia, $parametros);
}

function obtenerProductoPorId($id){
    $sentencia = "SELECT prod.*, pp.prioridad FROM productos prod left join prioridad_productos pp on pp.idPrioridad  = prod.idPrioridad  WHERE prod.id = ?";
    return select($sentencia, [$id])[0];
}

function obtenerPrioridadPorId($id){
    $sentencia = "SELECT * FROM prioridad_productos WHERE idPrioridad = ?";
    return select($sentencia, [$id])[0];
}

// function obtenerPrioridadesSinActiva($id){
//     $sentencia = "SELECT * FROM prioridad_productos WHERE idPrioridad  <> ?";
//     return select($sentencia, [$id])[0];
// }

function obtenerProductos($busqueda = null){
    $parametros = [];
    $sentencia = "SELECT prod.*, pp.prioridad FROM productos prod left join prioridad_productos pp on pp.idPrioridad  = prod.idPrioridad ";
    if(isset($busqueda)){
        $sentencia .= " WHERE nombre LIKE ? OR codigo LIKE ?";
        array_push($parametros, "%".$busqueda."%", "%".$busqueda."%"); 
    } 
    return select($sentencia, $parametros);
}

function obtenerPrioridades($busqueda = null){
    $parametros = [];
    $sentencia = "SELECT * FROM prioridad_productos"; 
    if(isset($busqueda)){
        $sentencia .= " WHERE prioridad LIKE ? OR idPrioridad LIKE ?";
        array_push($parametros, "%".$busqueda."%", "%".$busqueda."%"); 
    } 
    return select($sentencia, $parametros);
}

function obtenerPrioridadesSinActiva($id){
    $parametros = [];
    $sentencia = "SELECT prioridad_productos.*, p.id FROM prioridad_productos
    left join productos p on p.id = '".$id."'
    WHERE p.idPrioridad <> prioridad_productos.idPrioridad"; 
    return select($sentencia, $parametros);
}

function registrarProducto($nombre, $compra, $venta, $existencia, $cantMin, $cantFija, $idPrioridad ){
    $sentencia = "INSERT INTO productos(codigo, nombre, compra, venta, existencia, cantMin, cantFija, idPrioridad ) VALUES (?,?,?,?,?,?,?,?)";
    $parametros = [generarNumeroUnico(), $nombre, $compra, $venta, $existencia, $cantMin, $cantFija, $idPrioridad ];
    return insertar($sentencia, $parametros);
}

//TODO Inicio Pedidos automáticos

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

function obtenerProductosSuplidores($idProd){
    $sentencia = "SELECT ps.idProdTienda, ps.idProdSuplidor, ps.cantDisponible, ps.precioProd, ps.tiempoEntregaProd, ps.idSuplidor, sp.nombreSuplidor, p.nombre, p.idPrioridad
    FROM ventas_php.productos_suplidor ps
    left join productos p on p.id = ps.idProdTienda
    left join suplidor sp on sp.id = ps.idSuplidor
    where ps.idProdTienda = {$idProd}";
    return select($sentencia);
}

function elegirProductos($productosSuplidores, $idPrioridad, $cantPedir){
    $json = json_encode($productosSuplidores);
    $datosProductosSuplidores = json_decode($json, true);

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
        global $OrdenDeProductosPedir;
        $producto_elegido["cantPedir"] = $cantPedir;
        $OrdenDeProductosPedir[] = $producto_elegido;
    }
}

echo "<br>";
$gruposPorSuplidor = array();
$gruposPorPrioridad = array();
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
            if (!isset($gruposPorSuplidor[$idSuplidor])) {
                $gruposPorSuplidor[$idSuplidor] = [];
            }
            $gruposPorSuplidor[$idSuplidor][] = $array;
        } else {
            if (!isset($gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad])) {
                $gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad] = [];
            }
            $gruposPorSuplidor[$idSuplidor.$idProdTienda.$idPrioridad][] = $array;
        }

    }
    return $gruposPorSuplidor;
}


function ordenesPorSuplidor(){
    $datosPedidos = gruposPorSuplidor();

    if($datosPedidos == null){
        return;
    }

    foreach ($datosPedidos as $PS) {
        $totalPedido = 0; 
        $idSuplidor = "";
        foreach ($PS as $porSuplidor) {
            $totalPedido += $porSuplidor["cantPedir"] * $porSuplidor["precioProd"];
            $idSuplidor = $porSuplidor["idSuplidor"];
            $idPrioridad = $porSuplidor["idPrioridad"];
            $nombreSuplidor = $porSuplidor["nombreSuplidor"];
            // echo "<br>";
        }

        $idPedido = registrarPedido($PS, $totalPedido, $idSuplidor);
        // ERROR Activar para usar en producción
        sleep(10);
        enviarCorreo($PS, $totalPedido, $idSuplidor, $nombreSuplidor,$idPedido,$idPrioridad);
    }
}
// TODO Iniciar pedidos
// ordenesPorSuplidor();

function registrarPedido($PS, $totalPedido, $idSuplidor){
    $sentencia =  "INSERT INTO pedidos (fechaPedido, montoPedido, idEstado, idSuplidor) VALUES (?,?,?,?)";
    $parametros = [date("Y-m-d H:i:s"), $totalPedido, '1', $idSuplidor];

    $resultadoPedido = insertar($sentencia, $parametros);
    if($resultadoPedido){
        $idPedido = obtenerUltimoIdPedido();
        $productosRegistrados = registrarProductosPedido($PS, $idPedido);
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

    $mensajePrioridad = "";
    if($idPrioridad == 2){
        $mensajePrioridad = "Estos productos son requeridos lo más pronto posible.";
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
        <p>{$mensajePrioridad}</p>
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

    $mail->Body = $mensaje_email;
    
    // TODO Activar envío de correos
    // $mail->send();

    $mail->clearAddresses();
}




// Fin Pedidos automáticos


function registrarPrioridad($prioridad){
    $sentencia = "INSERT INTO prioridad_productos(prioridad) VALUES (?)";
    $parametros = [$prioridad];
    return insertar($sentencia, $parametros);
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