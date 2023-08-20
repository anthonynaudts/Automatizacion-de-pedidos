<?php
$idPedido = $_GET['idPedido'];
if (!$idPedido) {
    echo 'No se ha seleccionado el pedido';
    exit;
}
include_once "funciones.php";

$resultado = confirmarPedido($idPedido);
if(!$resultado){
    echo "Error al eliminar";
    return;
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="img/Sport_Zone_stacked.ico">
    <title>Confirmación</title>
    <style>
        *{
            font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        h1{
            /* color: #fe7112; */
            color: #383838;
        }
        h3{
            margin-top: 0;
            font-weight: bold;
            text-decoration: underline;
            color: #383838;
        }
    </style>
</head>
<body style ="height: 90vh; display:flex; flex-direction:column; justify-content:center; align-items:center;">
    <img src="img/Sport_Zone_stacked.svg" alt="" width="250" height="100" class="d-inline-block align-text-top">
    <h1>¡Gracias por recibir nuestro pedido!</h1>
    <!-- <h3>Número de pedido: <?php echo $idPedido; ?></h3> -->
</body>
</html>