

<?php
include("../procesos/token2.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Horario</title>
<link rel="stylesheet" type="text/css" href="../css/profesor/horario.css"/>
<script type="text/javascript" src="../js/profesor/horario.js"></script>


</head>
<body onload="iniciar()">
<?php include("menu.php") ?>
<div id="menu">
<ul>
<li id="dia1" class="diasMenu" onclick="cambiarDia(1)">Lunes</li>
<li id="dia2" class="diasMenu" onclick="cambiarDia(2)">Martes</li>
<li id="dia3" class="diasMenu" onclick="cambiarDia(3)">Miércoles</li>
<li id="dia4" class="diasMenu" onclick="cambiarDia(4)">Jueves</li>
<li id="dia5" class="diasMenu" onclick="cambiarDia(5)">Viernes</li>
<li id="dia6" class="diasMenu" onclick="cambiarDia(6)">Sábado</li>
<li id="dia7" class="diasMenu" onclick="cambiarDia(7)">Domingo</li>
</ul>
</div>

<center>

<table width="600px">
<tr valign="top">

<td width="5%">
<div id="contenHorasVerticales"></div>

</td>

<td width="50%" style="text-align:right"><div id="recibeTablasHorario"></div><div id="lineaTiempo"></div></td>
</tr>

</table>

</center>

</body>
</html>



