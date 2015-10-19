<?php
include("conexion.php");
@$valido=$_SESSION['estudiante'];
@$idEst=$_SESSION['idEstudiante'];
if($valido!=true || !(isset($_SESSION['CentroEducativo'])) || $idEst==0){
session_destroy();
header("location:../index.php");
echo '<script type="text/javascript">window.location.href="../index.php";</script>'; 
}
else{
	$idcentro=$_SESSION['CentroEducativo'];
	$datos2=mysql_query("SELECT anio,periodo FROM centroeducativo WHERE id='$idcentro'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $_SESSION['anio']=$reg2['anio'];
			 $_SESSION['periodo']=$reg2['periodo'];
		 }
	
}
?>