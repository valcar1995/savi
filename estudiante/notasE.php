
<?php
include("../procesos/token3.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Notas</title>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/estudiante/notasE.js"></script>
<link rel="stylesheet" type="text/css" href="../css/estudiante/notasE.css"/>

</head>

<body style="min-width:1200px; overflow:auto" onload="iniciar()">

<?php
include("menu.php");
?>
<br />
<center>

<label class="titulosCampos">Seleccione una materia:</label>
<select id="selectMateria" class="campos">
<option value="Todas">Todas</option>
<?php

$grupo=$_SESSION['idGrupoEstudiante'];
$anio=$_SESSION['anio'];
$datos=mysql_query("SELECT m.nombre,m.id FROM horario h INNER JOIN materia m ON h.materia=m.id  WHERE h.grupo='$grupo' AND h.anio='$anio' GROUP BY m.id ORDER BY m.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $id=$reg['id'];
			 $nombre=$reg['nombre'];
			 
			 echo "<option value='$id'>$nombre</option>";
		 }

?>

</select>

<label class="titulosCampos">Periodo:</label>
<select id="selectPeriodo" class="campos">
<?php

$matricula=$matricula=$_SESSION['idEstudiante'];
$periodo=$_SESSION['periodo'];
echo "<option value='$periodo'>$periodo</option>";
$grupo=$_SESSION['idGrupoEstudiante'];
$anio=$_SESSION['anio'];

$datos=mysql_query("SELECT c.periodo FROM planilla p INNER JOIN conceptoevaluacion c ON p.concepto=c.id WHERE p.matricula='$matricula' AND c.periodo!='$periodo'  GROUP BY c.periodo ORDER BY c.periodo");
		 while($reg=mysql_fetch_array($datos)){
			 $periodoC=$reg['periodo'];
			 echo "<option value='$periodoC'>$periodoC</option>";
		 }
?>

</select>


<button id="btnBuscarMateria" class="btns" onclick="buscarMateria()">Buscar</button><br /><br />
<hr />

<div id="contenResultados"></div>
</center>

</body>
</html>