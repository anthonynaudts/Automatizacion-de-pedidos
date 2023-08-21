<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();
if(empty($_SESSION['usuario'])) header("location: login.php");

if(isset($_POST['buscarPorSuplidor'])){
    if(empty($_POST['idSuplidor'])) header("location: pedidos_pendientes.php");
}

$suplidor = (isset($_POST['idSuplidor'])) ? $_POST['idSuplidor'] : null;

$pedidos = obtenerPedidosPendientes($suplidor);

$suplidores = obtenerSuplidor();
?>

<div class="container">
<h2 class="mb-3">Pedidos pendientes de recibir</h2>
<div class="col">
<div class="col d-flex justify-content-left">
    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row mb-3 w-50">
        <div class="input-group mb13">
            <span class="input-group-text fw-bold">Suplidor</span>
            <select class="form-select" id="idSuplidor" name="idSuplidor">
                <option selected value="">Todos los suplidores</option>
                <?php foreach($suplidores as $sup) {?>
                    <option value="<?= $sup->id?>"><?= $sup->nombreSuplidor?></option>
                <?php }?>
            </select>
        <input class="btn btn-primary" type="submit" name="buscarPorSuplidor" value="Buscar por suplidor">                 
        </div>
    </form>
</div>
</div>

    <?php if(count($pedidos) > 0){?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha pedido</th>
                <!-- <th>Fecha recepción</th> -->
                <th>Suplidor</th>
                <th>Monto pedido</th>
                <th>Estado</th>
                <th class="text-center">Acción</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pedidos as $pedido) {?>
                <tr>
                    <td><?= $pedido->idPedido;?></td>
                    <td><?=date_format(new DateTime($pedido->fechaPedido),'d/m/Y');?></td>
                    <!-- <td><?= $pedido->fechaRecepcion;?></td> -->
                    <td><?= $pedido->nombreSuplidor;?></td>
                    <td><?= number_format($pedido->montoPedido,2);?></td>
                    <td><?= $pedido->estado;?></td>
                    <td class="text-center">
                        <a href="http://localhost/recepcion_pedido.php?idPedido=<?= $pedido->idPedido;?>" style='text-decoration: none;padding: 3px 6px; background-color: #fe7112; color:white; font-weight:bold; border-radius:3px;'>¡Recibir pedido!</a>
                    </td>
                </tr>
            <?php }?>
        </tbody>
    </table>
    <?php }?>
    <?php if(count($pedidos) < 1){?>
        <div class="alert alert-warning mt-3 text-center" role="alert">
            <h1>No se han encontrado pedidos</h1>
        </div>
    <?php }?>
</div>