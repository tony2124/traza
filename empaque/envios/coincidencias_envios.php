<?php 
	include("../../mod/conexion.php");

	$buscar = $_POST['buscar'];

	//PRINT $cadena = "SELECT * FROM ordenes_distribuidor, envios_empaque, usuario_empaque where ordenes_distribuidor.id_orden = envios_empaque.id_orden_fk AND usuario_empaque.id_empaque_fk = ordenes_distribuidor.id_empaque_fk AND usuario_empaque.id_usuario_fk = 51";
	$cadena = "select * from envios_empaque, empresa_distribuidores, ordenes_distribuidor where nombre_distribuidor like '%$buscar%' AND id_distribuidor = id_distribuidor_fk AND id_orden = id_orden_fk AND id_empaque_fk = ".$_SESSION['id_empaque'];
	$result_productores = mysql_query($cadena);
	if(mysql_num_rows($result_productores) > 0){

 ?>

<div id="paginacion-resultados" style="width:95%; margin:0px auto;">
	    <table class="table table-hover">
	    	<thead>
		        <tr>
		          <th class="centro">#</th>
		          <th class="centro">Id orden</th>
		          <th class="centro">Distribuidor</th>
		          <th class="centro">Producto</th>
		          <th class="centro">Fecha de envío</th>
		          <th class="centro">Estado</th>
		          <th class="centro">Acción</th>
		        </tr>
      		</thead>
      		<tbody>
			<?php
			
				$i=1;
				 while($row = mysql_fetch_array($result_productores)) {
				 	?>
				 	<tr>
		        		<td><?php echo $i; ?></td>
		        		<td> <?php echo $row['id_orden_fk']; ?> </td>
			          	<td><?php echo $row['nombre_distribuidor']; ?></td>
			        	<td><?php 

			        	$cadena = "SELECT * FROM productos, ordenes_distribuidor_detalles WHERE id_producto = id_producto_fk AND id_orden_fk = ".$row['id_orden_fk'];
						$result = mysql_query($cadena);
						if(mysql_num_rows($result) > 0){
							while($row1 = mysql_fetch_array($result)) {
			        			echo $row1['nombre_producto']. " ".$row1['variedad_producto']."<br>"; 
			        		}
			        	}


			        	?>
			        	</td>
			          	<td class="centro"><?php echo $row['fecha_envio']; ?></td>
			          	<td class="centro"><?php if($row['estado_envio'] == 3){ ?>
			          	 <div class="label label-primary">ENVIADO</div>  <?php } 
			          	if($row['estado_envio'] == 4){ ?>
			          	<div class="label label-success">CONCRETADO</div> <?php  } 
			          	if($row['estado_envio'] == 5){ ?>
			          	<div class="label label-danger">CANCELADO</div> <?php  } ?>

			          </td>
			          	
			           <td align="center" width="300"> 
			           	<a  href="#" onclick = "mostrarCajasTarimas(<?php echo $row['id_orden_fk']; ?>)" data-toggle="modal" data-target="#modalCajasTarimas">
				        	<span title="Mostrar cajas" data-toggle="tooltip" class="glyphicon glyphicon-copy"></span>
				    	</a>&nbsp;&nbsp;
			           	<a onclick="detalles(<?php echo $row['id_envio'] ?>,<?php echo $row['id_orden_fk'] ?>)" data-toggle="modal" data-target="#myModal"  href="#">
				        	<span title ="Detalles de envío" class="glyphicon glyphicon-eye-open"></span>
				    	</a>&nbsp;&nbsp;
				    	<?php if($_SESSION['nivel_socio'] == 1) if($row['estado_envio'] == 3){ ?>
				    	<a data-toggle="modal" data-target="#myModal" href="#" onclick = "cancelar('<?php print $row['id_envio'] ?>','<?php print $row['id_orden_fk'] ?>')"> 
          					<span data-toggle="tooltip" data-placement="top" title="Cancelar envío" class="glyphicon glyphicon-remove"></span>
          				</a>&nbsp;&nbsp;
          				
          				<?php } ?>
			           	</td>
		        	</tr>
		        <?php  
		        $i=$i+1;
				 }
			
			  ?>
          	</tbody>
        </table>

        		<?php if($i > 1){ ?>
					<div class="my-navigation" style="margin: 0px auto;">
					<div class="simple-pagination-page-numbers"></div>
					<br><br><br>
					</div>
				<?php } 
			?>

		</div>

        <?php 
		    }else{
		    	 ?>
		    	 <br><br>
		    	 <br>
		    	 	<div style="width:500px; margin:0px auto;" class="alert alert-info centro" role="alert"> 
		    	 		<strong>No hay envío</strong>
		    	 	</div>
		    	 	<br><br>
		    	<?php
		    }
		?>
			<!-- Modal -->
			<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
			  <div class="modal-dialog" role="document"  style="width:800px;">
			    <div class="modal-content">
			      <div class="modal-header">
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			        <h3 class="modal-title" id="mititulo">Registrar envío</h3>
			      </div>
			      <div class="modal-body fondo-blanco">
			      	<div id="data-child1" style="margin:0px auto; ">
			        	
					</div>
			      </div>
			    </div>
			  </div>
			</div>

						<!-- Modal -->
		<div class="modal fade" id="modalCajasTarimas" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
		    <div class="modal-content">
		      <div class="modal-body">
		      	<div id="id_modalCajasTarimas">
		      		<!--<img src="img/cargando.gif">-->
		      	</div>
		        
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
		      </div>
		    </div>
		  </div>
		</div>
		<script type="text/javascript" src="../lib/pagination/jquery-simple-pagination-plugin.js"></script>
		<script type="text/javascript">
			$('#paginacion-resultados').simplePagination();
		</script>