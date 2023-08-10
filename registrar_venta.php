<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    
</body>
</html>
<?php
include_once "funciones.php";


session_start();
$productos = $_SESSION['lista'];
$idUsuario = $_SESSION['idUsuario'];
$total = calcularTotalLista($productos);
$idCliente = $_SESSION['clienteVenta'];

if(count($productos) === 0) {
    header("location: vender.php");
    return;
};
$resultado = registrarVenta($productos, $idUsuario, $idCliente, $total);

if(!$resultado) {
    echo "Error al registrar la venta";
    return;
}

$_SESSION['lista'] = [];
$_SESSION['clienteVenta'] = "";


echo"
    <script>
        Swal.fire({
            title: 'Venta realizada!',
            text: 'La venta se ha realizado sastifactoriamente!',
            icon: 'success',
            showCancelButton: false,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'vender.php'
            }
        })
    </script>";
// echo "
// <script type='text/javascript'>
//     window.location.href='vender.php'
//     alert('Venta realizada con éxito')
// </script>";
//header("location: vender.php");

?>