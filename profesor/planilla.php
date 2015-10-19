

<?php
include("../procesos/token2.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planilla</title>
<link rel="stylesheet" type="text/css" href="../css/profesor/planilla.css"/>
<script type="text/javascript" src="../js/profesor/planilla.js"></script>


</head>

<body>
<?php include("menu.php") ?>

<center>

<?php


$profe=$_SESSION['idProfesor'];
$grupo=$_SESSION['grupoElegido'];
$materia=$_SESSION['materiaElegida'];
$anio=$_SESSION['anio'];
$periodo=$_SESSION['periodo'];
$porcent=0;

$datos=mysql_query("SELECT sum(porcentaje) as total FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
while($reg=mysql_fetch_array($datos)){
	$porcent=($reg['total']==NULL)?0:$reg['total'];
}

$existePlanilla=false;

$datos=mysql_query("SELECT id FROM horario WHERE profesor='$profe' AND grupo='$grupo' AND materia='$materia' ");
while($reg=mysql_fetch_array($datos)){
	$existePlanilla=true;	 
}

if($existePlanilla==false){
	header("location:grupos.php");
	echo '<script type="text/javascript">window.location.href="grupos.php";</script>'; 
}


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

echo "<div id='tituloPlanilla'><span>$nomMat</span>-<span>$nomGru</span></div>";

?>
<br /><br />
<div id="menuPlanilla">
  <div class="itemsMenuPlanilla" id="itemselPlanilla" onclick="cambiarContenido(1)">Planilla</div>
  <div class="itemsMenuPlanilla" id="itemPlanilla2" onclick="cambiarContenido(2)">Indicadores de logro</div>
  <?php
  
  if($porcent>=100){
  echo '<div class="itemsMenuPlanilla" id="itemPlanilla3" onclick="cambiarContenido(3)">Recuperaciones</div>';
   }
  ?>
</div>
<div id="contenTodoPlan">
<br /><br />
<div id="recibeConten1" class="recibeConten" style="display:block">

<div id="contenPlanilla">

<?php

  echo '
  
   <table id="infoPlan" border="1" width="100%" cellpadding="0" cellspacing="0" style="text-align:center">
	<tr>
		<td>Año: <span>'.$anio.'</span></td>
		<td>Periodo: <span>'.$periodo.'</span></td>
		<td>Materia: <span>'.$nomMat.'</span></td>
		<td>grupo: <span>'.$nomGru.'</span></td>
		<td>Porcentaje evaluado: <span>'.$porcent.'%</span></td>
	</tr>
  </table>
  
  
  ';
?>






<table id="infoPlan" border="1" width="100%" cellpadding="0" cellspacing="0" style="text-align:center; min-width:900px;">
<tr class="titlulosTr">
<td width="3%">N°</td>
<td>Apellidos y nombres</td>
<?php
$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");

$index=0;
while($reg=mysql_fetch_array($datosConcepto)){
	$nomC=$reg['nombre'];
	$porcenC=$reg['porcentaje'];
	$index++;
	echo "<td title='$nomC ($porcenC%) ' class='circulosConceptos'>$index</td>";
}

?>
<td>Acumulada</td>
<td>Faltas de asistencia</td>
</tr>

<?php

$notaInf="";
	if (file_exists('../sistemaEvaluativo/sistemaEvaluativo.xml')){
		$valido=true;
	    $xml=simplexml_load_file("../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		$notaInf=$xml->sistema['notaInferior'];
	}

$contador=0;
$datos=mysql_query("SELECT id,nombres,apellidos FROM matricula WHERE grupo='$grupo' ORDER BY apellidos,nombres");
while($reg=mysql_fetch_array($datos)){
	$contador++;
	$idM=$reg['id'];
	$nombresM=$reg['nombres'];
	$apellidosM=$reg['apellidos'];
	$claseCampos="camposE".$idM;
   echo "<tr>";
	echo "<td>$contador</td>";
	echo "<td style='text-align:left; padding-left:5px;'>$apellidosM $nombresM</td>";
	
	$promedio=0;
	$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");

	while($reg2=mysql_fetch_array($datosConcepto)){
		$porcentajeN=$reg2['porcentaje'];
		$nombrePorcent=$reg2['nombre'];
		$idC=$reg2['id'];
		$datos3=mysql_query("SELECT id,nota FROM planilla WHERE concepto='$idC' AND matricula='$idM'");
        while($reg3=mysql_fetch_array($datos3)){
			
			$idN=$reg3['id'];
			$notaN=$reg3['nota'];
			$masP=$notaN*($porcentajeN/100);
			$promedio+=$masP;
			$idcampo="campoN".$idN;
			$idDivImg="divImg".$idN;
			$titleC=$nombrePorcent."($porcentajeN%)";
			echo '<td class="tdNotas"><input data-porcentaje="'.$porcentajeN.'" title="'.$titleC.'" type="text" id="'.$idcampo.'" value="'.$notaN.'" onkeyup="modificarNota('.$idN.','.$idM.')" class="camposNotas '.$claseCampos.'" /><div class="imgCarga" id="'.$idDivImg.'"><img src="../imagenes/ok.png" class="imgOk" /></div></td>';
		}
	}
	
	$porcentDivi=($porcent==0)?1:$porcent;
	$promedio2=$promedio*100/$porcentDivi;
	$promedio2=round($promedio2,1);
	$classTd=($promedio2<$notaInf)?"tdPierdeMat":"tdGanaMat";
	$promedio=round($promedio,1);
	$idProm="idPro".$idM;
	echo "<td id='$idProm' data-notaInferior='$notaInf' class='$classTd'>$promedio ($promedio2)</td>";
	
	
	$exisF=false;
	$idF=0;
	$valF=0;
	$datosConcepto=mysql_query("SELECT id,falta FROM faltas WHERE  materia='$materia' AND matricula='$idM' AND anio='$anio' AND periodo='$periodo'");
	while($reg2=mysql_fetch_array($datosConcepto)){
		$idF=$reg2['id'];
		$valF=$reg2['falta'];
		$exisF=true;
	}
	
	if($exisF==false){
		mysql_query("INSERT INTO faltas (falta,matricula,materia,anio,periodo) VALUES ('0','$idM','$materia','$anio','$periodo')",$con);
	}
	
	$datosConcepto=mysql_query("SELECT id,falta  FROM faltas WHERE  materia='$materia' AND matricula='$idM' AND anio='$anio' AND periodo='$periodo'");
	while($reg2=mysql_fetch_array($datosConcepto)){
		$idF=$reg2['id'];
		$valF=$reg2['falta'];
	}
	
	$idcF="campoFalta".$idF;
	echo '<td><input value="'.$valF.'" type="text" onkeyup="modificarFaltas('.$idF.',this.value)" class="camposFaltas" /><div class="imgCarga2" id="'.$idcF.'"><img src="../imagenes/ok.png" class="imgOk" /></div></td>';
	
	
	
	
   echo "</tr>";
   
   
	
}





echo '</table><br /><br />
<div id="contenConceptosEva">';
if($porcent<100){
	
	$maxval=100-$porcent;
	
	echo '
	<form action="../procesos/profesor/planillaP.php" method="post">
	<table id="tablaNuevoConcepto" border="0" cellpadding="0" cellspacing="0">
	<tr>
	<td><input type="text" placeholder="Nuevo concepto de evaluación" class="camposNuevoConcepto" id="camDescipConcept" name="nConcep" required /></td>
	<td><input type="number" placeholder="Porcentaje" min="1" max="'.$maxval.'" class="camposNuevoConcepto" id="camPorcentConcept" name="pConcep" required  /></td>
	<td><input type="submit" class="btns" value="Crear" /></td>
	</tr>
	</table>
	</form>
	';
	
}

echo "<div><table style='text-align:center' border='1' cellpadding='0' cellspacing='0'>
  <tr class='titlulosTr'>
     <td width='30px'>#</td>
	 <td width='400px'>Concepto evaluación</td>
	 <td width='100px'>Porcentaje</td>
	 <td width='150px'>Editar/Eliminar</td>
  </tr>
";


$datosConcepto=mysql_query("SELECT id,nombre,porcentaje FROM conceptoevaluacion WHERE grupo='$grupo' AND materia='$materia' AND anio='$anio' AND periodo='$periodo'");
$index=0;
while($reg=mysql_fetch_array($datosConcepto)){
	$index++;
	$idC=$reg['id'];
	$nomC=$reg['nombre'];
	$porC=$reg['porcentaje'];
	$idc1="c1".$idC;
	$idc2="c2".$idC;
	$idbt1="bt1".$idC;
	$idbt2="bt2".$idC;
	$idbt3="bt3".$idC;
	$idbtEnvio="btnEnvio".$idC;
	$maxVal=100-$porcent+$porC;
	
	echo "
	<form action='../procesos/profesor/planillaP.php' method='post'><tr>
	 <td><div class='indexConceptos'>$index</div></td>
	 <td>
	<input type='hidden' value='$idC' name='IdConcept' /> <input type='text' id='$idc1' value='$nomC' class='camposConceptoTabla camposTablaConcept' name='NomConcept' readonly='true' required />
	</td>
	<td><input type='number' min='1' max='$maxVal' id='$idc2' value='$porC' name='PorcentCocept' class='camposPorcentTabla camposTablaConcept' readonly='true' required/></td>
	<td><input id='$idbtEnvio' type='submit' value='Guardar' class='btnsEnvio' />
	<table width='100%' cellpadding='0' cellspacing='0'><tr>
	<td><img id='$idbt1' src='../imagenes/iconoGuardar.png' style='display:none' title='Guardar' class='imagenesElimConcept' onclick='guardarCambios($idC)'  /></td>
	<td><img id='$idbt2' src='../imagenes/iconoCancelar.png' style='display:none' title='Cancelar' class='imagenesElimConcept' onclick='cancelarEdicion($idC)' /></td>
	<td><img id='$idbt3' src='../imagenes/iconoModificar.png' title='Modificar' class='imagenesElimConcept' onclick='abilitarModificacion($idC)' /></td>
	<td><img src='../imagenes/iconoEliminar.png' class='imagenesElimConcept' title='Eliminar' onclick='EliminarConceptoEv($idC)' /></td>
	</tr></table></td>
	</tr>
	</form>
	";
	
}
		
echo "</table></div>";

echo '</div></div>';

?>

</div>
<div id="recibeConten2" class="recibeConten">

<div id="contenTodoIndi">

<h1 style="font-size:22px">Indicadores de logro de <?php echo $nomMat."-".$nomGru; ?>
</h1>

<div style="padding-top:40px; padding-right:20px; padding-left:20px;">
<div id="contentablaIndi">


<table id="tablaIndi" cellpadding="0" cellspacing="0">

<tr id="titulotbindi">
<td width="80%">Descripción</td>
<td>Estado</td>
<td>Final</td>
</tr>

<?php

$grado=0;
$datos=mysql_query("SELECT grado FROM grupo WHERE id='$grupo'");
while($reg=mysql_fetch_array($datos)){
	$grado=$reg['grado'];
}


$datos=mysql_query("SELECT id,nombre,estado,final FROM indicadorlogro WHERE grado='$grado' AND materia='$materia' AND periodo='$periodo' ORDER BY fechaModificacion");
while($reg=mysql_fetch_array($datos)){
	$idInd=$reg['id'];
	$nomInd=$reg['nombre'];
	$estInd=$reg['estado'];
	$finInd=$reg['final'];
	
	$check1=($estInd=="si")?"checked":"";
	$check2=($finInd=="si")?"checked":"";
	
	$idImg1="imgCargaInd1".$idInd;
	$idImg2="imgCargaInd2".$idInd;
	
	echo '<tr>
	
	<td>'.$nomInd.'</td>
	<td><div class="dvscheckbox"><input type="checkbox" '.$check1.' onclick="cambiarEstadoInd('.$idInd.',this.checked)" /><div id="'.$idImg1.'" class="imgCarga2" ><img src="../imagenes/ok.png" class="imgOk" /></div></div></td>
	<td><div class="dvscheckbox"><input type="checkbox" '.$check2.' onclick="cambiarFinInd('.$idInd.',this.checked)" /><div id="'.$idImg2.'" class="imgCarga2" ><img src="../imagenes/ok.png" class="imgOk" /></div></div></td>
	</tr>';
	
}


?>



</table>


</div>
</div>
</div>





</div>


<div id="recibeConten3" class="recibeConten">



</div>



</div>
</center>

<form action="../procesos/profesor/planillaP.php" method="post" style="display:none">
<input type="text" id="IdConceptElim" name="IdConceptElim" readonly="readonly" required="required" />
<input type="submit" id="btnEnviarElimConcept" />
</form>


</body>
</html>



