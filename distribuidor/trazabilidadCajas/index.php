<?php
	@session_start();

	if(!isset($_SESSION['tipo_socio'])){
		header('Location: ../');
	}
	else{
		switch($_SESSION['tipo_socio']) {
			case 1: header('Location: ../../productor/');
					break;
			case 2: header('Location: ../../empaque/');
					break;
			case 4: header('Location: ../../puntoVenta/');
					break;
		}
	}
?>

<!DOCTYPE html>
<html>
	<head>
		<title>Trazabilidad</title>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=0.5">
		<link rel="shortcut icon" href="../../img/logo_trazabilidad.png" type='image/png'>

		<link rel="stylesheet" type="text/css" href="../../lib/bootstrap-3.3.5/css/bootstrap.min.css">
		<!-- <link rel="stylesheet" type="text/css" href="../../lib/bootstrap-3.3.5/css/bootstrap-responsive.min.css" rel="stylesheet"> -->
		<link rel="stylesheet" type="text/css" href="../../css/estilos.css">
	</head>

	<body>
		<?php 
			include('../mod/navbar.php');
		?>
		<div class="contenido-general">
			<div class="modal-header">
        		<h3 class="titulo-header">
        			<?php 
        				$epc = $_GET['epc_caja'];
        			?>
        			Trazabilidad de la Caja <?php echo $epc; ?>
        		</h3>
      		</div>
			<div class="contenido-general-2">
				<!-- <div class="div-contenedor-form"> -->
					<?php 
						include("../../mod/conexion.php");
						
						$consulta = "SELECT id_lote, fecha_recibo_lote, hora_recibo_lote, nombre_productor, ubicacion_huerta_productor, nombre_producto, variedad_producto, nombre_empaque FROM lotes, empresa_productores AS ep, productos, empresa_empaques as ee WHERE lotes.id_productor_fk = ep.id_productor AND lotes.id_producto_fk = productos.id_producto AND lotes.id_empaque_fk = ee.id_empaque AND $epc >= lotes.rango_inicial AND $epc <= lotes.rango_final";

						$result = mysql_query($consulta);

						?>
					<div style="width:1220px; margin:0 auto;">

						<div style="width:600px; background:#FFFFFF; margin-left:10px; float:left;">
								<div class="alert alert-info" role="alert"><strong>Información</strong> del productor</div>
							<?php

							$row = mysql_fetch_array($result);
								?>
									<table class="table">
										<tbody>
											<tr>
												<td>Nombre del Productor</td>
												<td><strong><?php echo $row['nombre_productor']; ?></strong></td>
											</tr>
											<tr>
												<td>Ubicación de la huerta</td>
												<td><strong><?php echo $row['ubicacion_huerta_productor']; ?></strong></td>
											</tr>
											<tr>
												<td>Producto</td>
												<td><strong><?php echo $row['nombre_producto']." ".$row['variedad_producto']; ?></strong></td>
											</tr>
											<tr>
												<td>Número del lote</td>
												<td><strong><?php echo $row['id_lote']; ?></strong></td>
											</tr>
											<tr>
												<td>Fecha y hora de recepción</td>
												<td><strong><?php echo $row['fecha_recibo_lote']." a las ".$row['hora_recibo_lote']; ?></strong></td>
											</tr>
										</tbody>
									</table>

								<?php
							$nombre_empaque = $row['nombre_empaque'];

						 	?>
					 	</div>

					 <?php 
					 	$consulta = "SELECT recibido_dce, epc_tarima, dce.id_orden_fk as id_orden, fecha_envio, hora_envio, nombre_distribuidor, id_envio FROM distribuidor_cajas_envio AS dce, envios_empaque AS en_e, empresa_distribuidores as ed WHERE dce.id_orden_fk = en_e.id_orden_fk AND ed.id_distribuidor = en_e.id_distribuidor_fk AND dce.epc_caja = $epc";
					 	//echo $consulta;
						$result = mysql_query($consulta);
						?>
						<div style="width:600px; background:#FFFFFF; margin-left:10px; float:left;">
								<div class="alert alert-info" role="alert"><strong>Información</strong> del Empaque</div>
							<?php

						$row = mysql_fetch_array($result);
							?>
								<table class="table">
									<tbody>
										<tr>
											<td>Nombre del Empaque</td>
											<td><strong><?php echo $nombre_empaque; ?></strong></td>
										</tr>
										<tr>
											<td>Fecha y hora de envío</td>
											<td><strong><?php echo $row['fecha_envio']." a las ".$row['hora_envio']; ?></strong></td>
										</tr>
										<tr>
											<td>Número del pedido del distribuidor</td>
											<td><strong><?php echo $row['id_orden']; ?></strong></td>
										</tr>
										<tr>
											<td>Número de envío al distribuidor</td>
											<td><strong><?php echo $row['id_envio']; ?></strong></td>
										</tr>
										<tr>
											<td>EPC de la tarima</td>
											<td><strong><?php echo $row['epc_tarima']; ?></strong></td>
										</tr>
									</tbody>
								</table>

							<?php
							$recibido = $row['recibido_dce'];
							$nombre_distribuidor =  $row['nombre_distribuidor'];
						
					 		?>
					 	</div>

					 	<?php 
					 	//$consulta = "SELECT recibido_dce, epc_tarima, dce.id_orden_fk as id_orden, fecha_envio, hora_envio, nombre_distribuidor, id_envio FROM distribuidor_cajas_envio AS dce, envios_empaque AS en_e, empresa_distribuidores as ed WHERE dce.id_orden_fk = en_e.id_orden_fk AND ed.id_distribuidor = en_e.id_distribuidor_fk AND dce.epc_caja = $epc";
					 	//echo $consulta;
						//$result = mysql_query($consulta);

						$c = "SELECT fecha_entrega_orden FROM ordenes_distribuidor WHERE id_orden = ".$row['id_orden'];
						$r = mysql_query($c);

						$fila = mysql_fetch_array($r);

						$fecha_entrega_orden = $fila['fecha_entrega_orden'];

						$c = "SELECT id_orden_fk, recibido_dce, placas_carro, id_envio, fecha_entrega_envio, nombre_punto_venta FROM punto_venta_cajas_envio as pvce, envios_distribuidor as ed, empresa_punto_venta as epv WHERE pvce.id_orden_fk = ed.id_orden_dist_fk AND ed.id_punto_venta_fk = epv.id_punto_venta AND epc_caja = ".$epc;
						//echo $c;
						$r = mysql_query($c);

						$fila = mysql_fetch_array($r);

						?>
						<div style="width:600px; background:#FFFFFF; margin-left:10px; margin-top:10px; float:left;">
								<div class="alert alert-info" role="alert"><strong>Información</strong> del Distribuidor</div>
							<?php

						//while($row = mysql_fetch_array($result)){
							?>
								<table class="table">
									<tbody>
										<tr>
											<td>Nombre del Distribuidor</td>
											<td><strong><?php echo $nombre_distribuidor; ?></strong></td>
										</tr>
										<tr>
											<td>Número del pedido del punto de venta</td>
											<td><strong><?php echo $fila['id_orden_fk']; ?></strong></td>
										</tr>
										<tr>
											<td>Fecha de entrega</td>
											<td>
												<?php 
													if($recibido == 0){
														echo "<strong style='color:#a94442'>Aún no ha llegado la caja</strong>";
													}else{
														echo "<strong>".$fecha_entrega_orden."</strong>";
													}
												?>
											</td>
										</tr>
										<tr>
											<td>Número del envio al punto del venta</td>
											<td><strong><?php echo $fila['id_envio']; ?></strong></td>
										</tr>
										<tr>
											<td>Número de orden al empaque</td>
											<td><strong><?php echo $row['id_orden']; ?></strong></td>
										</tr>
										<tr>
											<td>Placas del carro de envío</td>
											<td><strong><?php echo $fila['placas_carro']; ?></strong></td>
										</tr>
									</tbody>
								</table>

							<?php
							/*$recibido = $row['recibido_dce'];
							$nombre_distribuidor =  $row['nombre_distribuidor'];*/
					//	}
					 		?>
					 	</div>




					 	<div style="width:600px; background:#FFFFFF; margin-left:10px; margin-top:10px; float:left;">
								<div class="alert alert-info" role="alert"><strong>Información</strong> del Punto de venta</div>
							<?php

						//while($row = mysql_fetch_array($result)){
							?>
								<table class="table">
									<tbody>
										<tr>
											<td>Nombre del Punto de venta</td>
											<td><strong><?php echo $fila['nombre_punto_venta']; ?></strong></td>
										</tr>
										<tr>
											<td>Número de la orden al distribuidor</td>
											<td><strong><?php echo $fila['id_orden_fk']; ?></strong></td>
										</tr>
										<tr>
											<td>Fecha de entrega</td>
											<td><?php 
													if($fila['recibido_dce'] == 0){
														echo "<strong style='color:#a94442'>Aún no ha llegado la caja</strong>";
													}else{
														echo "<strong>".$fecha_entrega_orden."</strong>";
													}
												?>
											</td>
										</tr>
									</tbody>
								</table>

							<?php
							/*$recibido = $row['recibido_dce'];
							$nombre_distribuidor =  $row['nombre_distribuidor'];*/
					//	}
					 		?>
					 	</div>









					<?php
						mysql_close($conexion);
					  ?>
					  </div>
				<!-- </div> -->
			</div>
		</div>

		<script type="text/javascript" src="../../lib/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../../lib/bootstrap-3.3.5/js/bootstrap.min.js"></script>
	</body>
</html>