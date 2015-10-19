<?php
include("../conexion.php");
include("validarIngresoP.php");

// cargar las materias de un grupo 
@$idGrupo=$_POST['grupo'];
if(isset($idGrupo)){
	
	$indexG=$_POST['index'];
	$_SESSION['grupoItemElegido']=$indexG;
	$profe=$_SESSION['idProfesor'];
	$anio=date("Y");
	
	$_SESSION['grupoElegido']=$idGrupo;
	
	$centro=$_SESSION['CentroEducativo'];
	$datos=mysql_query("SELECT anio FROM centroeducativo WHERE id='$centro' ");
	 while($reg=mysql_fetch_array($datos)){
		 $anio=$reg['anio'];
	 }
	 
	 $respuesta="";
	 
	 $datos=mysql_query("SELECT nombre FROM grupo WHERE id='$idGrupo' ");
	 while($reg=mysql_fetch_array($datos)){
		 $nomgrupo=$reg['nombre'];
		 $respuesta.="<div id='titulomateriaGrupo'>Materias asignadas en el grupo <span>$nomgrupo</span></div><br>";
	 }
	 
	 $datos=mysql_query("SELECT materia.id,materia.nombre FROM horario INNER JOIN materia ON materia.id=horario.materia WHERE horario.profesor='$profe' AND horario.anio='$anio' AND horario.grupo='$idGrupo' GROUP BY horario.materia");
	 while($reg=mysql_fetch_array($datos)){
		$idmate=$reg['id'];
		$materia=$reg['nombre'];
		$respuesta.="<div class='cuadrosMateriasGrupo' onClick='verMateria($idmate)'>$materia</div>";
	 }
	 
	 echo $respuesta;
}


// elegir una materia
@$mat=$_POST['materia'];
if(isset($mat)){
	$_SESSION['materiaElegida']=$mat;
}
?>