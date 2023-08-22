<?php
session_start();
include_once "encabezado.php";
include_once "navbar.php";

if(empty($_SESSION['usuario'])) header("location: login.php");

$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado la prioridad';
    exit;
}
include_once "funciones.php";
$prioridades = obtenerPrioridadPorId($id);
// $prioridades = obtenerPrioridadesSinActiva($id);
?>

<div class="container">
    <h3>Editar producto</h3>
    <form method="post">
        <div class="mb-3">
            <label for="prioridad" class="form-label">Nombre o descripción</label>
            <input type="text" name="prioridad" class="form-control" value="<?php echo $prioridades->prioridad;?>" id="prioridad" placeholder="Mayor costo">
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a href="prioridades.php" class="btn btn-danger btn-lg">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php
if(isset($_POST['registrar'])){
    $idPrioridad = $_POST['idPrioridad'];
    $prioridad = $_POST['prioridad'];
    if(empty($idPrioridad) 
    || empty($prioridad)){
        echo"
        <script>
            Swal.fire(
                'Campos vacíos',
                'Debe llenar todos los campos!',
                'warning'
            )
        </script>";
        return;
    } 
    
    include_once "funciones.php";
    $resultado = editarPrioridad($idPrioridad, $prioridad);
    if($resultado){
        echo"
        <script>
            Swal.fire(
                'Producto actualizado!',
                'Información del producto registrada con éxito!',
                'success'
            )
        </script>
        ";

        // echo"
        // <div class='alert alert-success mt-3' role='alert'>
        //     Información del producto registrada con éxito.
        // </div>";
    }
    
}
?>