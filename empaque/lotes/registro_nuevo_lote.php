<!DOCTYPE html>
<html>
	<head lang="ES">
		<title>Registro - lote</title>
		<meta charset="UTF-8">
		<!--<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">-->
		<!--<link rel="stylesheet" type="text/css" href="css/estilos.css">-->
	</head>
	<body>

	<div class="contenedor-form">
		<form id="formulario" class="form-horizontal" role="form" method="post" action="lotes/registro_nuevo_lote_admin.php">
     		<div class="modal-header">
       		<h3 class="modal-title">
       			<img class="img-header" src="img/lotes.png"> Registrar lote
       		</h3>
    		</div>
    		<div>
	      	<div class="modal-body">
	      		<div class="form-inline">
			    	<label class="col-sm-2 control-label">NOMBRE DEL PRODUCTOR </label>
			    	<div class="col-sm-17">
			    		<input type="hidden" name="id_productor" id="inputIdProductor">
						<input type="text" class="form-control " style="width: 500px" name="inputNombreProductor" id="inputNombreProductor" placeholder="Productor..." readOnly required>			
						<a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modalProductor"><i class="glyphicon glyphicon-search"></i> Buscar productor</a>
			    	</div>
			    
			  	</div>
			  	<p>&nbsp;</p>
			  	<p>&nbsp;</p>
			  	<div class="form-group">
			    	<label class="col-sm-2 control-label">TIPO DE PRODUCTO </label>
			    	<div id="contenedor-productos-productor" class="col-sm-10">
			    			<div class="alert alert-info" role="alert"><p>Seleccione un productor para continuar</p></div>
		         	</div>
				  </div>
				  <div class="form-group">
			    	<label class="col-sm-2 control-label">CANTIDAD DE CAJAS: </label>
			    	<div class="col-sm-10">
			    		<input type="number" class="form-control input" 
			    		name="cantidad_cajas" id="" 
			    		placeholder="Cantidad de cajas" required min ="0">
		         	</div>
				  </div>
				  <div class="form-group">
			    	<label class="col-sm-2 control-label">CANTIDAD DE KILOS: </label>
			    	<div class="col-sm-10">
			    		<input type="number" class="form-control input" 
			    		name="cantidad_kilos" 
			    		placeholder="Número de kilos" min="0" required>
		         	</div>
				  </div>
				  <div class="form-group">
			    	<label class="col-sm-2 control-label">NOMBRE REMITENTE: </label>
			    	<div class="col-sm-10">
			    		<input  type="text" pattern="[A-Za-zñÑ ]*" class="form-control input" 
			    		name="nombre_remitente" id="" 
			    		placeholder="Nombre del remitente" required>
		         	</div>
				  </div>
				  <div class="form-group">
			    	<label class="col-sm-2 control-label">COSTO LOTE: </label>
			    	<div class="col-sm-10">
			    		<input type="number" min="0" class="form-control input" 
			    		name="costo_lote" 
			    		placeholder="Costo del lote" required>
		         	</div>
				  </div>
				  <!--<div class="form-group">
			    	<label class="col-sm-2 control-label">RANGO INICIAL EPC: </label>
			    	<div class="col-sm-10">
			    		<input type="text" class="form-control input" 
			    		name="rango_inicial" id="" 
			    		placeholder="Rango EPC inicial" >
		         	</div>
				  </div>
				  <div class="form-group">
			    	<label class="col-sm-2 control-label">RANGO FINAL EPC: </label>
			    	<div class="col-sm-10">
			    		<input type="text" class="form-control input" 
			    		name="rango_final" id="" 
			    		placeholder="Rango EPC final" >
		         	</div>
				  </div>-->
			  	<hr>
			  	<center>
		     			<button id="guardar" class="btn btn-primary" disabled><i  class="glyphicon glyphicon-ok"></i> Registrar</button>
		     			<input type="hidden" name="url" value="../index.php?op=admon_lotes">
		     		</center>
		     	</div>
	    </div>
	     </form>	
	 </div>

	 <div class="modal fade bs-example-modal-lg" id="modalProductor" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
			<div class="modal-dialog modal-lg">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h3 class="titulo-header">
							<img class="img-header" src="img/buscar.png"> <span id="titulo-detalles">Buscar productor</span>
						</h3>
					</div>
					<div class="modal-body">
						<div class="alert alert-info"><p>En la siguiente tabla se muestran los productores activos en el sistema</p></div>
						<div class="form-inline">
							<center>
								<input type="text" class="form-control" name="inputBuscarProductor" id="inputBuscarProductor"  onkeyup="if(event.keyCode == 13) buscarProductores();" style="width: 80%;">
								<button type="button" class="btn btn-primary" onclick="buscarProductores()"><i class="glyphicon glyphicon-search"></i> Buscar</button>
							</center>
						</div>

						<div class="contenedor-productores" id="contenedor-productores">
							<table class="table">
								<thead>
									<th class="centro">#</th>
									<th>RFC</th>
									<th>Nombre del Productor</th>
									<th class="derecha"></th>
								</thead>
								<tbody>
									<?php 
										include('../../mod/conexion.php');

										$cont = 1;
									    $consulta = "SELECT * FROM empresa_productores WHERE estado = 1 ORDER BY RAND() LIMIT 10 ";
										$resultado = mysql_query($consulta);
										while($row = mysql_fetch_array($resultado)){ ?>
											<tr>
												<td class="centro"><?php echo $cont; ?></td>
												<td><?php echo $row['rfc_productor']; ?></td>
								          		<td><?php echo $row['nombre_productor'] . " " . $row['apellido_productor']; ?></td>
									        	<td class="derecha">
									        		<button class="btn btn-success" data-dismiss="modal" onClick="seleccionarProductor(<?php echo $row['id_productor']; ?>, '<?php echo $row['rfc_productor'] .' - '. $row['nombre_productor'] .' '. $row['apellido_productor']; ?>');"><i class="glyphicon glyphicon-ok"></i> Seleccionar</button>
									        	</td>
									        	<?php $cont++; ?>
								    	    </tr>
										<?php }
									?>
								</tbody>
							</table>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
					</div>
				</div>
			</div>
		</div>

		<?php mysql_close(); ?>
	</body>



	<script type="text/javascript" src="../lib/jquery/jquery-1.11.1.min.js"></script>
	<!--<script type="text/javascript" src="script/bootstrap.min.js"></script>-->

	<script type="text/javascript">
			$('#tabla-detalles-orden').hide();

			function guardar(){
				if($('#selectProducto').length == 0){
					alert("Se debe seleccionar un productor con productos asignados");
				}
				else
				{
					$("#formulario").submit();
				}
			}

			function buscarProductores(){
				var productorBuscar = $('#inputBuscarProductor').val();

				if(productorBuscar != ''){
					var params = {'productor':productorBuscar};

					$.ajax({
						type: 'POST',
						url: 'buscar/buscar_productores.php',
						data: params,

						success: function(data){
							$('#contenedor-productores').html(data);
							$('#inputBuscarProductor').select();
						}
					});
				}
			}

			function seleccionarProductor(idProductor, nombreProductor){
				
				$('#inputIdProductor').val(idProductor);
				$('#inputNombreProductor').val(nombreProductor);
				$.ajax({
					type: 'POST',
					url: 'buscar/buscar_productos_productor.php',
					data: {'idProductor':idProductor},

					success: function(data){
						$('#contenedor-productos-productor').html(data);
					}
				});
			}

			
		</script>
</html>