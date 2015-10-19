<?php
include("../conexion.php");

// cargar las materias de un grupo
@$idGrupo=$_POST['idGrupoMat'];
if(isset($idGrupo)){
	$idpro=$_SESSION['idProfesor'];
	
	$respuesta="";
	
	$datos=mysql_query("SELECT m.id, m.nombre FROM horario h INNER JOIN materia m ON m.id=h.materia WHERE h.profesor='$idpro' AND h.grupo='$idGrupo' GROUP BY h.materia ORDER BY m.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $idM=$reg['id'];
			 $nomM=$reg['nombre'];
			 $respuesta.="<option value='$idM'>$nomM</option>";
		 }
	
	echo $respuesta;
}


// registrar una nueva tarea
@$tipoT=$_POST['tipoTarea'];
if(isset($tipoT)){
	$fechaT=$_POST['fechaTarea'];
	$grupoT=$_POST['GrupoTarea'];
	$materiaT=$_POST['materiaTarea'];
	$profeT=$_SESSION['idProfesor'];
	$descripcionT=$_POST['descripcionTarea'];
	mysql_query("INSERT INTO tareas (nombre,tipo,grupo,materia,profesor,fecha) VALUES ('$descripcionT','$tipoT','$grupoT','$materiaT','$profeT','$fechaT')",$con);
	
	header("location:../../profesor/planificador.php");
	echo '<script type="text/javascript">window.location.href="../../profesor/planificador.php";</script>'; 
	
	
}

// obtener las tareas de un mes
@$mesplan=$_POST['mesPlan'];
if(isset($mesplan)){
	$anioPlan=$_POST['anioPlan'];
	$prof=$_SESSION['idProfesor'];
	$respuesta="";
	$datos=mysql_query("SELECT day(fecha) as dia, count(*) as total, fecha, tipo FROM tareas WHERE profesor='$prof' AND MONTH(fecha) = $mesplan AND YEAR(fecha) = $anioPlan group by day(fecha)");
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
	$prof=$_SESSION['idProfesor'];
	
	$datos=mysql_query("SELECT * FROM tareas WHERE profesor='$prof' AND fecha='$fechaTa'");
		 while($reg=mysql_fetch_array($datos)){
			 $idT=$reg['id'];
			 $nombreT=$reg['nombre'];
			 $grupoT=$reg['grupo'];
			 $materiaT=$reg['materia'];
			 $tipoT=$reg['tipo'];
			 
			 $datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupoT'");
			 while($reg2=mysql_fetch_array($datos2)){
				 $grupoT=$reg2['nombre'];
			 }
			 
			 $datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materiaT'");
			 while($reg2=mysql_fetch_array($datos2)){
				 $materiaT=$reg2['nombre'];
			 }
			 
			 $tdTar=($tipoT=="Tarea")?"dvTareas":"dvClases";
			 
			 $idtar="tareaNum".$idT;
			 
			 echo "
			 <div class='$tdTar' id='$idtar'>
			 <button class='btnElimTarea' title='Eliminar' onClick='EliminarTarea($idT)'>X</button>
			 <div class='grupoMatTarea'>$materiaT-$grupoT</div>
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