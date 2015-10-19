<?php
include("../conexion.php");
include("validarIngresoP.php");
include("../seguridad/seguridadProfesor.php");


// obtener las materias
@$idGrupo=$_POST['idGrupo'];
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

// cargar los años de una planilla anterior
@$idMat=$_POST['idMateria'];
if(isset($idMat)){
	$idGrupo=$_POST['idGrupoM'];
	$idpro=$_SESSION['idProfesor'];
	
	$respuesta="";
	$datos=mysql_query("SELECT anio FROM conceptoevaluacion WHERE grupo='$idGrupo' AND materia='$idMat' GROUP BY anio ORDER BY anio");
		 while($reg=mysql_fetch_array($datos)){
			 $anio=$reg['anio'];
			 $respuesta.="<option value='$anio'>$anio</option>";
		 }
	echo $respuesta;
}

// cargar informe planilla periodo
@$idgrupuInf=$_POST['idGrupoInf'];
if(isset($idgrupuInf)){
	$grupo=$idgrupuInf;
	$materia=$_POST['idMateriaInf'];
	$anio=$_POST['anioInf'];
	$periodo=$_POST['periodoInf'];
	$profe=$_SESSION['idProfesor'];
	
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	$porcent=0;

	$datos=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
	while($reg=mysql_fetch_array($datos)){
		$porcent=($reg['total']==NULL)?0:$reg['total'];
	}
	
	
	echo '
	<br><br><table id="infoPlan" border="1" cellpadding="0" cellspacing="0" style="text-align:center;">
	<tr class="titlulosTr">
	<td>N°</td>
	<td>Apellidos y nombres</td>
	
	';
	
	$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia'  AND anio='$anio' AND periodo='$periodo'");
	$index=0;
	while($reg=mysql_fetch_array($datosConcepto)){
		$nomC=$reg['nombre'];
		$porcenC=$reg['porcentaje'];
		$index++;
		echo "<td title='$nomC ($porcenC%) ' class='circulosConceptos'>$index</td>";
	}
    
	echo '<td>Acumulada</td>
		<td>Faltas de asistencia</td>
		</tr>';
		
		
		$contador=0;
$datos=mysql_query("SELECT id,nombres,apellidos FROM matricula WHERE grupo='$grupo' ORDER BY apellidos,nombres");
while($reg=mysql_fetch_array($datos)){
	$contador++;
	$idM=$reg['id'];
	$nombresM=$reg['nombres'];
	$apellidosM=$reg['apellidos'];
   echo "<tr>";
	echo "<td>$contador</td>";
	echo "<td style='text-align:left; padding-left:5px;'>$apellidosM $nombresM</td>";
	
	$promedio=0;
	$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");

	while($reg2=mysql_fetch_array($datosConcepto)){
		$porcentajeN=$reg2['porcentaje'];
		$idC=$reg2['id'];
		$datos3=mysql_query("SELECT id,nota FROM planilla WHERE concepto='$idC' AND matricula='$idM'");
        while($reg3=mysql_fetch_array($datos3)){
			
			$idN=$reg3['id'];
			$notaN=$reg3['nota'];
			$masP=$notaN*($porcentajeN/100);
			$promedio+=$masP;
			echo '<td class="tdNotas">'.$notaN.'</td>';
		}
	}
	
	$porcentDivi=($porcent==0)?1:$porcent;
	$promedio2=$promedio*100/$porcentDivi;
	$promedio2=round($promedio2,1);
	
	$masRecupera="";
			$noteParaRec=0;
			 if($promedio2<$notaInf){
				  $textoNotaRe='"nota de recuperación"';
				 $datos2I=mysql_query("SELECT m.notarecuperada  FROM informerecuperacion i INNER JOIN matricularecuperacion m ON m.informerecuperacion=i.id WHERE  i.grupo='$grupo' AND i.anio='$anio' AND i.periodo='$periodo' AND i.materia='$materia' AND m.matricula='$idM'");
					while($reg2I=mysql_fetch_array($datos2I)){
						$noteParaRec=$reg2I['notarecuperada'];
						$masRecupera="<div class='divsRecuperacion' title='Nota de recuperación' onclick='alert($textoNotaRe)'>$noteParaRec</div>";
					
					}
			 }
			
			
	$classTd=($promedio2<$notaInf)?"tdPierdeMat":"tdGanaMat";
	echo "<td class='$classTd'>$promedio ($promedio2) $masRecupera</td>";
	
	
	
	
	$valF="";
	$datosConcepto=mysql_query("SELECT id,falta  FROM faltas WHERE  materia='$materia' AND matricula='$idM' AND anio='$anio' AND periodo='$periodo'");
	while($reg2=mysql_fetch_array($datosConcepto)){
		$idF=$reg2['id'];
		$valF=$reg2['falta'];
	}
	echo '<td>'.$valF.'</td>';
	
	
	
	
   echo "</tr>";
   
   
	
}





echo '</table><br /><br />
<div id="contenConceptosEva">';

echo "<div><table id='infoConceptPlan' style='text-align:center' border='1' cellpadding='0' cellspacing='0'>
  <tr class='titlulosTr'>
     <td >#</td>
	 <td >Concepto evaluación</td>
	 <td >Porcentaje</td>
  </tr>
";


$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
$index=0;
while($reg=mysql_fetch_array($datosConcepto)){
	$index++;
	$nomC=$reg['nombre'];
	$porC=$reg['porcentaje'];
	
	echo "
	<tr>
	 <td class='indexConceptos'>$index</td>
	 <td style='text-align:left'>$nomC</td>
	<td>$porC</td>
	</tr>
	
	";
	
}
		
echo "</table></div>";
	
}

