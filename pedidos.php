<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();
if(empty($_SESSION['usuario'])) header("location: login.php");

if(isset($_POST['buscar'])){
    if(empty($_POST['inicio']) || empty($_POST['fin'])) header("location: pedidos.php");
}

if(isset($_POST['buscarPorEstado'])){
    if(empty($_POST['idEstado'])) header("location: pedidos.php");
}

if(isset($_POST['buscarPorSuplidor'])){
    if(empty($_POST['idSuplidor'])) header("location: pedidos.php");
}

if(isset($_POST['buscarPorPedido'])){
    if(empty($_POST['nPedido'])) header("location: pedidos.php");
}

$fechaInicio = (isset($_POST['inicio'])) ? $_POST['inicio'] : null;
$fechaFin = (isset($_POST['fin'])) ? $_POST['fin'] : null;
$estado = (isset($_POST['idEstado'])) ? $_POST['idEstado'] : null;
$suplidor = (isset($_POST['idSuplidor'])) ? $_POST['idSuplidor'] : null;
$nPedido = (isset($_POST['nPedido'])) ? $_POST['nPedido'] : null;

$pedidos = obtenerPedidos($fechaInicio, $fechaFin, $estado, $suplidor, $nPedido);

$cartas = [
    ["titulo" => "No. Pedidos", "icono" => "fa fa-shopping-cart", "total" => count($pedidos), "color" => "#A71D45"],
    ["titulo" => "Total invertido", "icono" => "fa fa-money-bill", "total" => "$".calcularTotalPedidos($pedidos), "color" => "#2A8D22"],
    ["titulo" => "Productos pedidos", "icono" => "fa fa-box", "total" =>calcularProductosPedidos($pedidos), "color" => "#223D8D"],
];

