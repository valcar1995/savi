<?php
include("../conexion.php");
include("validarIngresoE.php");


function notaFinalEst($idest,$materia,$anio,$periodo){
	$notaR=0;
	$datosparaNot=mysql_query("SELECT p.nota, c.porcentaje FROM planilla p INNER JOIN conceptoevaluacion c on c.id=p.concepto  WHERE p.matricula='$idest' AND c.materia='$materia' AND c.anio='$anio' AND c.periodo='$periodo'");
	while($registro=mysql_fetch_array($datosparaNot)){
		$nota=$registro['nota'];
		$porcent=$registro['porcentaje'];
		$masN=$nota*$porcent/100;
		$notaR+=$masN;
	}
	
	return $notaR;
}


// cargar las notas del estudiante
@$cargarNotas=$_POST['cargarNotas'];
if(isset($cargarNotas)){
	$grupo=$_SESSION['idGrupoEstudiante'];
    $anio=$_SESSION['anio'];
	$matricula=$_SESSION['idEstudiante'];
	$periodo=$_POST['periodo'];
	
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	
	echo "<br><table border='1' cellpadding='0' cellspacing='0'>
	<tr class='titulosTr'>
	<td>#</td>
	<td>Materia</td>
	<td>Porcentaje evaluado</td>
	<td>Nota Actual</td>
	<td>Faltas de asistencia</td>
	</tr>";
	
	$datos=mysql_query("SELECT m.id,m.nombre FROM horario h INNER JOIN materia m ON h.materia=m.id  WHERE h.grupo='$grupo' AND h.anio='$anio' GROUP BY m.id ORDER BY m.nombre");
		 $index=0;
		 while($reg=mysql_fetch_array($datos)){
			 $index++;
			 $materia=$reg['id'];
			 $nombreMateria=$reg['nombre'];
			 $porcent=0;
			 
			$datos2=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia'  AND anio='$anio' AND periodo='$periodo'");
			while($reg2=mysql_fetch_array($datos2)){
				$porcent=($reg2['total']==NULL)?0:$reg2['total'];
			}
			
			$valF=0;
			$datos2=mysql_query("SELECT id,falta  FROM faltas WHERE  materia='$materia' AND matricula='$matricula' AND anio='$anio' AND periodo='$periodo'");
			while($reg2=mysql_fetch_array($datos2)){
				$valF=$reg2['falta'];
			}

			 $notaActual=notaFinalEst($matricula,$materia,$anio,$periodo);
			 
			 $porcent2=($porcent==0)?1:$porcent;
			 
			 $notaAct2=$notaActual*100/$porcent2;
			 
			 $classtd=($notaAct2<$notaInf)?"tdPierde":"tdGana";
			 
			 $masRecupera="";
			 if($notaAct2<$notaInf){
				  $textoNotaRe='"nota de recuperación"';
				 $datos2=mysql_query("SELECT m.notarecuperada  FROM informerecuperacion i INNER JOIN matricularecuperacion m ON m.informerecuperacion=i.id WHERE  i.grupo='$grupo' AND i.anio='$anio' AND i.periodo='$periodo' AND i.materia='$materia' AND m.matricula='$matricula'");
					while($reg2=mysql_fetch_array($datos2)){
						$notarec=$reg2['notarecuperada'];
						$masRecupera="<div class='divsRecuperacion' title='Nota de recuperación' onclick='alert($textoNotaRe)'>$notarec</div>";
					}
			 }
			 
			 $notaActual=round($notaActual,1);
			 $notaAct2=round($notaAct2,1);
			
			 
			 echo "
			 <tr class='trNormal'>
			 <td>$index</td>
			 <td class='tdMateria' onClick='cargarNotasMateria($materia)'>$nombreMateria</td>
			 <td>$porcent%</td>
			 <td class='$classtd'>$notaAct2($notaActual) $masRecupera</td>
			 <td>$valF</td>
			 </tr>
			 
			 ";
			 
		 }
		 
		 echo "</table>";
}

// cargar las notas de una materia
@$notasMateria=$_POST['cargarNotasMateria'];
if(isset($notasMateria)){

$grupo=$_SESSION['idGrupoEstudiante'];
    $anio=$_SESSION['anio'];
	$matricula=$_SESSION['idEstudiante'];
	$periodo=$_POST['periodo'];
	$materia=$notasMateria;
	
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	$nomMat="";
	$datos=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
	while($reg=mysql_fetch_array($datos)){
		$nomMat=$reg['nombre'];;
	}
	
	$porcent=0;	 
	$datos2=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia'  AND anio='$anio' AND periodo='$periodo'");
	while($reg2=mysql_fetch_array($datos2)){
		$porcent=($reg2['total']==NULL)?0:$reg2['total'];
	}
	
	$notaActual=notaFinalEst($matricula,$materia,$anio,$periodo); 
	$porcent2=($porcent==0)?1:$porcent;
	$notaAct2=$notaActual*100/$porcent2;
	$classdv=($notaAct2<$notaInf)?"dvPierde":"dvGana";
	$notaActual=round($notaActual,1);
    $notaAct2=round($notaAct2,1);
	
	
	echo "<div class='$classdv divMateriaNotas'>Su nota es de <span>$notaAct2($notaActual)</span> para la materia <span>$nomMat</span> cuyo porcentaje evaluado es <span>$porcent%</span></div>";
	
	echo "<table border='1' cellpadding='0' cellspacing='0'>
	<tr class='titulosTr'>
	<td>#</td>
	<td>Concepto de evaluación</td>
	<td>Porcentaje</td>
	<td>Nota</td>
	</tr>";
	
	$index=0;
	
	$datos=mysql_query("SELECT c.nombre,c.porcentaje,p.nota FROM planilla p INNER JOIN conceptoevaluacion c ON p.concepto=c.id WHERE p.matricula='$matricula' AND c.materia='$materia' AND c.grupo='$grupo' AND c.periodo='$periodo' AND c.anio='$anio' ORDER BY c.nombre ");
	while($reg=mysql_fetch_array($datos)){
		$index++;
		$nombreC=$reg['nombre'];
		$porcentajeC=$reg['porcentaje'];
		$nota=$reg['nota'];
		$nota2=$nota*$porcentajeC/100;
		$nota2=round($nota2,1);
		$clasetd=($nota<$notaInf)?"tdPierde":"tdGana";
		
		echo "
		<tr class='trNormal'>
		  <td>$index</td>
		  <td>$nombreC</td>
		  <td>$porcentajeC%</td>
		  <td class='$clasetd'>$nota($nota2)</td>
		</tr>
		
		";
	}
	
	echo "
	<tr class='trTotales'>
	<td></td>
	<td>Total</td>
	<td>$porcent%</td>
	<td>$notaAct2($notaActual)</td>
	</tr>
	
	";
	
	echo "</table>";

	
}

?>