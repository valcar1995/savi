<?php
include("../conexion.php");
include("validarIngresoP.php");

// obtener el horaio de un profesor
@$dia=$_POST['diaHor'];
if(isset($dia)){
	$profe=$_SESSION['idProfesor'];
	$anio=date("Y");
	
	$centro=$_SESSION['CentroEducativo'];
	$datos=mysql_query("SELECT anio FROM centroeducativo WHERE id='$centro' ");
	 while($reg=mysql_fetch_array($datos)){
		 $anio=$reg['anio'];
	 }
	 
	 $respuesta="";
	 
	 $datos=mysql_query("SELECT horaInicio,horaFin,materia,grupo FROM horario WHERE profesor='$profe' AND dia='$dia' AND anio='$anio' ");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $h1=$reg['horaInicio'];
		 $h2=$reg['horaFin'];
		 $materia=$reg['materia'];
		 $grupo=$reg['grupo'];
		 
		 $datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $grupo=$reg2['nombre'];
		 }
		 
		 $datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $materia=$reg2['nombre'];
		 }
		 
		 $porcion="*-*$h1-*-$h2-*-$materia-*-$grupo";
		 
		 $respuesta.=$porcion;
	 }
	 
	 echo $respuesta;
}

?>