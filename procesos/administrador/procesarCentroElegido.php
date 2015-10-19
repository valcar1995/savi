<?php
include("../conexion.php");
include("validarIngresoA.php");
// elgir centro al inicio
@$cenEle=$_POST['cenEleg'];
if(isset($cenEle)){
	$_SESSION['CentroEducativo']=$cenEle;
	
	$datos=mysql_query("SELECT anio,periodo  FROM centroeducativo WHERE id='$cenEle'");
    while($reg=mysql_fetch_array($datos)){
		$_SESSION['anio']=$reg['anio'];
		$_SESSION['periodo']=$reg['periodo'];
		
	}
	
	
	
	header("location:../../administrador/reUsuarios.php");
	echo '<script type="text/javascript">window.location.href="../../administrador/reUsuarios.php";</script>'; 
}


// elegir centro desde registros

@$cenElere=$_POST['regCen'];
if(isset($cenElere)){
	$_SESSION['CentroEducativo']=$cenElere;
	
	$anio=$_POST['anio'];
	$periodo=$_POST['periodo'];
	
	$_SESSION['anio']=$anio;
	$_SESSION['periodo']=$periodo;
	
	echo $periodo;
	
	mysql_query("UPDATE centroeducativo SET anio='$anio', periodo='$periodo' WHERE id='$cenElere'",$con);
	
}

// obtener el año y periodo del centro elegido

@$centro=$_POST['idCentCambio'];
if(isset($centro)){
	$respuesta="";
	$datos=mysql_query("SELECT anio,periodo  FROM centroeducativo WHERE id='$centro'");
    while($reg=mysql_fetch_array($datos)){
		$respuesta=$reg['anio']."|".$reg['periodo'];
	}
	echo $respuesta;
}

?>