// cargar las areas de un grupo (areas-estudiante)
@$idGrupoArea=$_POST['idGrupoAreaInf'];
if(isset($idGrupoArea)){
	
	$idpro=$_SESSION['idProfesor'];
	
	$respuesta="";
	
	$datos=mysql_query("SELECT a.id,a.nombre FROM horario h INNER JOIN materia m ON m.id=h.materia INNER JOIN area a ON m.area=a.id WHERE h.profesor='$idpro' AND h.grupo='$idGrupoArea' GROUP BY a.id ORDER BY a.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $idM=$reg['id'];
			 $nomM=$reg['nombre'];
			 $respuesta.="<option value='$idM'>$nomM</option>";
		 }
	
	echo $respuesta;
}

// cargar los años con notas de una determinada area y grupo
@$idGrupoAnioAre=$_POST['idGrupoAnioInf'];
if(isset($idGrupoAnioAre)){
	$grupo=$idGrupoAnioAre;
	$area=$_POST['idaAreaAnioInf'];
	
	
	$respuesta="";
	
	$datos=mysql_query("SELECT c.anio FROM conceptoevaluacion c INNER JOIN materia m ON c.materia=m.id INNER JOIN area a ON m.area=a.id WHERE a.id='$area' AND c.grupo='$grupo' GROUP BY c.anio ORDER BY c.anio");
		 while($reg=mysql_fetch_array($datos)){
			 $anio=$reg['anio'];
			 $respuesta.="<option value='$anio'>$anio</option>";
		 }
	
	echo $respuesta;
}

