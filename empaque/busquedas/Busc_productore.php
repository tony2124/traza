<!DOCTYPE html>
<html>
	<head lang="ES">
		<meta charset="UTF-8">
		<link rel='stylesheet' type='text/css' href='../lib/pagination/css.css'/>
		<link rel="stylesheet" type="text/css" href="css/views.css">
		<script type="text/javascript" src="../lib/pagination/jquery-simple-pagination-plugin.js"></script>
	</head>

	<body>
		<?php 
			$titulo = "Búsqueda de productores";
			$placeholder="Buscar productor / usuario";
			$imagen = "productor.png";
			$ruta = "index.php?op=reg_productor";
			include("formulario_busqueda_empresa.php"); ?>
		<div style="clear:both"></div>
		<div id="data">

		</div>
	</body>

		
	<script type="text/javascript">
	
		function editar(id){
			$('#myModal').modal('show');
				var params = {'id':id};
				$.ajax({
					type: 'POST',
					url: 'busquedas/editarProductor.php',
					data: params,

					success: function(data){
						$('#data-child').html(data);
					},
					beforeSend: function(data ) {
				    $("#data-child").html("<center><img src=\"img/cargando.gif\"></center>");
				  }
				});
			}

		function showModalInfo(idProductor){
	
			var params = {'id':idProductor};
					$.ajax({
						type: 'POST',
						url: 'busquedas/verProductor.php',
						data: params,

						success: function(data){
							$('#views').html(data);
						},
						beforeSend: function(data ) {
					    $("#data-child").html("<center><img src=\"img/cargando.gif\"></center>");
					  }
					});
		}
/*
		function registrar(){
			$('#myModalRegistro').modal('show');
				$.ajax({
					type: 'POST',
					url: 'lotes/registro_nuevo_lote.php',
					success: function(data){
						$('#form').html(data);
					},
						beforeSend: function(data ) {
					    $("#form").html("<center><img src=\"img/cargando.gif\"></center>");
					  }
				});
		}
*/
		function buscar(){
				var Buscar = $('#inputBuscar').val();
					var params = {'buscar':Buscar};
					$.ajax({
						type: 'POST',
						url: 'busquedas/coincidencias_productores.php',
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

	</script>
</html>