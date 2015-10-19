<?php
include("../conexion.php");
include("validarIngresoE.php");
// cargar acomuladas de areas est
@$tdAreas=$_POST['cargarNotasAreas'];
if(isset($tdAreas)){
	$grupo=$_SESSION['idGrupoEstudiante'];
	$anio=$_SESSION['anio'];
	$matricula=$_SESSION['idEstudiante'];
	
	
	
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
		
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
		
		echo "<br><table border='1' cellpadding='0' cellspacing='0'><tr class='titulosTr'>
		<td>#</td>
		<td>Área</td>
		";
		
		foreach($xml->sistema->periodos->periodo as $p){
			echo "<td>Periodo ".$p["nombre"]." (".$p["porcentaje"]."%)</td>";
		}
		
		echo"<td>Acumulada año</td>
		</tr>";
		
		$index=0;
		$datos=mysql_query("SELECT a.nombre,a.id FROM horario h INNER JOIN materia m ON h.materia=m.id INNER JOIN area a ON m.area=a.id  WHERE h.grupo='$grupo' AND h.anio='$anio' GROUP BY a.id ORDER BY a.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $idArea=$reg['id'];
			 $nombreArea=$reg['nombre'];
			 $index++;
			 echo "<tr class='trNormal'>
			 <td>$index</td>
			 <td class='tdArea' onClick='cargarArea($idArea)'>$nombreArea</td>
			 ";
			 
			 $acomuladoPeriodos=0;
			 foreach($xml->sistema->periodos->periodo as $p){
				 $periodo=$p["nombre"];
				 $porcentajeP=$p["porcentaje"];
				 
				 $finalArea=NotaFinalArea($matricula,$grupo,$idArea,$periodo,$anio);
				 
				
				 $finalConPorcenjaePer=$finalArea*$porcentajeP/100;
				 $finalConPorcenjaePer=round($finalConPorcenjaePer,1);
				 $acomuladoPeriodos+=$finalConPorcenjaePer;
				 $classTd=($finalArea<$notaInf)?"tdPierde":"tdGana";
				 
				 echo "<td class='$classTd'>$finalArea ($finalConPorcenjaePer)</td>";
			 }
			 
			$acomuladoPeriodos=round($acomuladoPeriodos,1);
			$classTd=($acomuladoPeriodos<$notaInf)?"tdPierde":"tdGana";
			echo "<td class='$classTd'>$acomuladoPeriodos</td></tr>"; 
			 
		 }

		
		echo "</table>";
		
	}
	
}


function NotaFinalArea($matricula,$grupo,$area,$periodo,$anio){
	
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	
		$acomuladoArea=0;
		$idEst=$matricula;
		$datosMateria=mysql_query("SELECT id,porcentaje FROM materia WHERE area='$area'");
		while($regMateria=mysql_fetch_array($datosMateria)){
			$idMat=$regMateria['id'];
			$porcenM=$regMateria['porcentaje'];
			
			
				 
			$porcentEvaluadoMat=0;
			$datosConceptoM=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$idMat' AND anio='$anio' AND periodo='$periodo'");
			while($regConceptoM=mysql_fetch_array($datosConceptoM)){
				$porcentEvaluadoMat=($regConceptoM['total']==NULL)?0:$regConceptoM['total'];
			}
			
			
			
			$notaFinalMateria=notaFinalMateria($idEst,$idMat,$anio,$periodo);
			$porcentDivi=($porcentEvaluadoMat==0)?1:$porcentEvaluadoMat;
			
			$notaFinEstMat=$notaFinalMateria*100/$porcentDivi;
			
			
			$masRecupera="";
			$noteParaRec=0;
			 if($notaFinEstMat<$notaInf){
				  $textoNotaRe='"nota de recuperación"';
				  
				 $datos2I=mysql_query("SELECT m.notarecuperada  FROM informerecuperacion i INNER JOIN matricularecuperacion m ON m.informerecuperacion=i.id WHERE  i.grupo='$grupo' AND i.anio='$anio' AND i.periodo='$periodo' AND i.materia='$idMat' AND m.matricula='$idEst'");
					while($reg2I=mysql_fetch_array($datos2I)){
						$noteParaRec=$reg2I['notarecuperada'];
					}
			 }
			
			$notaFinEstMat=($notaFinEstMat>$noteParaRec)?$notaFinEstMat:$noteParaRec;
			
			$sumAcomulado=($notaFinEstMat*$porcenM/100);
			$sumAcomulado=round($sumAcomulado,1);
			
			
			$acomuladoArea+=$sumAcomulado;
			
		}
		
		$acomuladoArea=round($acomuladoArea,1);
		return $acomuladoArea;
}

