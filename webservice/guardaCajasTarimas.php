<?php 
	header("Content-Type: application/json");

	include("conexion.php");

	$datos = split(",", $_POST['datos']);
	$socio = $datos[0];
	$carro = $datos[1];
	$orden = $datos[2];
	$cajas = $datos[3];
	$tarima = $datos[4];

	$epcCajas = explode("*", $cajas);
	switch($socio){
		case 1://productor
		break;
		case 2://empaque

			$q = "SELECT id_envio FROM envios_empaque WHERE id_orden_fk = $orden AND id_camion_fk = $carro";
			$result = mysql_query($q);
			if(mysql_num_rows($result) > 0){
				$row = mysql_fetch_array($result);
				$id_envio = $row['id_envio'];

				for($i = 0; $i < count($epcCajas); $i++){
					$query = "INSERT INTO distribuidor_cajas_envio(id_envio_fk, epc_caja, epc_tarima, enviado_dce, recibido_dce) VALUES($id_envio, '".$epcCajas[$i]."', '$tarima', 1, 0)";

					mysql_query($query);
				}
  
				$datos_usuario = "Bien*Registro Exitoso";
			}else
				$datos_usuario = "Error*Error";

			
		break;
		case 3://distribuidor
		break;
		case 4://punto venta
		break;
	}


	mysql_close($dbhandle);
	echo $datos_usuario;

?>