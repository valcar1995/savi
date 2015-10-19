
<?php
include("../procesos/token3.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Acumulada</title>
<script type="text/javascript" src="../js/jquery/jquery.min.js"></script>
<script type="text/javascript" src="../js/estudiante/acomuladaE.js"></script>
<link rel="stylesheet" type="text/css" href="../css/estudiante/acomuladaE.css"/>

</head>

<body style="min-width:1200px; overflow:auto" onload="iniciar()">

<?php
include("menu.php");
?>
<br />
<center>

<label class="titulosCampos">Seleccione un área:</label>
<select id="selectArea" class="campos">
<option value="Todas">Todas</option>
<?php

$grupo=$_SESSION['idGrupoEstudiante'];
$anio=$_SESSION['anio'];
$datos=mysql_query("SELECT a.nombre,a.id FROM horario h INNER JOIN materia m ON h.materia=m.id INNER JOIN area a ON m.area=a.id  WHERE h.grupo='$grupo' AND h.anio='$anio' GROUP BY a.id ORDER BY a.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $id=$reg['id'];
			 $nombre=$reg['nombre'];
			 
			 echo "<option value='$id'>$nombre</option>";
		 }

?>

</select>




<button id="btnBuscarArea" class="btns" onclick="buscarArea()">Buscar</button><br /><br />
<hr />

<div id="contenResultados"></div>
</center>

</body>
</html>