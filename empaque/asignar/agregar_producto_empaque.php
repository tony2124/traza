 <?php session_start(); if($_SESSION['nivel_socio'] != 1) return; 
		include('../../mod/conexion.php');

		$idProducto	=	$_POST['idProducto'];
		$idEmpaque	=	$_POST['idEmpaque'];
		
//		$result = mysql_query("select id_producto_fk from productos_empaques join usuario_empaque on productos_empaques.id_empaque_fk = usuario_empaque.id_empaque_fk join productos on productos.id_producto = productos_empaques.id_producto_fk where usuario_empaque.id_usuario_fk =".$_SESSION['id_usuario']);

		$result = "select id_producto_fk from productos_empaques where id_empaque_fk = $_SESSION[id_empaque]";
		$bandera=0;
		if(mysql_num_rows($result) > 0){
			while($row = mysql_fetch_array($result)){
				if($row['id_producto_fk'] == $idProducto){
					$bandera = 1;
				}	
			}
		}

		if($bandera == 0){
			print $query="INSERT INTO productos_empaques(id_empaque_fk, id_producto_fk, precio_venta, precio_compra) VALUES ($idEmpaque, $idProducto,0,0)";
			mysql_query($query);
		}else
			echo "e";
		mysql_close();

 ?>