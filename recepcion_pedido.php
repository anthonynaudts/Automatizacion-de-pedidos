<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

$idPedido = $_GET['idPedido'];
if (!$idPedido) {
    echo 'No se ha seleccionado el cliente';
    exit;
}
include_once "funciones.php";


if(isset($_POST['buscarPorPedido'])){
    if(empty($_POST['nPedido'])) header("location: recepcion_pedido.php");
}

// $nPedido = (isset($_POST['nPedido'])) ? $_POST['nPedido'] : null;

$pedidos = obtenerPedidos(null, null, null, null, $idPedido);


?>
<div class="container">
    <h2 class="mb-3">Recepción de pedidos</h2>
        <!-- <div class="col">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row mb-1">
                    <div class="input-group mb-3 w-50">
                        <span class="input-group-text fw-bold" id="basic-addon1">Número pedido</span>
                        <input type="text" class="form-control" name="nPedido" placeholder="1" aria-label="1" aria-describedby="basic-addon1">
                        <input class="btn btn-warning" type="submit" name="buscarPorPedido" id="buscarPorPedido" value="Buscar pedido">
                    </div>
                </form>
    </div> -->

    <?php if(count($pedidos) > 0){?>


        <table class="table mb-5 w-50">
            <thead>
                <?php foreach($pedidos as $pedido) {?>
                <tr>
                    <td><span class="fw-bold"># Pedido:</span> <?= $pedido->idPedido;?></td>
                    <td><span class="fw-bold">Suplidor:</span> <?= $pedido->nombreSuplidor;?></td>
                </tr>
                <tr>
                    <td><span class="fw-bold">fecha pedido:</span> <?= date_format(new DateTime($pedido->fechaPedido),'d/m/Y');?></td>
                    <td><span class="fw-bold">Estado:</span> <?= $pedido->estado;?></td>
                </tr>
                <?php }?>
            </thead>
        </table>
        
    <table class="table">
        <thead>
            <tr>
                <th>Nombre producto</th>
                <th class="text-center">Cantidad</th>
                <!-- <th>Precio</th> -->
                <!-- <th>Suplidor</th>
                <th>Monto pedido</th>
                <th>Estado</th>
                <th>Productos</th> -->
            </tr>
        </thead>
        <tbody>
            <!-- <?php foreach($pedidos as $pedido) {?>
                <tr>
                    <td><?= $pedido->idPedido;?></td>
                    <td><?=date_format(new DateTime($pedido->fechaPedido),'d/m/Y');?></td>
                    <td><?= $pedido->fechaRecepcion;?></td>
                    <td><?= $pedido->nombreSuplidor;?></td>
                    <td>$<?= number_format($pedido->montoPedido,2);?></td>
                    <td><?= $pedido->estado;?></td>
                    <td> -->
            <?php foreach($pedido->productos as $producto) {?>
                <tr>
                <td><?= $producto->nombre;?></td>
                <td class="text-center"><?= $producto->cantidad;?></td>
                </tr>
            <?php }?>
            <!-- <?php }?> -->
        </tbody>
    </table>
    <div class="mt-5 text-center">
        // ERROR Configurar boton para cambiar estado
        <a href="#" style='text-decoration: none;padding: 6px 10px; background-color: #fe7112; color:white; font-weight:bold; border-radius:3px; font-size: 20px;'>¡Recibir pedido!</a>
    </div>
    <?php }?>
    <?php if(count($pedidos) < 1){?>
        <div class="alert alert-warning mt-3 text-center" role="alert">
            <h1>No se han encontrado pedidos</h1>
        </div>
    <?php }?>
</div>