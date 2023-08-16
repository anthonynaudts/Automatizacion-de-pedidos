<?php
include_once "encabezado.php";
include_once "navbar.php";
include_once "funciones.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

$prioridades = obtenerPrioridades();

?>
<div class="container">
    <h3>Agregar producto</h3>
    <form method="post">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código de barras</label>
            <input type="text" name="codigo" class="form-control" id="codigo" placeholder="Escribe el código de barras del producto">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre o descripción</label>
            <input type="text" name="nombre" class="form-control" id="nombre" placeholder="Red de tenis">
        </div>
        <div class="row mb-3">
            <div class="col">
                <label for="compra" class="form-label">Precio compra</label>
                <input type="number" name="compra" step="any" id="compra" class="form-control" placeholder="Precio de compra" aria-label="">
            </div>
            <div class="col">
                <label for="venta" class="form-label">Precio venta</label>
                <input type="number" name="venta" step="any" id="venta" class="form-control" placeholder="Precio de venta" aria-label="">
            </div>
            <div class="col">
                <label for="existencia" class="form-label">Existencia</label>
                <input type="number" name="existencia" step="any" id="existencia" class="form-control" placeholder="Existencia" aria-label="">
            </div>
            
        </div>

        <div class="row">
            <div class="col">
                <label for="cantMin" class="form-label">Cantidad mínima almacén</label>
                <input type="number" name="cantMin" step="any" id="cantMin" class="form-control" placeholder="Cantidad mínima" aria-label="">
            </div>
            <div class="col">
                <label for="cantFija" class="form-label">Cantidad fija almacén</label>
                <input type="number" name="cantFija" step="any" id="cantFija" class="form-control" placeholder="Cantidad fija" aria-label="">
            </div>
            <div class="col">
                <label for="cantFija" class="form-label">Prioridad compra</label>
                <select name="idPrioridad" id="idPrioridad" class="form-control">
                    <option value="">--Seleccionar prioridad</option>
                    <?php
                        foreach($prioridades as $prioridad){
                    ?>
                    <option value="<?= $prioridad->idPrioridad; ?>"><?= $prioridad->prioridad; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a class="btn btn-danger btn-lg" href="productos.php">
                <i class="fa fa-times"></i> 
                Cancelar
            </a>
        </div>
    </form>
</div>
<?php
if(isset($_POST['registrar'])){
    $codigo = $_POST['codigo'];
    $nombre = $_POST['nombre'];
    $compra = $_POST['compra'];
    $venta = $_POST['venta'];
    $existencia = $_POST['existencia'];
    $cantMin = $_POST['cantMin'];
    $cantFija = $_POST['cantFija'];
    $idPrioridad = $_POST['idPrioridad'];
    if(empty($codigo) 
    || empty($nombre) 
    || empty($compra) 
    || empty($venta)
    || empty($existencia)
    || empty($cantMin)
    || empty($cantFija)
    || empty($idPrioridad)){
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
    $resultado = registrarProducto($codigo, $nombre, $compra, $venta, $existencia, $cantMin, $cantFija,$idPrioridad);
    if($resultado){
        echo"
        <script>
            Swal.fire(
                'Producto registrado!',
                'El producto ha sido registrada con éxito!',
                'success'
            )
        </script>
        ";
    }
    
}
?>