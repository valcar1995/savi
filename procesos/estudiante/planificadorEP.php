<?php
include("../conexion.php");
include("validarIngresoE.php");
// registrar una nueva tarea
@$tipoT=$_POST['tipoTarea'];
if(isset($tipoT)){
	$fechaT=$_POST['fechaTarea'];
	$grupoT=$_SESSION['idGrupoEstudiante'];	
	$materiaT=$_POST['materiaTarea'];
	$profeT=$_SESSION['idProfesor'];
	$descripcionT=$_POST['descripcionTarea'];
	mysql_query("INSERT INTO tareas (nombre,tipo,grupo,materia,profesor,fecha) VALUES ('$descripcionT','$tipoT','$grupoT','$materiaT','$profeT','$fechaT')",$con);
	
	header("location:../../estudiante/planificadorE.php");
	echo '<script type="text/javascript">window.location.href="../../estudiante/planificadorE.php";</script>'; 
	
	
}


// obtener las tareas de un mes
@$mesplan=$_POST['mesPlan'];
if(isset($mesplan)){
	$anioPlan=$_POST['anioPlan'];
	$grupo=$_SESSION['idGrupoEstudiante'];
	$respuesta="";
	$datos=mysql_query("SELECT day(fecha) as dia, count(*) as total, fecha, tipo FROM tareas WHERE grupo='$grupo' AND MONTH(fecha) = $mesplan AND YEAR(fecha) = $anioPlan group by day(fecha)");
		 while($reg=mysql_fetch_array($datos)){
			 $dia=$reg['dia'];
			 $cantidad=$reg['total'];
			 $fecha=$reg['fecha'];
			 $tipo=$reg['tipo'];
			 
			 $masparaC="$dia-->$cantidad-->$fecha-->$tipo<|>";
			 
			 $respuesta.=$masparaC;
		 }
		 
		 echo $respuesta;
	
}

// buscar las tareas de un profesor para una fecha
@$fechaTa=$_POST['fechaTareas'];
if(isset($fechaTa)){
	$grupo=$_SESSION['idGrupoEstudiante'];
	
	$datos=mysql_query("SELECT * FROM tareas WHERE grupo='$grupo' AND fecha='$fechaTa'");
		 while($reg=mysql_fetch_array($datos)){
			 $idT=$reg['id'];
			 $nombreT=$reg['nombre'];
			 
			 $materiaT=$reg['materia'];
			 $tipoT=$reg['tipo'];
			 
			 
			 
			 $datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materiaT'");
			 while($reg2=mysql_fetch_array($datos2)){
				 $materiaT=$reg2['nombre'];
			 }
			 
			 
			 
			 $tdTar="";
			 
			 if($tipoT=="Tarea"){
				$tdTar= "dvTareas";
			 }
			 if($tipoT=="Evaluación"){
				 $tdTar="dvEvaluacion";
			 }
			 if($tipoT=="Clase"){
				 $tdTar= "dvClases";
			 }
			 
			 $idtar="tareaNum".$idT;
			 
			 echo "
			 <div class='$tdTar' id='$idtar'>";
			 $esRep=$_SESSION['EstRepresentante'];
			 if($esRep=="si"){
			    echo "<button class='btnElimTarea' title='Eliminar' onClick='EliminarTarea($idT)'>X</button>";
			 }
			 echo"<div class='grupoMatTarea'>$materiaT</div>
			 <div class='nombreTar'>$nombreT</div>
			 </div>
			 ";
			 
			 
		 }
}


// eliminar una tarea
@$idElimTar=$_POST['ElimTarId'];
if(isset($idElimTar)){
	mysql_query("DELETE FROM tareas WHERE id='$idElimTar'");
	echo "si";
}
?>