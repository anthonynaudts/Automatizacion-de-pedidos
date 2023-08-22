<?php
session_start();
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";

if(empty($_SESSION['usuario'])) header("location: login.php");

$idPedido = $_GET['idPedido'];
if (!$idPedido) {
    echo 'No se ha seleccionado el cliente';
    exit;
}
// include_once "funciones.php";

if(isset($_POST['buscar'])){
    if(empty($_POST['inicio']) || empty($_POST['fin'])) header("location: pedidos.php");
}

$pedidos = obtenerPedidos(null, null, null, null, $idPedido);
$pedidoRecibido = false;
foreach($pedidos as $pedido) {
    if($pedido->estado == "Pedido recibido"){
        $pedidoRecibido = true;
    }
}
if(isset($_POST['recibirPedido'])){
    
}
echo $pedido->idPedido;
print_r($pedido->productos);

// echo( $_SERVER["HTTP_REFERER"]);
?>
<div class="container">
    <h2 class="mb-3">Recepción de pedido</h2>
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
            <?php }
            print_r($pedido->productos);
            ?>
            <!-- <?php }?> -->
        </tbody>
    </table>
    <div class="mt-5 text-center">
        <form action="<?php echo $_SERVER["HTTP_REFERER"]; ?>" method="post" class="row mb-1">
            <div class="w-100">
                <?php
                    if($pedidoRecibido){
                        ?>
                        <p class="fw-bold text-danger" style="font-size:20px;">Este pedido ha sido recibido anteriormente.</p>
                        <?php
                    }else{
                        ?>
                        <input class="btn" style='padding: 6px 10px; background-color: #fe7112; color:white; font-weight:bold; border-radius:3px; font-size: 18px;' type="submit" name="recibirPedido" id="recibirPedido" value="¡Recibir pedido!">
                        <?php
                    }
                ?>
            </div>
        </form>
    </div>
    <?php }?>
    <?php if(count($pedidos) < 1){?>
        <div class="alert alert-warning mt-3 text-center" role="alert">
            <h1>No se han encontrado pedidos</h1>
        </div>
    <?php }?>
</div>