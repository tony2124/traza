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
		<link rel='stylesheet' type='text/css' href='../../lib/pagination/css.css'/>
		<link rel="stylesheet" type="text/css" href="../../css/estilos.css">
	</head>

	<body>
		<?php 
			include('../mod/navbar.php');

			if($privPedidos == 0)
				header('Location: ../');
		?>
		<div class="contenido-general">
			<div class="modal-header">
				<h3 class="titulo-header">
					<h3 class="titulo-contenido">
						<img class="img-header" src="../../img/historial.png"> <span id="lbl-titulo">Historial de Pedidos</span>
						<!-- <button class="btn btn-default" id="btnReportes" onclick="generacionReportes();" data-toggle="tooltip" title="Generación e impresión de reportes"><i class="glyphicon glyphicon-print"></i> </button> -->
					</h3>
				</h3>
			</div>
			<br>
			<div class="div-buscar">
				<div class="form-inline">
					<input type="text" class="form-control" name="inputBuscar" id="inputBuscar" placeholder="Buscar por nombre del punto de venta..." onkeyup="if(event.keyCode == 13) buscarPedidos();" autofocus>
					<button class="btn btn-primary" id="btnBuscar" onclick="buscarPedidos();"><i class="glyphicon glyphicon-search"></i> Buscar</button>
					<button class="btn btn-success" id="btnAvanzada" data-toggle="modal" data-target="#modalBusquedaAvanzada"><i class="glyphicon glyphicon-search"></i> Búsqueda Avanzada</button>
					<a href="../pedidos/" class="btn btn-info" id="btnMostrarTodos"><i class="glyphicon glyphicon-th-list"></i> Mostrar Todos</a>
				</div>
			</div>
			<div class="contenido-general-2">
				<br>
				<div id="paginacion-resultados">
					<table class="table">
						<thead>
							<tr>
								<th class="centro">ID</th>
								<th>Punto de Venta</th>
								<th class="centro">Fecha Orden</th>
								<th class="centro">Fecha Entrega</th>
								<th class="derecha">Total</th>
								<th class="centro">Estado</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							<?php
								include('../../mod/conexion.php');

								$consulta = "SELECT id_distribuidor_fk FROM usuario_distribuidor WHERE id_usuario_fk = ".$_SESSION['id_usuario'];
								$resultado = mysql_query($consulta);
								$row = mysql_fetch_array($resultado);
								$id_distribuidor_fk = $row['id_distribuidor_fk'];

								$cont = 0;
							    $consulta = "SELECT ordspv.id_orden, mpsapv.id_punto_venta, mpsapv.nombre_punto_venta, ordspv.fecha_orden, ordspv.fecha_entrega_orden, ordspv.costo_orden, ordspv.estado_orden FROM ordenes_punto_venta AS ordspv, usuario_punto_venta AS ususpv, empresa_punto_venta AS mpsapv WHERE ordspv.id_usuario_punto_venta_fk = ususpv.id_usuario_pv AND ususpv.id_punto_venta_fk = mpsapv.id_punto_venta AND ordspv.id_distribuidor_fk = $id_distribuidor_fk ORDER BY ordspv.id_orden DESC";
								$resultado = mysql_query($consulta);
								while($row = mysql_fetch_array($resultado)){ ?>
									<tr>
						          		<td class="centro"><?php echo $row['id_orden']; ?></td>
						          		<td>
						          			<?php 
						          				$idPuntoVenta = $row['id_punto_venta'];

						          				$consulta2 = "SELECT * FROM empresa_punto_venta WHERE id_punto_venta = $idPuntoVenta";
						          				$resultado2 = mysql_query($consulta2);
						          				$row2 = mysql_fetch_array($resultado2);
						          			?>
						          			<a href="#" class="popover-punto-venta" 
						          				tabindex="0"
						          				data-toggle="popover"
						          				data-placement="right"
						          				data-trigger="focus"
						          				data-container="body"
						          				data-html="true"
						          				title="<center><strong><?php echo $row2['nombre_punto_venta']; ?></strong></center>"
						          				data-content="<table class='table'>
						          								<tr>
						          									<td><strong>RFC: </strong></td>
						          									<td><?php echo $row2['rfc_punto_venta']; ?></td>
						          								</tr>
						          								<tr>
						          									<td><strong>Ciudad: </strong></td>
						          									<td><?php echo $row2['ciudad_punto_venta']; ?></td>
						          								</tr>
						          								<tr>
						          									<td><strong>Dirección: </strong></td>
						          									<td><?php echo $row2['direccion_punto_venta']; ?></td>
						          								</tr>
						          								<tr>
						          									<td><strong>Teléfono: </strong></td>
						          									<td><?php echo $row2['telefono_punto_venta']; ?></td>
						          								</tr>
						          								<tr>
						          									<td><strong>Email: </strong></td>
						          									<td><?php echo $row2['email_punto_venta']; ?></td>
						          								</tr>
						          							  <table>">
						          				<?php echo $row['nombre_punto_venta']; ?>
						          			</a>
						          		</td>
						          		<!-- <td><?php echo $row['nombre_punto_venta']; ?></td> -->
						          		<td class="centro"><?php echo date('d/m/Y', strtotime($row['fecha_orden'])); ?></td>
						          		<td class="centro"><?php echo date('d/m/Y', strtotime($row['fecha_entrega_orden'])); ?></td>
						          		<td class="derecha"><?php echo "$ ".number_format($row['costo_orden'], 2, '.', ','); ?></td>
						          		<?php 
						          			$estado = $row['estado_orden'];
						          		?>
						          		<?php
					          				switch($estado) {
					          					case '1': echo "<td class='centro pendiente'><span class='link-estado' onclick='mostrarModalEstado(".$row['id_orden'].", 1, 1)'>PENDIENTE</span></td>"; break;
					          					case '2': echo "<td class='centro rechazado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='RECHAZADO POR EMPAQUE'>RECHAZADO</span></td>"; break;
					          					case '3': echo "<td class='centro enviado'><span class='link-estado' onclick='mostrarModalEstado(".$row['id_orden'].", 2, 3)'>ENVIADO</span></td>"; break;
					          					case '4': echo "<td class='centro concretado'>CONCRETADO</td>"; break;
					          					case '5': echo "<td class='centro cancelado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='CANCELADO POR EMPAQUE'>CANCELADO</span></td>"; break;
					          					case '6': echo "<td class='centro aprobado'><span class='link-estado' onclick='mostrarModalEstado(".$row['id_orden'].", 2, 6)'>APROBADO</span></td>"; break;
					          					case '7': echo "<td class='centro pendiente'>PRE-ENVIO</td>"; break;
					          					case '8': echo "<td class='centro cancelado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='CANCELADO POR DISTRIBUIDOR'>CANCELADO</span></td>"; break;
					          					case '9': echo "<td class='centro rechazado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='RECHAZADO POR DISTRIBUIDOR'>RECHAZADO</span></td>"; break;
					          					case '10': echo "<td class='centro cancelado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='CANCELADO POR PUNTO DE VENTA'>CANCELADO</span></td>"; break;
					          					case '11': echo "<td class='centro rechazado'><span class='popover-estado link-estado' tabindex='0' data-toggle='popover' data-placement='top' data-trigger='focus' data-container='body' data-content='RECHAZADO POR PUNTO DE VENTA'>RECHAZADO</span></td>"; break;
					          				}
					          			?>
						          		<td class="derecha">
							        		<button class="btn btn-primary" onClick="mostrarDetalles(<?php echo $row['id_orden']; ?>)">Detalles</button>
							        	</td>
						    	    </tr>
								<?php $cont++; 
								}
							?>
						</tbody>
					</table>

					<?php if($cont > 0){ ?>
						<div class="my-navigation" style="margin: 0px auto;">
							<div class="simple-pagination-page-numbers"></div>
							<br><br><br>
						</div>
					<?php } else{ ?>
						<div class="alert alert-info" role="alert" style="text-align: center;">
							<strong>Sin resultados...</strong> No hay entradas registradas.
						</div>
					<?php } ?>

					<?php 
						mysql_close();
					?>
				</div>
			</div>
		</div>

		<div class="modal fade bs-example-modal-lg" id="modalBusquedaAvanzada" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="titulo-header">
							<img class="img-header" src="../../img/buscar.png"> <span id="titulo-estado">Búsqueda Avanzada</span>
						</h3>
					</div>
					<div class="modal-body">
						<div class="form-horizontal">
							<div class="form-group">
						    	<label class="col-sm-2 control-label">Punto de Venta: </label>
						    	<div class="col-sm-10">
						    		<td style="border-color: #F8F8F8;"><input type="text" class="form-control" name="inputPuntoVenta" id="inputPuntoVenta" placeholder="Nombre del punto de venta..."></td>
						    	</div>
						  	</div>
						  	<div class="form-group">
						    	<label class="col-sm-2 control-label">Estado: </label>
						    	<div class="col-sm-10">
						    		<select class="form-control" name="inputEstado" id="inputEstado">
										<option value="0">TODOS</option>
										<option value="1">PENDIENTE</option>
										<option value="6">APROBADO</option>
										<option value="7">PRE-ENVIO</option>
										<option value="3">ENVIADO</option>
										<option value="8">CANCELADO POR DISTRIBUIDOR</option>
										<option value="10">CANCELADO POR PUNTO DE VENTA</option>
										<option value="9">RECHAZADO POR DISTRIBUIDOR</option>
										<option value="11">RECHAZADO POR PUNTO DE VENTA</option>
										<option value="4">CONCRETADO</option>
									</select>
						    	</div>
						  	</div>
						</div>
						<br>
						<table class="table">
							<tr>
								<td style="border-color: #F8F8F8;"><label class="lbl-nueva-orden">De:</label></td>
								<td style="border-color: #F8F8F8;"><input type="date" class="form-control" name="inputFecha1" id="inputFecha1" placeholder="Seleccionar fecha..."></td>
								<td style="border-color: #F8F8F8;"><label class="lbl-nueva-orden">A:</label></td>
								<td style="border-color: #F8F8F8;"><input type="date" class="form-control" name="inputFecha2" id="inputFecha2" placeholder="Seleccionar fecha..."></td>
							</tr>
						</table>
						
						<table class="table">
							<tr>
								<td style="border-color: #F8F8F8;"><label class="lbl-nueva-orden">De:</label></td>
								<td style="border-color: #F8F8F8;"><input type="number" min="0" class="form-control" name="inputCosto1" id="inputCosto1" placeholder="Costo mínimo..."></td>
								<td style="border-color: #F8F8F8;"><label class="lbl-nueva-orden">A:</label></td>
								<td style="border-color: #F8F8F8;"><input type="number" min="0" class="form-control" name="inputCosto2" id="inputCosto2" placeholder="Costo máximo..."></td>
							</tr>
						</table>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
						<button type="button" class="btn btn-primary" onclick="busquedaAvanzada()"><i class="glyphicon glyphicon-search"></i> Buscar</button>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade bs-example-modal-lg" id="modalEstado" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<form method="post" action="../mod/cambiar_estado_pedido.php">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
							<h3 class="titulo-header">
								<img class="img-header" src="../../img/cambiar_estado.png"> <span id="titulo-estado">Cambiar Estado del Pedido</span>
							</h3>
						</div>
						<div class="modal-body">
							<p><label>Estado:</label></p>
							<p>
								<select class="form-control" name="inputEstado" id="selectEstado">
								
								</select>
							</p>
							<br>
							<p><label>Motivo de Cancelación:</label></p>
							<p>
								<input type="hidden" name="inputIdPedido" id="inputIdPedido">
								<input type="hidden" name="inputEstadoOriginal" id="inputEstadoOriginal">
								<textarea class="form-control" rows="4" name="inputMotivoCancelar" id="inputMotivoCancelar" placeholder="Motivo de cancelación..." disabled required></textarea>
							</p>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Cerrar</button>
							<button type="submit" class="btn btn-primary"><i class="glyphicon glyphicon-floppy-disk"></i> Cambiar Estado</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<div class="modal fade bs-example-modal-lg" id="modalDetalles" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="titulo-header">
							<img class="img-header" src="../../img/detalles_orden.png"> <span id="titulo-detalles">Detalles del Pedido</span>
							<input type="hidden" name="idPedidoDetalles" id="idPedidoDetalles">
							<button class="btn btn-default" id="btnReportes" onclick="generacionReportes();" data-toggle="tooltip" title="Generación e impresión de reportes"><i class="glyphicon glyphicon-print"></i> </button>
						</h3>
					</div>
					<div class="modal-body">
						<div class="contenedor-detalles-pedido" id="contenedor-detalles-pedido">

						</div>
					</div>
					<div class="modal-footer">
						 <button type="button" class="btn btn-primary" data-dismiss="modal">Aceptar</button>
					</div>
				</div>
			</div>
		</div>

		<script type="text/javascript" src="../../lib/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../../lib/bootstrap-3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../lib/pagination/jquery-simple-pagination-plugin.js"></script>

		<script type="text/javascript">
			$('#paginacion-resultados').simplePagination();
			$('.popover-punto-venta').popover();
			$('.popover-estado').popover();
			$('#btnReportes').tooltip();

			function buscarPedidos(){
				var pvBuscar = $('#inputBuscar').val();

				if(pvBuscar != ''){
					$.ajax({
						type: 'POST',
						url: '../mod/buscar_pedidos.php',
						data: {'puntoventa':pvBuscar},

						beforeSend: function(){
							$('.contenido-general-2').html("<br><center><img id='img-cargando' src='../../img/cargando.gif'></center>");
						},

						success: function(data){
							$('.img-header').attr('src', '../../img/buscar.png');
							$('#lbl-titulo').text('Resultado de la búsqueda "' + pvBuscar + '"');
							$('#inputBuscar').select();
							$('#btnMostrarTodos').css('display', 'block');
							$('.contenido-general-2').html(data);
						}
					});
				}
			}

			function busquedaAvanzada(){
				var params = {'puntoventa':$('#inputPuntoVenta').val(), 'estado':$('#inputEstado').val(), 'fecha1':$('#inputFecha1').val(), 'fecha2':$('#inputFecha2').val(), 'costo1':$('#inputCosto1').val(), 'costo2':$('#inputCosto2').val()};

				$.ajax({
					type: 'POST',
					url: '../mod/busqueda_avanzada_pedidos.php',
					data: params,

					beforeSend: function(){
						$('#modalBusquedaAvanzada').modal('toggle');
						$('.contenido-general-2').html("<br><center><img id='img-cargando' src='../../img/cargando.gif'></center>");
					},

					success: function(data){
						$('.img-header').attr('src', '../../img/buscar.png');
						$('#lbl-titulo').text('Resultado de la Búsqueda Avanzada');
						$('#inputBuscar').select();
						$('#btnMostrarTodos').css('display', 'block');
						$('.contenido-general-2').html(data);
					}
				});
			}

			function generacionReportes(){
				var pedido = $('#idPedidoDetalles').val();
				var params = {'orden':pedido};

				$.ajax({
					type: 'POST',
					url: '../../genReps/generarOrdenPuntoVentaDistribuidor.php',
					data: params,

					beforeSend: function(){
						$('#contenedor-detalles-orden').html("<br><center><img id='img-cargando' src='../../img/cargando.gif'></center>");
					},

					success: function(data){
						var urlPDF = "../../docs/ordendecomprapv" + pedido + ".pdf";
						$('#contenedor-detalles-orden').html("");
						$('#modalDetalles').modal('toggle');
						setTimeout(window.open(urlPDF), 1000);
					}
				});
			}

			$('#selectEstado').change(function(){
				var estadoElegido = $('#selectEstado').val();

				if(estadoElegido == 8){
					$('#inputMotivoCancelar').removeAttr('disabled');
				}
				else{
					$('#inputMotivoCancelar').attr('disabled', 'disabled');
				}
			});

			function mostrarModalEstado(pedido, estados, original){
				if(estados == 1){
					$('#selectEstado').html('');
					$('#selectEstado').append("<option value='6'>APROBADO</option>");
					$('#selectEstado').append("<option value='8'>CANCELADO</option>");
				}
				else{
					$('#selectEstado').html('');
					$('#inputMotivoCancelar').removeAttr('disabled');
					$('#selectEstado').append("<option value='8'>CANCELADO</option>");
				}

				$('#inputIdPedido').val(pedido);
				$('#inputEstadoOriginal').val(original);
				$('#titulo-estado').text('Cambiar Estado del Pedido ' + pedido);
				$('#modalEstado').modal('show');
			}

			function mostrarDetalles(pedido){
				$('#idPedidoDetalles').val(pedido);

				$.ajax({
					type: 'POST',
					url: '../mod/buscar_detalles_orden_pv.php',
					data: {'orden':pedido},

					success: function(data){
						$('#contenedor-detalles-pedido').html(data);
						$('#titulo-detalles').text('Detalles del Pedido ' + pedido);
						$('#modalDetalles').modal('show');
					}
				});
			}
		</script>
	</body>
</html>