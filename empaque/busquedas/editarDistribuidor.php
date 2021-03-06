<?php  if($_SESSION['nivel_socio'] != 1) return; 
	include("../mod/conexion.php");
	$cad = "select id_distribuidor, nombre_distribuidor, rfc_distribuidor, ".
				"pais_distribuidor, estado_distribuidor, ciudad_distribuidor, direccion_distribuidor, cp_distribuidor, ".
				" email_distribuidor, tel1_distribuidor, tel2_distribuidor, estado_d from empresa_distribuidores where 
				id_distribuidor = $id AND id_usuario_que_registro = ".$_SESSION['id_receptor'];
	 $result_productores = mysql_query($cad);
        	
			$row = mysql_fetch_array($result_productores);  
?>
<div class="modal-header">
		<h3 class="modal-title">
			<img class="img-header" src="img/distribuidor.png"> Editar distribuidor
		</h3>
	</div>
	<div style="width:80%; margin: 30px auto">
<form name="formulario" class="form-horizontal" role="form" method="post" action="busquedas/editar_distribuidor.php">
	<div class="modal-body" style="width:50%; float: left; border-radius: 5px">
	  	<div class="alert alert-info">DATOS DEL DISTRIBUIDOR</div>

		  <div class="form-group">
	    	<label class="col-sm-3 control-label">Nombre1: </label>
	    	<div class="col-sm-8">
	    		<input type="text" pattern="[A-Za-zÑñáéíóúÁÉÍÓÚ]+([ ][A-Za-zÑñáéíóúÁÉÍÓÚ]+)*" title="Ingresa sólo letras y sin espacios extras" class="form-control input" value="<?php echo $row['nombre_distribuidor']; ?>" name="nombre_distribuidor" id="" placeholder="Nombre del distribuidor" required>
         	</div>
		  </div>
		  <div class="form-group">
	    	<label class="col-sm-3 control-label">RFC: </label>
	    	<div class="col-sm-8">
	    		<input type="text" pattern="[A-Za-z]{4}[0-9]{6}[A-Za-z0-9]{3}" title="El RFC debe contener 4 letras, seguido de 6 números y tres caracteres de la homoclave" class="form-control input" value="<?php echo $row['rfc_distribuidor']; ?>" name="rfc_distribuidor" id="" placeholder="RFC del distribuidor" required>
         	</div>
		  </div>

			
		  <div class="form-group">
	    	<label class="col-sm-3 control-label">E-mail: </label>
	    	<div class="col-sm-8">
	    		<input type="email" value="<?php echo $row['email_distribuidor']; ?>" class="form-control input" name="email_distribuidor" id="" placeholder="Correo electrónico" required>
         	</div>
		  </div>

		  <div class="form-group">
	    	<label class="col-sm-3 control-label">Teléfono: </label>
	    	<div class="col-sm-8">
	    		<input type="text" pattern="[0-9]{10}|[0-9]{11}|[0-9]{12}|[0-9]{13}" title="Ingresa 10, 11, 12 y 13 dígitos" value="<?php echo $row['tel1_distribuidor']; ?>" class="form-control input" name="telefono1_distribuidor" id="" placeholder="Teléfono" required>
         	</div>
		  </div>
		  <div class="form-group">
	    	<label class="col-sm-3 control-label">Teléfono 2: </label>
	    	<div class="col-sm-8">
	    		<input type="text" pattern="[0-9]{10}|[0-9]{11}|[0-9]{12}|[0-9]{13}" title="Ingresa 10, 11, 12 y 13 dígitos" value="<?php echo $row['tel2_distribuidor']; ?>" class="form-control input" name="telefono2_distribuidor" id="" placeholder="Teléfono alternativo" >
         	</div>
		  </div>
		 </div>
		  <div class="modal-body" style="width:40%; float: right; border-radius: 5px">
     		    <div class="alert alert-info">UBICACIÓN DEL DISTRIBUIDOR</div>

		  <div class="form-group">
	    	<label class="col-sm-3 control-label">País: </label>
		    <div class="col-sm-8">
		      	<select class="form-control select select-primary" name="pais" id="select1" onchange="cambiar()">
					<option value="0">MÉXICO</option>
					<option value="1">EEUU</option>
					<option value="2">CANADA</option>
					<option value="3">JAPÓN</option>
					<option value="4">AUSTRALIA</option>
				</select>
		    </div>
	  	</div>
		
		<!-- Estado -->
 	  	<div class="form-group">
	    	<label class="col-sm-3 control-label">Estado: </label>
		    <div class="col-sm-8">
		       <select class="form-control select select-primary" name="estado" id="select2">
				</select>
		    </div>
	  	</div>
		
		
 	  	<div class="form-group">
	    	<label class="col-sm-3 control-label">Ciudad: </label>
		    <div class="col-sm-8">
		      	<input type="text" pattern="[A-Za-zÑñáéíóúÁÉÍÓÚ]+([ ][A-Za-zÑñáéíóúÁÉÍÓÚ]+)*" title="Ingresa sólo letras y sin espacios extras" class="form-control input" value="<?php echo $row['ciudad_distribuidor']; ?>" name="ciudad_distribuidor" required placeholder="Ciudad del distribuidor">	
		    </div>
	  	</div>
		
		<div class="form-group">
	    	<label class="col-sm-3 control-label">Dirección: </label>
	    	<div class="col-sm-8">
	    		<input type="text" value="<?php echo $row['direccion_distribuidor']; ?>" class="form-control input" name="direccion_distribuidor" id="" placeholder="Dirección del distribuidor" required>
         	</div>
		  </div>
		  <div class="form-group">
	    	<label class="col-sm-3 control-label">C.P: </label>
	    	<div class="col-sm-8">
	    		<input type="text" pattern="[0-9]{5}|[0-9]{6}|[0-9]{7}" title="Ingresa 5, 6 o 7 dígitos" value="<?php echo $row['cp_distribuidor']; ?>" class="form-control input" name="cp_distribuidor" id="" placeholder="Código postal" required>
         	</div>
		  </div>
	  	<hr>
	  	<center>
	  			<a href="index.php?op=bus_distribuidor" type="button" class="btn btn-default" style="width: 150px" >Regresar</a>
	  			<input type="hidden" name="id_distribuidor" value="<?php echo $row['id_distribuidor']; ?>">
     			<button type="submit" class="btn btn-primary" style="width: 150px"><i  class="glyphicon glyphicon-ok"></i> Guardar cambios</button>
     		</center>
     	</div>
	</form>	
	<div style="clear: both"></div>
	<p>&nbsp;</p>
</div>
<?php include("script/paises.js"); ?>

<script type="text/javascript">
	seleccionar(<?php print $row['pais_distribuidor'] .",". $row['estado_distribuidor']?>);
</script> 