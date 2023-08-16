<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");
$nombrePrioridad = (isset($_POST['prioridad'])) ? $_POST['prioridad'] : null;

$prioridades = obtenerPrioridades($nombrePrioridad);

?>
<div class="container mt-3">
    <h1>
        <a class="btn btn-success btn-lg" href="agregar_prioridad.php">
            <i class="fa fa-plus"></i>
            Agregar
        </a>
        Prioridades
    </h1>

    <form action="" method="post" class="input-group mb-3 mt-3">
        <input autofocus name="prioridad" type="text" class="form-control" placeholder="Escribe la prioridad o código que deseas buscar" aria-label="Nombre prioridad" aria-describedby="button-addon2">
        <button type="submit" name="buscarPrioridad" class="btn btn-primary" id="button-addon2">
            <i class="fa fa-search"></i>
            Buscar
        </button>
    </form>
    <table class="table">
        <thead>
            <tr>
                <th>Código</th>
                <th>Prioridad</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach($prioridades as $element_prioridad){
            ?>
                <tr>
                    <td><?= $element_prioridad->idPrioridad; ?></td>
                    <td><?= $element_prioridad->prioridad; ?></td>
                    <td>
                        <a class="btn btn-warning" href="editar_prioridad.php?id=<?= $element_prioridad->idPrioridad; ?>">
                            <i class="fa fa-edit"></i>
                            Editar
                        </a>
                    </td>
                    <td>
                        <!-- <a class="btn btn-danger" href="eliminar_producto.php?id=<?= $element_prioridad->idPrioridad; ?>">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </a> -->
                        <a class="btn btn-danger" onclick="eliminarPrioridad(<?= $element_prioridad->idPrioridad; ?>)">
                            <i class="fa fa-trash"></i>
                            Eliminar
                        </a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
