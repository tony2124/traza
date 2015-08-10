<!DOCTYPE html>
<html>
	<head lang="ES">
		<title>Trazabilidad</title>
		<meta charset="UTF-8">
		<link rel='stylesheet' type='text/css' href='../lib/pagination/css.css'/>
		<link rel="stylesheet" type="text/css" href="css/views.css">
		
	</head>

	<body>
		<?php 
			$titulo = "Búsqueda de pedidos";
			$placeholder="Nombre del distribuidor / número orden";
			$imagen = "detalles_orden.png";
			include("../busquedas/formulario_busqueda.php"); ?>
		
	<div style="clear:both"></div>
	<div id="data"></div>

	<div class="modal fade"  id="filtro" role="dialog" >
	  <div class="modal-dialog" style="width: 700px" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h3 class="modal-title"><div>Búsqueda avanzada</div></h3>
	      </div>
	      <div class="modal-body">
	      	<div class="form-horizontal">
		   	<div class="form-group">
		    	<label class="col-sm-2 control-label">Estado: </label>
			    <div class="col-sm-4">
			      	<select id="status" class="form-control input">
			      		<option value="0">-- Elige una opción</option>
			      		<option value="5">Cancel. por empaque</option>
			      		<option value="8">Cancel. por distribuidor</option>
			      		<option value="6">Aprobada</option>
			      		<option value="2">Rechazad. por empaque</option>
			      		<option value="9">Rechazad. por distribuidor</option>
			      		<option value="4">Concretada</option>
			      		<option value="3">Enviada</option>
			      	</select>
			    </div>
	  		</div>
	  		<div class="form-group">
		    	<label class="col-sm-2 control-label">Costo: </label>
			    <div class="col-sm-3">
			      	<select id="costo" onchange="verificar()" class="form-control input">
			      		<option value="0">---</option>
			      		<option value="1">Menor que</option>
			      		<option value="2">Igual que</option>
			      		<option value="3">Mayor que</option>
			      		<option value="4">Entre</option>
			      	</select>
			    </div>
			    <div class="col-sm-3">
			      	<input id="costo_inicio" disabled class="form-control input" type="number" value="0.0" min="0">
			    </div> 
			    <div class="col-sm-3">
			      	<input id="costo_fin" style="display: none" class="form-control input" type="number" value="0.0" min="0">
			    </div> 
	  		</div>
	  		<div class="form-group">
		    	<label class="col-sm-2 control-label">Fecha de pedido: </label>
			    <div class="col-sm-3">
			      	<select id="fecha" onchange="verificar2()" class="form-control input">
			      		<option value="0">---</option>
			      		<option value="1">Menor que</option>
			      		<option value="2">Igual que</option>
			      		<option value="3">Mayor que</option>
			      		<option value="4">Entre</option>
			      	</select>
			    </div>
			    <div class="col-sm-3">
			      	<input id="fecha_i" value="<?php print date("Y-m-d") ?>" disabled class="form-control input" type="date" min="0">
			    </div> 
			    <div class="col-sm-3">
			      	<input id="fecha_f" value="<?php print date("Y-m-d") ?>" style="display:none" class="form-control input" type="date" min="0">
			    </div> 

	  		</div>

	  		</div>
	  		<div style="clear:both"></div>
	  		<p>&nbsp;</p>
		  </div>
		  <div class="modal-footer">
		  	<center>
			    <button style="width: 150px" type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			    <button onclick="aplicar()" style="width: 150px" class="btn btn-primary" data-dismiss="modal">Aplicar</button>
			    <input type="hidden" id="filtro">
		    </center>
	      </div>
	    </div>
	  </div>
	</div>



	
	<script type="text/javascript">

		function verificar(){
			//alert("cambio a " + $("#costo").val());
			if($("#costo").val() == 0)
				$("#costo_inicio").attr("disabled","disabled");
			else 
				$("#costo_inicio").removeAttr("disabled");

			if($("#costo").val() == 4)
				$("#costo_fin").css("display","block");
			else
				$("#costo_fin").css("display","none");
		}

		function verificar2(){
			//alert("cambio a " + $("#costo").val());
			if($("#fecha").val() == 0)
				$("#fecha_i").attr("disabled","disabled");
			else 
				$("#fecha_i").removeAttr("disabled");

			if($("#fecha").val() == 4)
				$("#fecha_f").css("display","block");
			else
				$("#fecha_f").css("display","none");
		}


		

		function buscar(){
				var Buscar = $('#inputBuscar').val();
					var params = {'buscar':Buscar, 'filtro':$('#filtro').val()};
					$.ajax({
						type: 'POST',
						url: 'pedidos/coincidencias_pedidos.php',
						data: params,

						success: function(data){
							$('#data').html(data);
							$('#inputBuscar').select();
						},
						beforeSend: function(data ) {
					    $("#data").html("<center><img src=\"img/cargando.gif\"></center>");
					  }
					});
			}

			buscar();


		function mostrarModalOrdenes(idOrden, descripcion, total, fecha, usuario){
			$('#detallesOrden').html("");
			$('#myModalOrden').modal('show');
			var parametros = {'id_orden':idOrden, 'descripcion': descripcion,'total':total,'fecha':fecha,'usuario':usuario};

			$.ajax({
				type:'post',
				url:'pedidos/detallesOrdenes.php',
				data: parametros,
				success: function(data){
					$('#detallesOrden').html(data);
					
				},
				beforeSend: function(data ) {
			    	$("#detallesOrden").html("<center><img src=\"img/cargando.gif\"></center>");
				}

			});
		}

		function infoModalShow(id, estado){
			if(estado == 6){ 
				$('#info_modal').html(
					'<div class="alert alert-warning" role="alert"> <strong> ¿Seguro? </strong> Al aprobar la orden estás aceptando que se inicie el proceso de embarque. 	</div>'
				);
				$('#titulo_orden').html('¡Aprobar Orden!');
			}
			if(estado == 1){ 
				$('#info_modal').html(
					'<div class="alert alert-warning" role="alert"> <strong> ¿Seguro? </strong> <p>Al hacer esta operación reiniciarás el proceso de la orden. Ten encuenta que puedes causar inconsistencias en el proceso de la orden, te recomendamos lo siguiente</p><ul><li>Contacta al cliente y comunícale que se re-valorará la orden.</li></ul></p>	</div>'
				);
				$('#titulo_orden').html('¡Re-valorar Orden!');
			}
			if(estado == 5){ 
				$('#info_modal').html(
					'<div class="alert alert-danger" role="alert"> <strong> ¿Seguro? </strong> <p>Al cancelar la orden se está expresando que no se puede concluir el proceso de la misma.	</div><label>Motivo de cancelación:</label><textarea required class="form-control" name="motivo"></textarea>'
				);
				$('#titulo_orden').html('¡Cancelar Orden!');

			}

			if(estado == 2){ 
				$('#info_modal').html(
					'<div class="alert alert-danger" role="alert"> <strong> ¿Seguro? </strong> <p>Ten encuenta que si se rechaza una orden se reconoce que el proceso de la orden no ha sido el adecuado</p></div><label>Motivo de rechazo:</label><textarea required class="form-control" name="motivo"></textarea>'
				);
				$('#titulo_orden').html('¡Rechazar Orden!');
			}

			$('#id').val(id);
			$('#estado').val(estado);
			$('#infoModal').modal('show');
		}

		function aplicar(){
			var consulta = "";
			if($("#status").val() == 0) 
				consulta = "";
			else consulta =  " AND estado_orden = '" + $("#status").val() + "' " ;

			switch($("#costo").val())
			{
				case 0: break;
				case 1: consulta += " AND costo_orden > "+$("#costo_inicio").val();  break;
				case 2: break;
				case 3: break;
				case 4: consulta += " AND costo_orden > "+$("#costo_inicio").val()+" AND costo_orden < "+$("#costo_fin").val(); break;
			}
				

				

			$("#filtro").val(cadena);
			buscar();
		}

	</script>
	</body>	
</html>