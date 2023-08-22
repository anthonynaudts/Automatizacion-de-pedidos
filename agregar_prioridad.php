<?php
session_start();
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";


if(empty($_SESSION['usuario'])) header("location: login.php");

$prioridades = obtenerPrioridades();

?>
<div class="container">
    <h3>Agregar prioridad</h3>
    <form method="post">
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre o descripción</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Mayor costo">
        </div>
        
        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a class="btn btn-danger btn-lg" href="prioridades.php">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php
if(isset($_POST['registrar'])){
    $prioridad = $_POST['prioridad'];
    
    if(empty($prioridad) ){
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
    $resultado = registrarPrioridad($prioridad);
    if($resultado){
        echo"
        <script>
            Swal.fire(
                'Prioridad registrado!',
                'La prioridad ha sido registrada con éxito!',
                'success'
            )
        </script>
        ";
    }
    
}
?>