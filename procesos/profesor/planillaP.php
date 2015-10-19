<?php
include("../conexion.php");
include("validarIngresoP.php");

// registrar concepto evaluación
@$nconcep=$_POST['nConcep'];
if(isset($nconcep)){
	$pconcep=$_POST['pConcep'];
	$grupo=$_SESSION['grupoElegido'];
	$materia=$_SESSION['materiaElegida'];
	$profe=$_SESSION['idProfesor'];
	$anio=$_SESSION['anio'];
	$periodo=$_SESSION['periodo'];
	
	mysql_query("INSERT INTO conceptoevaluacion (nombre,porcentaje,grupo,materia,profesor,anio,periodo) VALUES ('$nconcep','$pconcep','$grupo','$materia','$profe','$anio','$periodo')",$con);
	
	$idC=0;
	$datos=mysql_query("SELECT id FROM conceptoevaluacion ORDER BY id DESC limit 1");
	 while($reg=mysql_fetch_array($datos)){
		 $idC=$reg['id'];
	 }
	 
	 echo $idC;
	 
	 $datos=mysql_query("SELECT id FROM matricula WHERE grupo='$grupo'");
	 while($reg=mysql_fetch_array($datos)){
		 $idMatr=$reg['id'];
		 mysql_query("INSERT INTO planilla (matricula,concepto,nota) VALUES ('$idMatr','$idC','0')",$con);
	
	 }
	 
	header("location:../../profesor/planilla.php");
	echo '<script type="text/javascript">window.location.href="../../profesor/planilla.php";</script>'; 
}

// modificar nota
@$idN=$_POST['idNota'];
if(isset($idN)){
	$valorN=$_POST['valorNota'];
	mysql_query("UPDATE planilla SET nota='$valorN' WHERE id='$idN'",$con);
	echo "si";
}


// actualizar un concepto de evaluación
@$idC=$_POST['IdConcept'];
if(isset($idC)){
	$nom=$_POST['NomConcept'];
	$porcentC=$_POST['PorcentCocept'];
	mysql_query("UPDATE conceptoevaluacion SET nombre='$nom', porcentaje='$porcentC' WHERE id='$idC'",$con);
	header("location:../../profesor/planilla.php");
	echo '<script type="text/javascript">window.location.href="../../profesor/planilla.php";</script>'; 
}

// eliminar un concepto de evaluacion
@$idCE=$_POST['IdConceptElim'];
if(isset($idCE)){
	mysql_query("DELETE FROM planilla WHERE concepto='$idCE'");
	mysql_query("DELETE FROM conceptoevaluacion WHERE id='$idCE'");
	header("location:../../profesor/planilla.php");
	echo '<script type="text/javascript">window.location.href="../../profesor/planilla.php";</script>'; 
}

// actualizar numero faltas
@$idF=$_POST['idCampF'];
if(isset($idF)){
	$valorF=$_POST['valorCampF'];
	mysql_query("UPDATE faltas SET falta='$valorF' WHERE id='$idF'",$con);
	echo "si";
}

// actualizar estado de un indicador
@$idInd=$_POST['idEstInd'];
if(isset($idInd)){
	$est=$_POST['valorEstInd'];
	mysql_query("UPDATE indicadorlogro SET estado='$est', fechaModificacion=NOW() WHERE id='$idInd'",$con);
	echo "si";
}

// actualizar final de un indicador
@$idInd2=$_POST['idFinInd'];
if(isset($idInd2)){
	$fin=$_POST['valorFinInd'];
	mysql_query("UPDATE indicadorlogro SET final='$fin' WHERE id='$idInd2'",$con);
	echo "si";
}

// actualizar nota recuperacion
@$idnR=$_POST['idnRec'];
if(isset($idnR)){
	$valnR=$_POST['valornRec'];
	mysql_query("UPDATE matricularecuperacion SET notarecuperada='$valnR' WHERE id='$idnR'",$con);
	echo "si";
}

