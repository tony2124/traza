<?php 
	header("Content-Type: application/json");

	include("../mod/conexion.php");

	$datos_usuario = "";
	$datos 	= explode(",", $_POST['datos']);
	$tipo 	= $datos[0]; 
	$socio  = $datos[1];
	$orden  = $datos[2]; 
	$envio 	= $datos[3];
	$carro  = $datos[4];

	switch($socio){
		case 1://productor
		break;
		case 2://empaque

		break;
		case 3://distribuidor
			if($tipo == 1)
			{

				$query = "SELECT id_distribuidor_cajas_envio FROM distribuidor_cajas_envio WHERE recibido_dce = 1 AND id_envio_fk = $envio";
				$result = mysql_query($query);

				$recibido = mysql_num_rows($result);

				if($recibido == 0){
					$datos_usuario = "Error*No puede finalizar este envio porque aún no recibe ninguna caja.";
				}else{

					$query = "SELECT id_distribuidor_cajas_envio FROM distribuidor_cajas_envio WHERE enviado_dce = 1 AND id_envio_fk = $envio";
					$result = mysql_query($query);

					$enviado = mysql_num_rows($result);

					if($enviado == $recibido){

						$query = "UPDATE envios_empaque SET estado_envio = 4 WHERE id_envio = $envio";

						$r = mysql_query($query);

						if($r){
							$quer = "UPDATE camiones_empaque SET disponibilidad_ce = 0 WHERE id_camion = $carro";
							mysql_query($quer);

							$query = "SELECT id_envio FROM envios_empaque WHERE id_orden_fk = $orden AND estado_envio = 3";
							$Enviosresult = mysql_query($query);

							if(mysql_num_rows($Enviosresult) == 0){
								$query = "SELECT estado_orden FROM ordenes_distribuidor WHERE id_orden = $orden";
								$Ordenresult = mysql_query($query);

								$estadoOrden = mysql_fetch_array($Ordenresult);

								if($estadoOrden['estado_orden'] == 6)
									$datos_usuario = "Bien*El envio ha sido finalizado exitosamente. \n -La orden no se finalizó porque aún pueden llegar envios de la orden.";
								else{
									$query = "UPDATE ordenes_distribuidor SET estado_orden = 4 WHERE id_orden = $orden";
									$r = mysql_query($query);

										if($r){

											$datos_usuario = "Bien*El Envio y la Orden han sido Finalizados.";

											

											mysql_query($quer);
										}
										else
											$datos_usuario = "Error*El Envio ha sido finalizao y la orden no. \n -La orden debio finalizarse puesto que ya no tiene envios.";
								}

							}else
								$datos_usuario = "Bien*El Envio ha sido entregado exitosamente.\n -Finalice todos los envios de la orden para que la orden sea Finalizada";

						}else
							$datos_usuario = "Error*Error al finalizar el envio";

					}else{
						if($enviado > $recibido){
							$datos_usuario .= "Error2*No se han recibido las cajas que se enviaron.\n - Cajas enviadas: $enviado \n - Cajas recibidas: $recibido";
						}else
							$datos_usuario .= "Error2*Se han recibido mas cajas de las que se enviaron.\n - Cajas enviadas: $enviado \n - Cajas recibidas: $recibido";
					}

				}
			}

			if($tipo == 2)
			{
				$query = "UPDATE envios_empaque SET estado_envio = 4 WHERE id_envio = $envio";
				$r = mysql_query($query);

				if($r){

					$query = "SELECT id_envio FROM envios_empaque WHERE id_orden_fk = $orden AND estado_envio = 3";
					$Enviosresult = mysql_query($query);

					if(mysql_num_rows($Enviosresult) == 0){
						$query = "SELECT estado_orden FROM ordenes_distribuidor WHERE id_orden = $orden";
						$Ordenresult = mysql_query($query);

						$estadoOrden = mysql_fetch_array($Ordenresult);

						if($estadoOrden['estado_orden'] == 6)
							$datos_usuario = "Bien*El envio ha sido finalizado exitosamente. \n -La orden no se finalizó porque aún pueden llegar envios de la orden.";
						else{
							$query = "UPDATE ordenes_distribuidor SET estado_orden = 4 WHERE id_orden = $orden";
							$r = mysql_query($query);

								if($r){

									$datos_usuario = "Bien*El Envio y la Orden han sido Finalizados.";

									$quer = "UPDATE camiones_empaque SET disponibilidad_ce = 0 WHERE id_camion = $carro";

									mysql_query($quer);
								}
								else
									$datos_usuario = "Error*El Envio ha sido finalizao y la orden no. \n -La orden debio finalizarse puesto que ya no tiene envios.";
						}

					}else
						$datos_usuario = "Bien*El Envio ha sido entregado exitosamente.\n -Finalice todos los envios de la orden para que la orden sea Finalizada";

				}else
					$datos_usuario = "Error*Error al finalizar el envio";

			}

			if($tipo == 3)
			{
				$query = "UPDATE envios_empaque SET estado_envio = 9 WHERE id_envio = $envio";
				$r = mysql_query($query);

				if($r){

					$query = "SELECT id_envio FROM envios_empaque WHERE id_orden_fk = $orden AND estado_envio = 3";
					$Enviosresult = mysql_query($query);

					if(mysql_num_rows($Enviosresult) == 0){
						$query = "SELECT estado_orden FROM ordenes_distribuidor WHERE id_orden = $orden";
						$Ordenresult = mysql_query($query);

						$estadoOrden = mysql_fetch_array($Ordenresult);

						if($estadoOrden['estado_orden'] == 6)
							$datos_usuario = "Bien*El envio ha sido rechazado. \n -La orden no se rechazó porque aún pueden llegar envios de la orden.";
						else{
							$query = "UPDATE ordenes_distribuidor SET estado_orden = 9 WHERE id_orden = $orden";
							$r = mysql_query($query);

								if($r){

									$datos_usuario = "Bien*El Envio y la Orden han sido Rechazados.";

									$quer = "UPDATE camiones_empaque SET disponibilidad_ce = 0 WHERE id_camion = $carro";

									mysql_query($quer);
								}
								else
									$datos_usuario = "Error*El Envio ha sido Rechazado y la orden no. \n -La orden debio Rechazarse puesto que ya no tiene envios.";
						}

					}else
						$datos_usuario = "Bien*El Envio ha sido Recahzado.\n -Rechace todos los envios de la orden para que la orden sea Rechazada";

				}else
					$datos_usuario = "Error*Error al finalizar el envio";

			}

		break;
		case 4://punto venta
			if($tipo == 1)
			{

				$query = "SELECT id_punto_venta_cajas_envio FROM punto_venta_cajas_envio WHERE recibido_dce = 1 AND id_envio_fk = $envio";
				$result = mysql_query($query);

				$recibido = mysql_num_rows($result);

				if($recibido == 0){
					$datos_usuario = "Error*No puede finalizar este envio porque aún no recibe ninguna caja.";
				}else{

					$query = "SELECT id_punto_venta_cajas_envio FROM punto_venta_cajas_envio WHERE enviado_dce = 1 AND id_envio_fk = $envio";
					$result = mysql_query($query);

					$enviado = mysql_num_rows($result);

					if($enviado == $recibido){

						$query = "UPDATE envios_distribuidor SET estado_envio = 4 WHERE id_envio = $envio";
						$r = mysql_query($query);

						if($r){

							$query = "SELECT id_envio FROM envios_distribuidor WHERE id_orden_dist_fk = $orden AND estado_envio = 3";
							$Enviosresult = mysql_query($query);

							if(mysql_num_rows($Enviosresult) == 0){
								$query = "SELECT estado_orden FROM ordenes_punto_venta WHERE id_orden_dist_fk = $orden";
								$Ordenresult = mysql_query($query);

								$estadoOrden = mysql_fetch_array($Ordenresult);

								if($estadoOrden['estado_orden'] == 6)
									$datos_usuario = "Bien*El envio ha sido finalizado exitosamente. \n -La orden no se finalizó porque aún pueden llegar envios de la orden.";
								else{
									$query = "UPDATE ordenes_punto_venta SET estado_orden = 4 WHERE id_orden = $orden";
									$r = mysql_query($query);

										if($r)
										{
											$datos_usuario = "Bien*El Envio y la Orden han sido Finalizados.";

											$quer = "UPDATE camiones_distribuidor SET disponibilidad_camion_distribuidor = 0 WHERE id_camion_distribuidor = $carro";

											mysql_query($quer);
										}
										else
											$datos_usuario = "Error*El Envio ha sido finalizado y la orden no. \n -La orden debio finalizarse puesto que ya no tiene envios.";
								}

							}else
								$datos_usuario = "Bien*El Envio ha sido entregado exitosamente.\n -Finalice todos los envios de la orden para que la orden sea Finalizada";

						}else
							$datos_usuario = "Error*Error al finalizar el envio";

					}else{
						if($enviado > $recibido){
							$datos_usuario .= "Error2*No se han recibido las cajas que se enviaron.\n - Cajas enviadas: $enviado \n - Cajas recibidas: $recibido";
						}else
							$datos_usuario .= "Error2*Se han recibido mas cajas de las que se enviaron.\n - Cajas enviadas: $enviado \n - Cajas recibidas: $recibido";
					}

				}
			}

			if($tipo == 2)
			{
				$query = "UPDATE envios_distribuidor SET estado_envio = 4 WHERE id_envio = $envio";
				$r = mysql_query($query);

				if($r){

					$query = "SELECT id_envio FROM envios_distribuidor WHERE id_orden_dist_fk = $orden AND estado_envio = 3";
					$Enviosresult = mysql_query($query);

					if(mysql_num_rows($Enviosresult) == 0){
						$query = "SELECT estado_orden FROM ordenes_punto_venta WHERE id_orden_dist_fk = $orden";
						$Ordenresult = mysql_query($query);

						$estadoOrden = mysql_fetch_array($Ordenresult);

						if($estadoOrden['estado_orden'] == 6)
							$datos_usuario = "Bien*El envio ha sido finalizado exitosamente. \n -La orden no se finalizó porque aún pueden llegar envios de la orden.";
						else{
							$query = "UPDATE ordenes_punto_venta SET estado_orden = 4 WHERE id_orden = $orden";
							$r = mysql_query($query);

								if($r)
								{
									$datos_usuario = "Bien*El Envio y la Orden han sido Finalizados.";

									$quer = "UPDATE camiones_distribuidor SET disponibilidad_camion_distribuidor = 0 WHERE id_camion_distribuidor = $carro";

									mysql_query($quer);
								}
								else
									$datos_usuario = "Error*El Envio ha sido finalizado y la orden no. \n -La orden debio finalizarse puesto que ya no tiene envios.";
						}

					}else
						$datos_usuario = "Bien*El Envio ha sido entregado exitosamente.\n -Finalice todos los envios de la orden para que la orden sea Finalizada";

				}else
					$datos_usuario = "Error*Error al finalizar el envio";

			}


			if($tipo == 3)
			{
				$query = "UPDATE envios_distribuidor SET estado_envio = 11 WHERE id_envio = $envio";
				$r = mysql_query($query);

				if($r){

					$query = "SELECT id_envio FROM envios_distribuidor WHERE id_orden_dist_fk = $orden AND estado_envio = 3";
					$Enviosresult = mysql_query($query);

					if(mysql_num_rows($Enviosresult) == 0){
						$query = "SELECT estado_orden FROM ordenes_punto_venta WHERE id_orden_dist_fk = $orden";
						$Ordenresult = mysql_query($query);

						$estadoOrden = mysql_fetch_array($Ordenresult);

						if($estadoOrden['estado_orden'] == 6)
							$datos_usuario = "Bien*El envio ha sido Rechazado. \n -La orden no se Rechazó porque aún pueden llegar envios de la orden.";
						else{
							$query = "UPDATE ordenes_punto_venta SET estado_orden = 11 WHERE id_orden = $orden";
							$r = mysql_query($query);

								if($r)
								{
									$datos_usuario = "Bien*El Envio y la Orden han sido Rechazados.";

									$quer = "UPDATE camiones_distribuidor SET disponibilidad_camion_distribuidor = 0 WHERE id_camion_distribuidor = $carro";

									mysql_query($quer);
								}
								else
									$datos_usuario = "Error*El Envio ha sido Rechazado y la orden no. \n -La orden debio Rezhazarse puesto que ya no tiene envios.";
						}

					}else
						$datos_usuario = "Bien*El Envio ha sido Rechazado.\n -Finalice todos los envios de la orden para que la orden sea Rezhazada.";

				}else
					$datos_usuario = "Error*Error al Rechazar el envio";

			}


		break;
	}

	mysql_close();
	echo $datos_usuario;

?>