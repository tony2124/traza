<?php 
	@session_start();

	if(!isset($_SESSION['id_usuario']))
		header('Location: ../');

	$idOrden = $_POST['orden'];

	include('../../mod/conexion.php');

	$consulta = "UPDATE ordenes_punto_venta SET estado_orden = 5 WHERE id_orden = $idOrden";
	mysql_query($consulta, $conexion);

	mysql_close();
?>