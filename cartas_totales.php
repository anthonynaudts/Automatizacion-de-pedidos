<?php
include_once "encabezado.php";
?>


	<div class="card-deck row d-flex justify-content-center">
		<?php foreach($cartas as $carta){?>
		<div class="col-xs-12 col-sm-6 col-md-3" style="color: <?=  $carta['color']?> !important">
			<div class="card text-center">
				<div class="card-body">
					<h4 class="card-title" >
						<i class="fa <?= $carta['icono']?>"></i>
						<?= $carta['titulo']?>
					</h4>
					<h2><?= number_format($carta['total'], 2)?></h2>

				</div>

			</div>
		</div>
		<?php }?>
	</div>
