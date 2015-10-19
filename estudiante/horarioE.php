
<?php
include("../procesos/token3.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Horario</title>
<script type="text/javascript" src="../js/estudiante/horarioE.js"></script>
<link rel="stylesheet" type="text/css" href="../css/estudiante/horarioE.css"/>
</head>

<body onload="iniciar()" style="min-width:1300px; overflow:auto">

<?php
include("menu.php");
?>

<center>


<table style=" width:1100px; position:relative; overflow:auto">

<tr>
<td></td>

<td style="text-align:left">
<div id="columnadiaC1" class="diasHorario"><div id="columnadia1" class="columnasDias" style=" margin-left:-2px; border-left:1px solid rgba(204,204,204,1);"></div>Lunes</div>
<div id="columnadiaC2" class="diasHorario"><div id="columnadia2" class="columnasDias"></div>Martes</div>
<div id="columnadiaC3" class="diasHorario"><div id="columnadia3" class="columnasDias"></div>Miércoles</div>
<div id="columnadiaC4" class="diasHorario"><div id="columnadia4" class="columnasDias"></div>Jueves</div>
<div id="columnadiaC5" class="diasHorario"><div id="columnadia5" class="columnasDias"></div>Viernes</div>
<div id="columnadiaC6" class="diasHorario"><div id="columnadia6" class="columnasDias"></div>Sábado</div>
<div id="columnadiaC7" class="diasHorario"><div id="columnadia7" class="columnasDias"></div>Domingo</div>

</td>


</tr>

<tr valign="top">

<td width="3%">
<div id="contenHorasVerticales"></div>

</td>

<td width="50%" style="text-align:right">
<div id="lineaTiempo"></div>
<div id="recibeTablasHorario">

<?php

$idEst=$_SESSION['idEstudiante'];
$anio=$_SESSION['anio'];


$grupo=0;
 $datos=mysql_query("SELECT grupo FROM matricula WHERE id='$idEst'");
  while($reg=mysql_fetch_array($datos)){
	  $grupo=$reg['grupo']; 
  }
  
  $datos=mysql_query("SELECT * FROM horario WHERE grupo='$grupo' AND anio='$anio'");
  while($reg=mysql_fetch_array($datos)){
	  $h1=$reg['horaInicio'];
	  $h2=$reg['horaFin'];
	  $profe=$reg['profesor'];
	  $materia=$reg['materia'];
	  $dia=$reg['dia'];
	  
	  $datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
	  while($reg2=mysql_fetch_array($datos2)){
		  $materia=$reg2['nombre']; 
	  }
	  
	  $datos2=mysql_query("SELECT nombres, apellidos FROM profesor WHERE id='$profe'");
	  while($reg2=mysql_fetch_array($datos2)){
		  $profe=$reg2['nombres']." ".$reg2['apellidos'];; 
	  }
	  
	  echo agregarHorario($dia,$h1,$h2,$materia,$profe);
	  
  }
  


function agregarHorario($dia,$h1,$h2,$mate,$profesor){
	$diferenciaHoras=rangoHoras($h1,$h2);
	$horaInicial=convertirHora($h1);
	
	$pos1=obtenerPosHora($horaInicial);
	$h=obtenerPosHora(($horaInicial+$diferenciaHoras));
	$h=($h-$pos1)."px";
	$pos1.="px";
	$posDia=((obtenerposDia($dia))*139)."px";
	return "<div class='ranfost' style='margin-top:".$pos1."; margin-left:$posDia; height:".$h.";  max-height:".$h." '><div class='nombreGrado'>".$mate."</div><div class='nombreMateria'>".$profesor."</div></div>";
	
	
}

function obtenerposDia($dia){
	$diasSemana = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
	$fin=count($diasSemana);
	for($i=0; $i<$fin; $i++){
		if($dia==$diasSemana[$i]){
			return $i;
		}
	}
}

function rangoHoras($h1,$h2){
 

 $hora1=convertirHora($h1);
 $hora2=convertirHora($h2);
 $hora=$hora2-$hora1;
 return $hora;
 
}

function convertirHora($hor){
	$hsp=split(":",$hor);
	$hora=intval($hsp[0]);
	$minutos=intval($hsp[1]);
	return ($minutos!=0)?$hora+($minutos/60):$hora;
}


function obtenerPosHora($h1){
	return (($h1-5)*40);
}

?>
</div>
</td>
</tr>

</table>

</center>

</body>
</html>