<?php
	@session_start();

	if(!isset($_SESSION['tipo_socio']) || $_SESSION['nivel_socio'] != 1){
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
		?>
		<div class="contenido-general">
			<div class="modal-header">
				<h3 class="titulo-header">
					<h3 class="titulo-contenido">
						<img class="img-header" src="../../img/nuevo_envio.png"> <span id="lbl-titulo">Lista de Camiones</span>
						<button class="btn btn-default" id="btnReportes" onclick="generacionReportes();" data-toggle="tooltip" title="Generación e impresión de reportes"><i class="glyphicon glyphicon-print"></i> </button>
					</h3>
				</h3>
			</div>
			<br>
			<div class="div-buscar">
				<div class="form-inline">
					<input type="text" class="form-control" name="inputBuscar" id="inputBuscar" placeholder="Buscar por nombre del chofer, placas, marca, modelo..." onkeyup="if(event.keyCode == 13) buscarCamiones();" autofocus>
					<button class="btn btn-primary" id="btnBuscar" onclick="buscarCamiones();"><i class="glyphicon glyphicon-search"></i> Buscar</button>
					<a href="../nuevoCamion/" class="btn btn-success" id="btnAgregarCamion"><i class="glyphicon glyphicon-plus"></i> Agregar Camion</a>
					<a href="../camiones/" class="btn btn-info" id="btnMostrarTodos"><i class="glyphicon glyphicon-th-list"></i> Mostrar Todos</a>
				</div>
			</div>
			<div class="contenido-general-2">
				<br>
				<div id="mensaje">
				<?php if(isset($_REQUEST['e'])){ ?>
			  		<div class="alert alert-danger alert-dismissible" role="alert">
						<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<strong>Error!</strong> Ha ocurrido un error al insertar los datos.
					</div>
			  	<?php } ?>
			  	</div>
				<div id="paginacion-resultados">
					<table class="table">
						<thead>
							<tr>
								<th class="centro">#</th>
								<th class="centro">ID</th>
								<th>Nombre del Chofer</th>
								<th class="centro">Placas</th>
								<th class="centro">Marca</th>
								<th class="centro">Modelo</th>
								<th>Descripción</th>
								<th class="centro">Disponibilidad</th>
								<th></th>
							</tr>
						</thead>

						<tbody>
							<?php
								include('../../mod/conexion.php');

								$consulta = "SELECT id_distribuidor_fk FROM usuario_distribuidor WHERE id_usuario_fk = ".$_SESSION['id_usuario'];
			      				$resultado = mysql_query($consulta);
			      				$row = mysql_fetch_array($resultado);
			      				$idDistribuidorFK = $row['id_distribuidor_fk'];

								$cont = 1;
								$consulta = "SELECT * FROM camiones_distribuidor WHERE id_distribuidor_fk = $idDistribuidorFK ORDER BY nombre_chofer_camion_distribuidor ASC";
								$resultado = mysql_query($consulta);
								while($row = mysql_fetch_array($resultado)){ ?>
									<tr>
						          		<td class="centro"><?php echo $cont; ?></td>
						          		<td class="centro"><?php echo $row['id_camion_distribuidor']; ?></td>
						          		<td><?php echo $row['nombre_chofer_camion_distribuidor']; ?></td>
						          		<td class="centro"><?php echo $placas = $row['placas_camion_distribuidor']; ?></td>
						          		<td class="centro"><?php echo $row['marca_camion_distribuidor']; ?></td>
						          		<td class="centro"><?php echo $row['modelo_camion_distribuidor']; ?></td>
						          		<td>
						          			<?php 
						          				$descripcion = $row['descripcion_camion_distribuidor'];
						          				if(strlen($descripcion) > 20)
						          					echo substr($descripcion, 0, 20)."...";
						          				else
						          					echo $descripcion;
						          			?>
						          		</td>
						          		<?php 
						          			$estadoCamion = $row['estado_camion_distribuidor'];
						          			if($estadoCamion == 1){
						          				if($row['disponibilidad_camion_distribuidor'] == 0)
						          					echo "<td class='centro concretado'>DISPONIBLE</td>";
						          				else
						          					echo "<td class='centro cancelado'>OCUPADO</td>";
						          			}
					          				else
					          					echo "<td class='centro cancelado'>DADO DE BAJA</td>";
					          			?>
						          		<td class="derecha">
						          			<?php 
						          				$idCamionDist = $row['id_camion_distribuidor'];
						          			?>
						          			<button class="btn btn-primary btn-editar" data-toggle="tooltip" data-placement="top" title="Editar camion" onClick="editarCamion(<?php echo $idCamionDist; ?>)"><i class="glyphicon glyphicon-pencil"></i></button>
							        		<?php 
						        				if($estadoCamion == 0){ ?>
							        				<button class="btn btn-success btn-alta" data-toggle="tooltip" data-placement="top" title="Dar de alta" onClick="altaCamion(<?php echo $idCamionDist; ?>, '<?php echo $placas; ?>')"><i class="glyphicon glyphicon-ok"></i></button>
						          				<?php } else{ ?>
						          					<button class="btn btn-danger btn-baja" data-toggle="tooltip" data-placement="top" title="Dar de baja" onClick="bajaCamion(<?php echo $idCamionDist; ?>, '<?php echo $placas; ?>')"><i class="glyphicon glyphicon-remove"></i></button>
						          				<?php }
							        		?>
							        	</td>
						    	    </tr>
								<?php $cont++; 
								}
							?>
						</tbody>
					</table>

					<?php if($cont > 1){ ?>
						<div class="my-navigation" style="margin: 0px auto;">
							<div class="simple-pagination-page-numbers"></div>
							<br><br><br>
						</div>
					<?php } else{ ?>
						<div class="alert alert-info" role="alert" style="text-align: center;">
							<strong>Sin resultados...</strong> No hay camiones registrados.
						</div>
					<?php } ?>
				</div>
			</div>
		</div>

		<?php 
			mysql_close();
		?>

		<script type="text/javascript" src="../../lib/jquery/jquery-1.11.1.min.js"></script>
		<script type="text/javascript" src="../../lib/bootstrap-3.3.5/js/bootstrap.min.js"></script>
		<script type="text/javascript" src="../../lib/pagination/jquery-simple-pagination-plugin.js"></script>

		<script type="text/javascript">
			$('#paginacion-resultados').simplePagination();
			$('#btnReportes').tooltip();
			$('.btn-editar').tooltip();
			$('.btn-alta').tooltip();
			$('.btn-baja').tooltip();

			function buscarCamiones(){
				var camionBuscar = $('#inputBuscar').val();

				if(camionBuscar != ''){
					$.ajax({
						type: 'POST',
						url: '../mod/buscar_camiones.php',
						data: {'camion':camionBuscar},

						beforeSend: function(){
							$('.contenido-general-2').html("<br><center><img id='img-cargando' src='../../img/cargando.gif'></center>");
						},

						success: function(data){
							$('.img-header').attr('src', '../../img/buscar.png');
							$('#lbl-titulo').text('Resultado de la búsqueda "' + camionBuscar + '"');
							$('#inputBuscar').select();
							$('#btnMostrarTodos').css('display', 'block');
							$('.contenido-general-2').html(data);
						}
					});
				}
			}

			function generacionReportes(){
				var dist = "<?php echo $idDistribuidorFK; ?>";
				var params = {'dist': dist};

				$.ajax({
					type: 'POST',
					url: '../../genReps/generarRelacionCamionesDistribuidor.php',
					data: params,

					beforeSend: function(){
						$('#mensaje').html("<br><center><img id='img-cargando' src='../../img/cargando.gif'></center>");
					},

					success: function(data){
						var urlPDF = "../../docs/camionesdistribuidor" + dist + ".pdf";
						$('#mensaje').html("");
						setTimeout(window.open(urlPDF), 1000);
					}
				});
			}

			function editarCamion(camion){
				var parametros = {'camion': camion};

				var body = document.body;
				form = document.createElement('form');
				form.method = 'POST'; 
				form.action = '../editarCamion/';

				for (index in parametros) {
					var input = document.createElement('input');
					input.type = 'hidden';
					input.name = index;
					input.id = index;
					input.value = parametros[index];
					form.appendChild(input);
				}
				
				body.appendChild(form);
				form.submit();
			}

			function bajaCamion(camion, placas){
				var respuesta = confirm("¿Desea dar de baja al camion " + placas + "?");
			    if(respuesta){
			    	$.post('../mod/baja_camion.php', {'camion': camion},
						function(data){
							$(location).attr('href', '../camiones/');
						}
					);
				}
			}

			function altaCamion(camion, placas){
				var respuesta = confirm("¿Desea dar de alta al camion " + placas + "?");
			    if(respuesta){
			    	$.post('../mod/alta_camion.php', {'camion': camion},
						function(data){
							$(location).attr('href', '../camiones/');
						}
					);
				}
			}
		</script>
	</body>
</html>