// informe de area estudiane
@$idArea=$_POST['idAreaInf'];
if(isset($idArea)){
	$area=$idArea;
	$grupo=$_POST['grupoAreaInf'];
	$anio=$_POST['anioAreaInf'];
	$periodo=$_POST['periodoAreaInf'];
	
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	
	echo"<table border='1' cellpadding='0' cellspacing='0'><tr class='titlulosTr'>
	<td>N°</td>
	<td>Apellidos y Nombres</td>";
	
	$datos=mysql_query("SELECT nombre,porcentaje FROM materia WHERE area='$area'");
	while($reg=mysql_fetch_array($datos)){
		$nomMat=$reg['nombre'];
		$porCMat=$reg['porcentaje'];
		echo "<td>$nomMat ($porCMat%)</td>";	 
	}
	
	echo "<td>Acumulada</td>";
	
	$datos=mysql_query("SELECT id,nombres,apellidos FROM matricula WHERE grupo='$grupo' ORDER BY apellidos, nombres");
	$index=0;
	while($reg=mysql_fetch_array($datos)){
		$idEst=$reg['id'];
		$nomEst=$reg['nombres'];
		$apeEst=$reg['apellidos'];
		$index++;
		
		echo "<tr>
		<td>$index</td>
		<td> $apeEst $nomEst</td>
		";
		
		$acomuladoArea=0;
		$datos2=mysql_query("SELECT id,porcentaje FROM materia WHERE area='$area'");
		while($reg2=mysql_fetch_array($datos2)){
			$idMat=$reg2['id'];
			$porcenM=$reg2['porcentaje'];
			
			$porcentEvaluadoMat=0;
			$datos3=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$idMat' AND anio='$anio' AND periodo='$periodo'");
			while($reg3=mysql_fetch_array($datos3)){
				$porcentEvaluadoMat=($reg3['total']==NULL)?0:$reg3['total'];
			}
			
			$notaFinalMateria=notaFinalEst($idEst,$idMat,$anio,$periodo);
			$porcentDivi=($porcentEvaluadoMat==0)?1:$porcentEvaluadoMat;
			
			$notaFinEstMat=$notaFinalMateria*100/$porcentDivi;
			
			$notaFinEstMat=round($notaFinEstMat,1);
			
			
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
			
			$sumAcomulado=($noteParaRec*$porcenM/100);
			$acomuladoArea+=$sumAcomulado;
			$sumAcomulado=round($sumAcomulado,1);
			$classTd=($notaFinEstMat<$notaInf)?"tdPierdeMat":"tdGanaMat";
			
			echo "<td class='$classTd'>$notaFinEstMat ($sumAcomulado) $masRecupera</td>";
			
		}
		
		$acomuladoArea=round($acomuladoArea,1);
		$classTd=($acomuladoArea<$notaInf)?"tdPierdeMat":"tdGanaMat";
		echo "<td class='$classTd'>$acomuladoArea</td>";
		echo "</tr>";
		
	}
	
	
	echo "</table>";
	
	
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

// obtener estudiates de ingreso tardio
@$igGrupTa=$_POST['idGrupoTard'];
if(isset($igGrupTa)){
	$anio=$_SESSION['anio'];
	$idProf=$_SESSION['idProfesor'];
	
	
	$resultado="";
	
	$cabezote='<table id="infoPlan" border="1" cellpadding="0" cellspacing="0" style="text-align:center;">
	<tr class="titlulosTr"><td>Estudiante</td>';
	
	$PariodosMatNotas = array();
	$IdMateriasNotas=array();
	$datos=mysql_query("SELECT m.id, m.nombre FROM horario h INNER JOIN materia m ON m.id=h.materia WHERE h.profesor='$idProf' AND h.grupo='$igGrupTa' AND h.anio='$anio' GROUP BY h.materia ORDER BY m.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $idMat=$reg['id'];
			 $nommat=$reg['nombre'];
			 $notasMatPer=obtenerPeridosConNotasMateria($idMat,$anio);
			 array_push($PariodosMatNotas, $notasMatPer);
			 array_push($IdMateriasNotas, $idMat);
			 $cabezote.="<td>$nommat</td>";
		 }
		 
		 
		 
		 
	$cabezote.="</tr>";
    
	$inicioNot=true;
	$hayEstNuevos=false;
	$datos=mysql_query("SELECT id, nombres,apellidos FROM matricula WHERE grupo='$igGrupTa' ORDER BY apellidos,nombres");
		 while($reg=mysql_fetch_array($datos)){
			 $idM=$reg['id'];
			 $nomM=$reg['nombres']." ".$reg['apellidos'];
			 
			 $esnuevo=false;
			 $InfoTr="<tr>";
			for($i=0; $i<count($PariodosMatNotas); $i++){
			    $idMat=$IdMateriasNotas[$i];
				$cantiNot=$PariodosMatNotas[$i];
				$cantidadNotasEstMat=ObtenerPeriodosConNotasMatEst($idM,$idMat,$anio);
				$InfoTr=($i==0)?$InfoTr."<td>$nomM</td>":$InfoTr;
				if($cantidadNotasEstMat<$cantiNot){
					$hayEstNuevos=true;
					$esnuevo=true;
					if($inicioNot==true){
						$inicioNot=false;
						$resultado.=$cabezote;
					}
					$idtd="td".$idMat.$idM;
					$InfoTr.="<td id='$idtd'><button class='btnsEnvio' onclick='BuscarNotasEstTardio($idM,$idMat)'>Agregar Nota</button></td>";
				}
				else{
					$InfoTr.="<td><img src='../imagenes/ok.png' width='20' height='20' /><label class='notasRegistradas'>Nota registrada</label></td>";
				}
			 }
			 $InfoTr.="</tr>";
			 if($esnuevo==true){
				$resultado.=$InfoTr; 
			 }
			 
		 }
		 
		 if($hayEstNuevos==true){
			 $resultado.="</table>";
		 }
		 
		 echo $resultado;
	
	
}


function obtenerPeriodosConNotasGrupo($MateriaN,$anioN){
	$PeriodosN=array();
	
	 $datosNC=mysql_query("SELECT periodo, count(id) as total FROM conceptoevaluacion WHERE anio='$anioN' AND materia='$MateriaN' GROUP BY periodo ORDER BY periodo");
	while($regNC=mysql_fetch_array($datosNC)){
		$periodo=$regNC['periodo'];
		$totalNotas=$regNC['total'];
		$ob= (object) array('periodo' => $periodo, 'totalNotas' => $totalNotas);
	    array_push($PeriodosN, $ob);
	}
	return $PeriodosN;
}

function obtenerPeridosConNotasMateria($idMatM,$anioM){
   $cantidadPerNotas=0;
   $datosNotPer=mysql_query("SELECT id FROM conceptoevaluacion WHERE anio='$anioM' AND materia='$idMatM' GROUP BY periodo");
	while($regNotPer=mysql_fetch_array($datosNotPer)){
		$cantidadPerNotas++;
	}
	return $cantidadPerNotas;
}

function ObtenerPeriodosConNotasMatEst($idME,$idMatE,$anioE){
   $cantidadPerNotas=0;
   $datosNotPer=mysql_query("SELECT p.id FROM planilla p 
                             INNER JOIN conceptoevaluacion c ON p.concepto=c.id 
							 WHERE c.anio='$anioE' AND c.materia='$idMatE' AND p.matricula='$idME' GROUP BY periodo");
	while($regNotPer=mysql_fetch_array($datosNotPer)){
		$cantidadPerNotas++;
	}
	return $cantidadPerNotas;
}

// cargar los periodos sin notas de un estudiante
@$estTar=$_POST['estTar'];
if(isset($estTar)){
	$matTar=$_POST['matTar'];
	$grupo=0;
	$datosNC=mysql_query("SELECT grupo FROM matricula WHERE id='$estTar'");
		while($regNC=mysql_fetch_array($datosNC)){
			$grupo=$regNC['grupo'];
		}
	if($grupo==0){
		return;
	}
	
	$anio=$_SESSION['anio'];
	echo "<form action='../procesos/profesor/areasP.php' method='post'>
	<input type='hidden' name='EstNotTard' value='$estTar' />
	<input type='hidden' name='MatNotTard' value='$matTar' />
	<table cellpadding='0' >";
	$periodosConNotasGrupo=obtenerPeriodosConNotasGrupo($matTar,$anio);
	
	for($i=0; $i<count($periodosConNotasGrupo); $i++){
		$periodoG=$periodosConNotasGrupo[$i]->periodo;
		
		$datosNC=mysql_query("SELECT count(*) as total FROM planilla p
		                     INNER JOIN conceptoevaluacion c ON p.concepto=c.id
							 WHERE c.anio='$anio' AND c.periodo='$periodoG' AND c.materia='$matTar' AND p.matricula='$estTar' ");
		$notasEst=0;
		while($regNC=mysql_fetch_array($datosNC)){
			$notasEst=$regNC['total'];
		}
		
		if($notasEst!=$periodosConNotasGrupo[$i]->totalNotas){
			$nomcam="campoTar".$periodoG;
			echo "<tr>
			<td>Periodo $periodoG:</td>
			<td><input type='number' required class='camposNotasTar' name='$nomcam' /></td>
			</tr>";
		}
	}
	
	echo "</table><input type='submit' class='btnsEnvio' value='Guardar' onclick='return confirmarRegistroNotaTardia()' /></form>";
	
}

// actualizar las notas finales de un estudiante en periodos pasados
@$idEstTar=$_POST['EstNotTard'];
if(isset($idEstTar)){
	
	$anio=$_SESSION['anio'];
	$idMatTar=$_POST['MatNotTard'];
	$grupo=0;
	$datos=mysql_query("SELECT grupo FROM matricula WHERE id='$idEstTar'");
		while($reg=mysql_fetch_array($datos)){
			$grupo=$reg['grupo'];
		}
	if($grupo==0){
		return;
	}
	
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		echo "sisisisiisis";
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		foreach ($xml->sistema->periodos->periodo as $periodo) {
		   $periodo=$periodo['nombre'];
		   $cadenaPost="campoTar".$periodo;
		   if(isset($_POST[$cadenaPost])){
			   $notaFinal=$_POST[$cadenaPost];
			   
			   $datos=mysql_query("SELECT id FROM conceptoevaluacion WHERE materia='$idMatTar' AND grupo='$grupo' AND anio='$anio' AND periodo='$periodo'");
		       while($reg=mysql_fetch_array($datos)){
				    $concepto=$reg['id'];
					 mysql_query("INSERT INTO planilla (matricula,concepto,nota) VALUES ('$idEstTar','$concepto','$notaFinal')",$con);
			   }
		    }
		    
		  }
		}
	
	header("location:../../profesor/areas.php?ver=3");
	echo '<script type="text/javascript">window.location.href="../../profesor/areas.php?ver=3"</script>'; 
			 
}



?>