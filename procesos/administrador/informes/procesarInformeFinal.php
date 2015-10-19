<?php
include("../../conexion.php");
include("validarIngresoA.php");

$centroEducativo=$_SESSION['CentroEducativo'];
header('Content-Type: text/html; charset=UTF-8');

// obtener los grupos de una anio
@$anio=$_POST['anioEleg'];
if(isset($anio)){
	$respuesta="";
	 $idCentro=$_SESSION['CentroEducativo'];
	   $datos=mysql_query("SELECT g.id,g.nombre FROM grupo g
	                       INNER JOIN conceptoevaluacion c ON c.grupo=g.id
						   INNER JOIN grado gr ON g.grado=gr.id
	                       WHERE g.centroeducativo='$idCentro' AND c.anio='$anio'
						   GROUP by c.grupo
						   ORDER BY gr.numero
						   
						   
	                        ");
		 while($reg=mysql_fetch_array($datos)){
			 $idGrupo=$reg['id'];
			 $nomGrupo=$reg['nombre'];
			 $respuesta.="<option value='$idGrupo'>$nomGrupo</option>";
		 } 
	echo $respuesta;
}



// cargar los estudiantes de un grupo
@$grupo=$_POST['gruEleg'];
if(isset($grupo)){
	$anio=$_POST['anioGruEleg'];
	$respuesta="";
	 $idCentro=$_SESSION['CentroEducativo'];
	 
	   $datos=mysql_query("SELECT m.id,m.nombres,m.apellidos FROM grupo g
	                       INNER JOIN conceptoevaluacion c ON c.grupo=g.id
						   INNER JOIN planilla p ON p.concepto=c.id
						   INNER JOIN matricula m ON p.matricula=m.id
	                       WHERE g.centroeducativo='$idCentro' AND c.anio='$anio' AND c.grupo='$grupo'
						   GROUP by m.id
	                        ");
		 while($reg=mysql_fetch_array($datos)){
			 $idEst=$reg['id'];
			 $nomEst=$reg['nombres']." ".$reg['apellidos'];
			 $respuesta.="<option value='$idEst'>$nomEst</option>";
		 } 
	echo $respuesta;
}

// generar informe final
@$estInf=$_POST['estInf'];
if(isset($estInf)){
	$anio=$_POST['anioInf'];
	$grupo=$_POST['grupoInf'];
	$sumaNotasAreas=0;
	$cantidadAreas=0;
	
	$notaInf="";
	if (file_exists('../../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	$nomGrupo="";
	$grado=0;
	$datos=mysql_query("SELECT grado,nombre FROM grupo WHERE id='$grupo'");
	while($reg=mysql_fetch_array($datos)){
	   $nomGrupo=$reg['nombre'];
	   $grado= $reg['grado'];
	}
	if($grupo==0 || $grado==0){
	   return;	
	}
	
	echo '<div style="width:900px; text-align:left">';
	
	$centroEd=$_SESSION['CentroEducativo'];
	$datos=mysql_query("SELECT nombre,logo,nit,dane,complementodane,sistemaevaluativo FROM centroeducativo WHERE id='$centroEd'");
		 while($reg=mysql_fetch_array($datos)){
			 
			 $nombreCen=$reg['nombre'];
			 $logoCen=$reg['logo'];
			 $nitCen=$reg['nit'];
			 $daneCen=$reg['dane'];
			 $compleMentoDaneCen=$reg['complementodane'];
			 $sistemaEvaluativoCen=$reg['sistemaevaluativo'];
			 
			 $textoDane="";
			 if($daneCen!=""){
				 $textoDane="DANE:".$daneCen;
				 if($compleMentoDaneCen!=""){
					 $textoDane.=", ".$compleMentoDaneCen;
				 }
			 }
			
			 echo "<div style='text-align:center; font-style: italic;'>
				 <img src='../../imagenes/centros/$logoCen' class='logoInforme' /><br>
				 <label class='nombreCentroInforme'>$nombreCen</label><br>
				 <label class='nitInfome'>NIT:$nitCen</label><br>
				 <label class='daneInforme'>$textoDane</label><br>
				 <label class='sistemaEvaluativoInforme'>$sistemaEvaluativoCen</label>
			 </div><br>
			 
			 ";
			 
		 }
	
	
	$datos=mysql_query("SELECT docId, nombres, apellidos FROM matricula WHERE id='$estInf'");
		 while($reg=mysql_fetch_array($datos)){
			 
			 $nomEst=$reg['nombres']." ".$reg['apellidos'];
			 $docId=$reg['docId'];
			 echo '
				<table border="1" cellspacing="0" style="width:100%">
				 
				 <tr>
				 <td>Año:</td>
				 <td><span class="spnNegrita">'.$anio.'</span></td>
				 <td>Nombre del estudiante:</td>
				 <td><span class="spnNegrita">'.$nomEst.'</span></td>
				 <td>Id:</td>
				 <td><span class="spnNegrita">'.$docId.'</span></td>
				 <td>Grupo:</td>
				 <td><span class="spnNegrita">'.$nomGrupo.'</span></td>
				 </tr>
			   
			   </table>
	           ';
		 }
	
	echo ' <table border="1" cellspacing="0" style="width:100%; margin-top:10px;">';
	$datos=mysql_query("SELECT a.nombre,a.id FROM horario h INNER JOIN materia m ON h.materia=m.id INNER JOIN area a ON m.area=a.id  WHERE h.grupo='$grupo' AND h.anio='$anio' GROUP BY a.id ORDER BY a.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 
			 $area=$reg['id'];
			 $nomArea=$reg['nombre'];
			 
			 echo '
			  <tr>
			  <td class="tdsAreas">
			  ';
		     $nomArea= mb_strtoupper($nomArea, 'UTF-8');
			 $porciones = mb_str_split($nomArea);
			  for($i=0; $i<count($porciones); $i++){
				 echo $porciones[$i]."<br>";
			   }
			  echo'</td>
			  <td class="tdsInfoAreas">
				<table cellspacing="0" border="1" style="width:100%;">';
				$acomuladaArea=0;
				$datos2=mysql_query("SELECT m.id,m.nombre, m.porcentaje FROM horario h INNER JOIN materia m ON h.materia=m.id INNER JOIN area a ON m.area=a.id  WHERE h.grupo='$grupo' AND h.anio='$anio' AND a.id='$area' GROUP BY h.materia ORDER BY m.nombre");
		        while($reg2=mysql_fetch_array($datos2)){
				  
				  $materia=$reg2['id'];
				  $nomMateria=$reg2['nombre'];
				  $porcentajeMat=$reg2['porcentaje'];
				  
				  $faltas=0;
				  
				  $datos3=mysql_query("SELECT sum(falta) falta FROM faltas WHERE matricula='$estInf' AND materia='$materia' AND anio='$anio' ");
					while($reg3=mysql_fetch_array($datos3)){
						$faltas=$reg3['falta'];
					}
				  
				  echo'
				  <tr>
					<td><div class="divContenAsF">
					  <div class="dvsAsignaturas">Asignatura:<span class="spnNomAsignaturas">'.$nomMateria.'</span></div>
					  <div class="dvsFaltas">Faltas de asistencia: <span class="spnFaltas">'.$faltas.'</span></div>
					</div></td>
					<td style="text-align:center">Valor Acumulado Anual</td>
				  </tr>
				  <tr>
					<td class="tdIndicadores"><div class="contenIndicadores">
					<div class="titIndicadores">Indicadores de logro:</div>
					<ul class="ulIndicadores">';
					
					$datos3=mysql_query("SELECT nombre FROM indicadorlogro WHERE grado='$grado' AND materia='$materia' AND final='si' ORDER BY fechaModificacion ");
					while($reg3=mysql_fetch_array($datos3)){
						$nomInd=$reg3['nombre'];
						echo " <li>$nomInd</li>";
					}
					
					echo'
					</ul>
					</div></td>';
					
					
					$acomuladaAnio=acomuladaAnio($estInf,$materia,$anio,$notaInf,$grupo);
					$acomuladaAnioSum=$acomuladaAnio*$porcentajeMat/100;
					
					$acomuladaArea+=$acomuladaAnioSum;
					$acomuladaAnio=round($acomuladaAnio,1);
					
					echo'
					<td class="tdsAcomuladaAnio">'.$acomuladaAnio.'<br /><span class="spnPorcentajeAsig" >('.$porcentajeMat.'%)</span></td>
				  </tr>';
		         }
				 $acomuladaArea=round($acomuladaArea,1);
				 
				echo'</table>
				<div class="dvContenFinalArea">
				<table class="tblFilanArea" cellspacing="0" border="1"><tr><td>Valoración area de '.$nomArea.'</td><td class="tdValoracionFinalArea">'.$acomuladaArea.'</td></tr></table>
				</div><br /><br />
			  </td>
			  </tr>
			 ';
			 
			 $sumaNotasAreas+=$acomuladaArea;
	         $cantidadAreas++;
		 }
		 
    echo '</table>';
	$cantidadAreas=($cantidadAreas==0)?1:$cantidadAreas;
	$promedioFinalAreas=$sumaNotasAreas/$cantidadAreas;
	
	$promedioFinalAreas=round($promedioFinalAreas,1);
		
	echo "<div class='promedioTotalAreas'>Promedio de todas las áreas: <span>$promedioFinalAreas</span></div><br>";
    echo "<br>
	<div class='complementoInforme'>
	Comportamiento:
	<hr align='left' style='width:50%'><br><br>
	Observaciones Generales:
	<hr><br><hr><br><hr><br><br>
	<hr  align='left' style='width:50%'>
	Orientador Grupo
	
	</div>
	
	";
		 
		 
	echo "</div>";	 
	
}

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


function acomuladaAnio($idest,$materia,$anio,$nInf,$grupoE){
	$acomulada=0;
	 if (file_exists('../../../sistemaEvaluativo/sistemaEvaluativo.xml')){
	    $xml=simplexml_load_file("../../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		foreach ($xml->sistema->periodos->periodo as $periodo2) {
		   $per=$periodo2['nombre'];
		   $porcent=$periodo2['porcentaje'];
		   $nota=0;
		   $porcentEvaluadoMat=100;
		   
		   $notaFPer=0;
		   $notaFPer=notaFinalEst($idest,$materia,$anio,$per);
		   $nota=$notaFPer;
		    
			   if($nota<$nInf){
				   $noteParaRec=0;
						 $datos2I=mysql_query("SELECT m.notarecuperada  FROM informerecuperacion i 
						                       INNER JOIN matricularecuperacion m ON m.informerecuperacion=i.id 
											   WHERE  i.grupo='$grupoE' AND i.anio='$anio' AND i.periodo='$per' 
											   AND i.materia='$materia' AND m.matricula='$idest'");
							while($reg2I=mysql_fetch_array($datos2I)){
								$noteParaRec=$reg2I['notarecuperada'];
							}
				  $nota=($nota<$noteParaRec)?$noteParaRec:$nota;
			   }
			   
			  
					$datosConceptoM=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupoE' AND materia='$materia' AND anio='$anio' AND periodo='$per'");
					while($regConceptoM=mysql_fetch_array($datosConceptoM)){
						$porcentEvaluadoMat=($regConceptoM['total']==NULL)?0:$regConceptoM['total'];
			
					}
			$porcentDivi=($porcentEvaluadoMat==0)?1:$porcentEvaluadoMat;
			$nota=$nota*100/$porcentDivi;
		    $acomulada+=($nota*$porcent/100);
		}
		}
	return $acomulada;
}

function mb_str_split( $string ) { 
    # Split at all position not after the start: ^ 
    # and not before the end: $ 
    return preg_split('/(?<!^)(?!$)/u', $string ); 
} 

?>