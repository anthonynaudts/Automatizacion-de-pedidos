<?php
include_once "encabezado.php";
include_once "navbar.php";
session_start();

if(empty($_SESSION['usuario'])) header("location: login.php");

$id = $_GET['id'];
if (!$id) {
    echo 'No se ha seleccionado el producto';
    exit;
}
include_once "funciones.php";
$producto = obtenerProductoPorId($id);
$prioridades = obtenerPrioridadesSinActiva($id);
?>

<div class="container">
    <h3>Editar producto</h3>
    <form method="post">
        <div class="mb-3">
            <label for="codigo" class="form-label">Código de barras</label>
            <input type="text" name="codigo" class="form-control" value="<?php echo $producto->codigo;?>" id="codigo" placeholder="Escribe el código de barras del producto">
        </div>
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre o descripción</label>
            <input type="text" name="nombre" class="form-control" value="<?php echo $producto->nombre;?>" id="nombre" placeholder="Ej. Papas">
        </div>
        <div class="row">
            <div class="col">
                <label for="compra" class="form-label">Precio compra</label>
                <input type="number" name="compra" step="any" value="<?php echo $producto->compra;?>" id="compra" class="form-control" placeholder="Precio de compra" aria-label="">
            </div>
            <div class="col">
                <label for="venta" class="form-label">Precio venta</label>
                <input type="number" name="venta" step="any" value="<?php echo $producto->venta;?>" id="venta" class="form-control" placeholder="Precio de venta" aria-label="">
            </div>
            <div class="col">
                <label for="existencia" class="form-label">Existencia</label>
                <input type="number" name="existencia" step="any" value="<?php echo $producto->existencia;?>" id="existencia" class="form-control" placeholder="Existencia" aria-label="">
            </div>
            
        </div>

        <div class="row mt-3">
            <div class="col">
                <label for="cant_min" class="form-label">Cantidad mínima almacén</label>
                <input type="number" name="cant_min" step="any" value="<?php echo $producto->cant_min;?>" id="cant_min" class="form-control" placeholder="cant_min" aria-label="">
            </div>
            <div class="col">
                <label for="cant_fija" class="form-label">Cantidad fija almacén</label>
                <input type="number" name="cant_fija" step="any" value="<?php echo $producto->cant_fija;?>" id="cant_fija" class="form-control" placeholder="cant_fija" aria-label="">
            </div>
            <div class="col">
                <label for="id_prioridad" class="form-label">Prioridad compra (Dias máximos para recibir)</label>
                <!-- <input type="number" name="id_prioridad" step="any" value="<?php echo $producto->id_prioridad;?>" id="id_prioridad" class="form-control" placeholder="id_prioridad" aria-label=""> -->

                <select name="id_prioridad" id="id_prioridad" class="form-control" >
                    <option value="<?php echo $producto->id_prioridad;?>"><?php echo $producto->prioridad;?> (Días Máximos: <?php echo $producto->tiempo_llegada_dias; ?>)</option>
                    <?php
                        foreach($prioridades as $prioridad){
                    ?>
                        <option value="<?php echo $prioridad->id_prioridad; ?>"><?php echo $prioridad->prioridad; ?> (Días Máximos: <?php echo $prioridad->tiempo_llegada_dias; ?>)</option>
                    <?php
                        }
                    ?>
                </select>
            </div>
        </div>

        <div class="text-center mt-3">
            <input type="submit" name="registrar" value="Registrar" class="btn btn-primary btn-lg">
            
            </input>
            <a href="productos.php" class="btn btn-danger btn-lg">
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
    $cant_min = $_POST['cant_min'];
    $cant_fija = $_POST['cant_fija'];
    $id_prioridad = $_POST['id_prioridad'];
    if(empty($codigo) 
    || empty($nombre) 
    || empty($compra) 
    || empty($venta)
    || empty($existencia
    || empty($cant_min)
    || empty($cant_fija)
    || empty($id_prioridad))){
        echo'
        <div class="alert alert-danger mt-3" role="alert">
            Debes completar todos los datos.
        </div>';
        return;
    } 
    
    include_once "funciones.php";
    $resultado = editarProducto($codigo, $nombre, $compra, $venta, $existencia,$cant_min, $cant_fija,$id_prioridad, $id);
    if($resultado){
        echo'
        <div class="alert alert-success mt-3" role="alert">
            Información del producto registrada con éxito.
        </div>';
    }
    
}
?>