$suplidores = obtenerSuplidor();
$estados = obtenerEstados();
?>
<div class="container">
    <h2>Pedidos: 
        <?php 
        if(empty($fechaInicio)) echo HOY;
        if(isset($fechaInicio) && isset($fechaFin)) echo $fechaInicio ." al ". $fechaFin;
        ?>
    </h2>
        <div class="col">
                <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row mb-1">
                    <div class="input-group mb-3 w-50">
                        <span class="input-group-text fw-bold" id="basic-addon1">Número pedido</span>
                        <input type="text" class="form-control" name="nPedido" placeholder="1" aria-label="1" aria-describedby="basic-addon1">
                        <input class="btn btn-warning" type="submit" name="buscarPorPedido" id="buscarPorPedido" value="Buscar pedido">
                    </div>
                </form>
                <div class="col d-flex justify-content-left">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row w-50 me-2">
                        <div class="input-group mb-3">
                            <span class="input-group-text fw-bold" id="idEstado">Estado pedido</span>
                            <select class="form-select" id="idEstado" name="idEstado">
                                <option selected value="">--Selecciona un estado</option>
                                    <?php foreach($estados as $estado) {?>
                                    <option value="<?= $estado->idEstado?>"><?= $estado->estado?></option>
                                    <?php }?>
                            </select>
                            <input class="btn btn-primary" type="submit" name="buscarPorEstado" id="buscarPorEstado" value="Buscar por estado">              
                        </div>
                    </form>
                    <!-- &nbsp; -->
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="row mb-3 w-50">
                        <div class="input-group mb13">
                            <span class="input-group-text fw-bold">Suplidor</span>
                            <select class="form-select" id="idSuplidor" name="idSuplidor">
                                <option selected value="">--Selecciona un suplidor</option>
                                    <?php foreach($suplidores as $sup) {?>
                                    <option value="<?= $sup->id?>"><?= $sup->nombreSuplidor?></option>
                                    <?php }?>
                            </select>
                            <input class="btn btn-primary" type="submit" name="buscarPorSuplidor" value="Buscar por suplidor">                 
                        </div>
                    </form>
            </div>
        <!-- </div> -->

        <div class="col">
            <form class="row mb-3" method="post">
                <h5>Busqueda por fechas</h5>
                <div class="col-3 input-group mb-3 w-50">
                    <span class="input-group-text fw-bold" id="basic-addon1">Fecha inicial</span>
                    <input type="date" class="form-control" name="inicio" placeholder="1" aria-label="1" aria-describedby="basic-addon1">
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <span class="input-group-text fw-bold" id="basic-addon1">Fecha final</span>
                    <input type="date" class="form-control" name="fin" placeholder="1" aria-label="1" aria-describedby="basic-addon1">
                    <input class="btn btn-primary" type="submit" name="buscar" id="buscar" value="Buscar fechas">
                </div>
            

            <!-- <div class="col-5">
                <label for="inicio" class="form-label">Fecha busqueda inicial</label>
                <input type="date" name="inicio" class="form-control" id="inicio" >
            </div>
            <div class="col-5">
                <label for="fin" class="form-label">Fecha busqueda final</label>
                <input type="date" name="fin" class="form-control" id="fin" >
            </div>
            <div class="col">
                <input type="submit" name="buscar" value="Buscar" class="btn btn-primary mt-4">
            </div> -->
            </form>
        </div>

    <!-- <form class="row mb-3" method="post">
        <div class="col-5">
            <label for="inicio" class="form-label">Fecha busqueda inicial</label>
            <input type="date" name="inicio" class="form-control" id="inicio" >
        </div>
        <div class="col-5">
            <label for="fin" class="form-label">Fecha busqueda final</label>
            <input type="date" name="fin" class="form-control" id="fin" >
        </div>
        <div class="col">
            <input type="submit" name="buscar" value="Buscar" class="btn btn-primary mt-4">
        </div>
    </form> -->
    <!-- <div class="row mb-2">
        <div class="col">
            <form action="" method="post" class="row">
                <div class="col-6">
                <select class="form-select" aria-label="Default select example" name="idEstado">
                    <option selected value="">Selecciona un estado</option>
                    <?php foreach($estados as $estado) {?>
                    <option value="<?= $estado->idEstado?>"><?= $estado->estado?></option>
                    <?php }?>
                </select>
                </div>
                <div class="col-1">
                    <input type="submit" name="buscarPorEstado" value="Buscar por estado de pedido" class="btn btn-secondary">
                </div>
            </form>
        </div> -->
        <!-- <div class="col">
            <form action="" method="post" class="row">
                <div class="col-6">
                <select class="form-select" aria-label="Default select example" name="idSuplidor">
                    <option selected value="">Selecciona un suplidor</option>
                    <?php foreach($suplidor as $sup) {?>
                    <option value="<?= $sup->id?>"><?= $sup->nombreSuplidor?></option>
                    <?php }?>
                </select>
                </div>
                <div class="col-1">
                    <input type="submit" name="buscarPorSuplidor" value="Buscar por suplidor" class="btn btn-secondary">
                </div>
            </form>
        </div> -->
    </div>

    <?php include_once "cartas_totales.php"?>
    <?php if(count($pedidos) > 0){?>
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Fecha pedido</th>
                <th>Fecha recepción</th>
                <th>Suplidor</th>
                <th>Monto pedido</th>
                <th>Estado</th>
                <th>Productos</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($pedidos as $pedido) {?>
                <tr>
                    <td><?= $pedido->idPedido;?></td>
                    <td><?= $pedido->fechaPedido;?></td>
                    <td><?= $pedido->fechaRecepcion;?></td>
                    <td><?= $pedido->nombreSuplidor;?></td>
                    <td>$<?= $pedido->montoPedido;?></td>
                    <td><?= $pedido->estado;?></td>
                    <td>
                        <table class="table">
                            <?php foreach($pedido->productos as $producto) {?>
                                <tr>
                                    <td><?= $producto->nombre;?></td>
                                    <td><?= $producto->cantidad;?></td>
                                    <td> X </td>
                                    <td>$<?=  $producto->precioUnitario ;?></td>
                                    <th>$<?= $producto->cantidad * $producto->precioUnitario ;?></th>
                                </tr>
                                <?php }?>
                        </table>
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