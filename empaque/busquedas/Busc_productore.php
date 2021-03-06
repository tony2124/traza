<?php if($_SESSION['nivel_socio'] != 1) return; ?>
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
			$placeholder="Nombre productor / usuario / RFC / ID";
			$imagen = "productor.png";
			$ruta = "index.php?op=reg_productor";
			include("formulario_busqueda_empresa.php"); ?>
		<div style="clear:both"></div>
		<div id="data">

		</div>

		<div class="modal fade"  id="avanzada" role="dialog" >
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
			      		<option value="-1">-- Sin filtro</option>
			      		<option value="1">Activo</option>
			      		<option value="0">Inactivo</option>
			      	</select>
			    </div>
	  		</div>

	  		<div class="form-group">
		    	<label class="col-sm-2 control-label">Fecha de registro: </label>
			    <div class="col-sm-3">
			      	<select id="fecha" onchange="verificar2()" class="form-control input">
			      		<option value="0">--- Sin filtro</option>
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
	</body>

		
	<script type="text/javascript">
		

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

		function aplicar(){
			var consulta = "";
			if($("#status").val() == 0 || $("#status").val() == 1) 
				consulta = " AND estado_p = '" + $("#status").val() + "' " ;
			else consulta = "" ;

			

			switch($("#fecha").val())
			{
				case '0': break;
				case '1': consulta += " AND fecha_registro_prod < '"+$("#fecha_i").val()+"'";  break;
				case '2': consulta += " AND fecha_registro_prod = '"+$("#fecha_i").val()+"'";  break;
				case '3': consulta += " AND fecha_registro_prod > '"+$("#fecha_i").val()+"'";  break;
				case '4': consulta += " AND fecha_registro_prod > '"+$("#fecha_i").val()+"' AND fecha_registro_prod < '"+$("#fecha_f").val()+"'"; break;
			}
			//alert(consulta);
			$("#filtro").val(consulta);
			buscar();
		}


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

		function lista(){
				
				$.ajax({
					type: 'POST',
					url: '../genReps/generarRelacionProductoresEmpaque.php',

					success: function(data){
						var urlPDF = "../docs/productoresempaque" + <?php print $_SESSION['id_empaque'] ?> + ".pdf";
						setTimeout(window.open(urlPDF), 1000);
					}
				});
			}


		function buscar(){
				var Buscar = $('#inputBuscar').val();
					var params = {'buscar':Buscar, 'filtro':$('#filtro').val()};
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