// obtener la planilla de recuperación de un grupo
@$planiRec=$_POST['planiRec'];
if(isset($planiRec)){
	
$profe=$_SESSION['idProfesor'];
$grupo=$_SESSION['grupoElegido'];
$materia=$_SESSION['materiaElegida'];
$anio=$_SESSION['anio'];
$periodo=$_SESSION['periodo'];
$porcent=0;

$datos=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND profesor='$profe' AND anio='$anio' AND periodo='$periodo'");
while($reg=mysql_fetch_array($datos)){
	$porcent=($reg['total']==NULL)?0:$reg['total'];
}

if($porcent>=100){
	
	
	$nomMat="";
	$nomGru="";
	
	$datos=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
	while($reg=mysql_fetch_array($datos)){
		$nomGru=$reg['nombre']; 
	}
	
	$datos=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
	while($reg=mysql_fetch_array($datos)){
		$nomMat=$reg['nombre']; 
	}
	
	$idRec=0;
	$existRe=false;
	
	$datosConcepto=mysql_query("SELECT id FROM informerecuperacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
	while($reg=mysql_fetch_array($datosConcepto)){
		$idRec=$reg['id'];
		$existRe=true;
	}
	
	if($existRe==false){
		mysql_query("INSERT INTO informerecuperacion (grupo,materia,anio,periodo) VALUES ('$grupo','$materia','$anio','$periodo')",$con);
		
		$datosConcepto=mysql_query("SELECT id FROM informerecuperacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
		while($reg=mysql_fetch_array($datosConcepto)){
			$idRec=$reg['id'];
		}
	}
	$valido=false;
	$notaInf="";
	if (file_exists('../../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}
	$datosConcepto=mysql_query("SELECT id FROM matricula WHERE grupo='$grupo'");
	while($reg=mysql_fetch_array($datosConcepto)){
		$idE=$reg['id'];
		$notaF=notaFinalEst($idE,$materia,$anio,$periodo);
		
		if($notaF<$notaInf){
			
			$existeEstRe=false;
			$idRecEst=0;
			$datos2=mysql_query("SELECT id FROM matricularecuperacion WHERE matricula='$idE' AND informerecuperacion='$idRec'");
			while($reg2=mysql_fetch_array($datos2)){
				$existeEstRe=true;
				$idRecEst=$reg2['id'];
			}
			if($existeEstRe==false){
			mysql_query("INSERT INTO matricularecuperacion (matricula,informerecuperacion,notanormal,notarecuperada) VALUES ('$idE','$idRec','$notaF','0')",$con);
			}
			else{
				mysql_query("UPDATE matricularecuperacion SET notanormal='$notaF' WHERE id='$idRecEst'",$con);
			}
		
		}
		else{
			mysql_query("DELETE FROM matricularecuperacion WHERE informerecuperacion='$idRec' AND matricula='$idE'");
		}
	}
	
	
	 echo '
  <div id="contenRecuper">
   <table id="infoPlan" border="1" width="100%" cellpadding="0" cellspacing="0" style="text-align:center">
	<tr>
		<td>Año: <span>'.$anio.'</span></td>
		<td>Periodo: <span>'.$periodo.'</span></td>
		<td>Materia: <span>'.$nomMat.'</span></td>
		<td>grupo: <span>'.$nomGru.'</span></td>
	</tr>
  </table>
  ';
  
  echo '
  
  <table id="infoPlan" border="1" width="100%" cellpadding="0" cellspacing="0" style="text-align:center; min-width:900px;">
  <tr class="titlulosTr">
  <td>#</td>
  <td>Apellidos y nombres</td>
  <td>Nota original</td>
  <td>Nota recuperación</td>
  </tr>
  ';
  $index=0;
  $datosConcepto=mysql_query("SELECT id FROM matricula WHERE grupo='$grupo' ORDER BY apellidos,nombres");
	while($reg=mysql_fetch_array($datosConcepto)){
		$idE=$reg['id'];
		  $datos2=mysql_query("SELECT mr.id, mr.notanormal, mr.notarecuperada, m.nombres, m.apellidos FROM matricularecuperacion mr INNER JOIN matricula m ON mr.matricula=m.id WHERE mr.matricula='$idE' AND mr.informerecuperacion='$idRec' ORDER BY m.apellidos, m.nombres");
		  while($reg2=mysql_fetch_array($datos2)){
				$idN=$reg2['id'];
				$nombres=$reg2['nombres'];
				$apellidos=$reg2['apellidos'];
				$notan=$reg2['notanormal'];
				$notaf=$reg2['notarecuperada'];
				$index++;
				
				
				$idcR="cargaCampRec".$idN;
				echo "
				<tr>
				 <td>$index</td>
				  <td style='text-align:left; padding-left:5px;'>$apellidos $nombres</td>
				  <td>$notan</td>
				  <td><input value='$notaf' class='camposNotasR' onkeyup='cambiarNotaRecuperada(".$idN.",this.value)' /><div class='imgCarga2' id='$idcR'><img src='../imagenes/ok.png' class='imgOk' /></div></td>
				</tr>
				";		
		   }
	}
  
  
  echo "</table>";
  echo"</div>";
	
}
else{
	
	$idRec=0;
	$datosConcepto=mysql_query("SELECT id FROM informerecuperacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
	while($reg=mysql_fetch_array($datosConcepto)){
		$idRec=$reg['id'];
		$existRe=true;
	}
	
	mysql_query("DELETE FROM matricularecuperacion WHERE informerecuperacion='$idRec'");
	mysql_query("DELETE FROM informerecuperacion WHERE id='$idRec'");
	
	echo "<div id='sinRecuperaciones'>La planilla de recuperaciones solo estará habilitada una vez se halla evaluado el 100% de la materia.</div>";
}
	
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

?>