function notaFinalMateria($idest,$materia,$anio,$periodo){
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


// cargar las notas de un area
@$area=$_POST['cargarNotasPorArea'];
if(isset($area)){
	
	$grupo=$_SESSION['idGrupoEstudiante'];
	$anio=$_SESSION['anio'];
	$matricula=$_SESSION['idEstudiante'];
	
	$nombreAreaE="";
	$datos=mysql_query("SELECT nombre FROM area WHERE id='$area'");
		while($reg=mysql_fetch_array($datos)){
			$nombreAreaE=$reg['nombre'];
		}
		
		echo "<div class='nombreAreaM'>$nombreAreaE</div>";
	
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
		
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
		
		echo "<br><table border='1' cellpadding='0' cellspacing='0'><tr class='titulosTr'>
		<td>#</td>
		<td>Materia</td>
		";
		
		foreach($xml->sistema->periodos->periodo as $p){
			echo "<td>Periodo ".$p["nombre"]." (".$p["porcentaje"]."%)</td>";
		}
		
		echo"<td>Acumulada año</td>
		</tr>";
		
		$acomuladoArea=0;
		$idEst=$matricula;
		$index=0;
		$datosMateria=mysql_query("SELECT id,porcentaje,nombre FROM materia WHERE area='$area' ORDER BY nombre");
		while($regMateria=mysql_fetch_array($datosMateria)){
			$index++;
			$idMat=$regMateria['id'];
			$porcenM=$regMateria['porcentaje'];
			$nombreMateria=$regMateria['nombre'];
			
			echo "<tr class='trNormal'>
			<td>$index</td>
			<td>$nombreMateria ($porcenM%)</td>";
			
			$acomuladoPeriodos=0;
			foreach($xml->sistema->periodos->periodo as $p){
				 $periodo=$p["nombre"];
				 $porcentajeP=$p["porcentaje"];
				 
			$porcentEvaluadoMat=0;
			$datosConceptoM=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$idMat' AND anio='$anio' AND periodo='$periodo'");
			while($regConceptoM=mysql_fetch_array($datosConceptoM)){
				$porcentEvaluadoMat=($regConceptoM['total']==NULL)?0:$regConceptoM['total'];
			}
			
			
			
			$notaFinalMateria=notaFinalMateria($idEst,$idMat,$anio,$periodo);
			$porcentDivi=($porcentEvaluadoMat==0)?1:$porcentEvaluadoMat;
			
			$notaFinEstMat=$notaFinalMateria*100/$porcentDivi;
			
			$masRecupera="";
			$noteParaRec=0;
			 if($notaFinEstMat<$notaInf){
				  $textoNotaRe='"nota de recuperación"';
				  
				 $datos2I=mysql_query("SELECT m.notarecuperada  FROM informerecuperacion i INNER JOIN matricularecuperacion m ON m.informerecuperacion=i.id WHERE  i.grupo='$grupo' AND i.anio='$anio' AND i.periodo='$periodo' AND i.materia='$idMat' AND m.matricula='$idEst'");
					while($reg2I=mysql_fetch_array($datos2I)){
						$noteParaRec=$reg2I['notarecuperada'];
						$masRecupera="<div class='divsRecuperacion' title='Nota de recuperación' onclick='alert($textoNotaRe)'>$noteParaRec</div>";
					
					}
			 }
			
			$noteParaRec=($notaFinEstMat>$noteParaRec)?$notaFinEstMat:$noteParaRec;
			
			
			$notaFinEstMat=round($notaFinEstMat,1);
			
			$sumAcomulado=($noteParaRec*$porcenM/100);
			$sumAcomulado=round($sumAcomulado,2);
			
			$sumParaPer=($noteParaRec*$porcentajeP/100);
			
			$acomuladoPeriodos+=$sumParaPer;
			$classTd=($notaFinEstMat<$notaInf)?"tdPierde":"tdGana";
			
			
			
			
			echo "<td class='$classTd'>$notaFinEstMat ($sumAcomulado) $masRecupera</td>";
				 
			}
			
			$acomuladoPeriodos=round($acomuladoPeriodos,1);
			$classTd=($acomuladoPeriodos<$notaInf)?"tdPierde":"tdGana";
			echo "<td class='$classTd'>$acomuladoPeriodos $masRecupera</td><tr>";
		}
		
		echo "</table>";
		
		
	}
}

?>