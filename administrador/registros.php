

<?php
//include("../procesos/token.php");
?>
<link rel="stylesheet" type="text/css" href="../css/administrador/registros.css"/>
<script type="text/javascript" src="../js/administrador/registros.js"></script>
<header>
<div id="cabesa">
<table style="float:left">
<tr>
<td>
<img src="../imagenes/logo.jpg" id="logo" />
</td>
</tr>
<tr>
<td style="text-align:center">
<?php
echo $_SESSION['nombreUsurio'];
?>
</td>
</tr>
</table>


<div style="height:0px">

<div id="tablaNormal">
<table>
<?php
$idCentro=$_SESSION['CentroEducativo'];
$anio=date("Y");
$periodo=1;
$nombre="";
$logo="0.jpg";
$datos=mysql_query("SELECT id,nombre,anio,periodo,logo FROM centroeducativo WHERE id='$idCentro'");
while($reg=mysql_fetch_array($datos)){
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$anio=$reg['anio'];
		$periodo=$reg['periodo'];
		$logo=$reg['logo'];
		
		echo'
		<tr>
		    <td><img src="../imagenes/centros/'.$logo.'" class="logosCentros" /></td>
			<td ><div class="tdstitulos"><label class="textosCabesa" >Centro Actual:</label></div></td>
			<td><label class="datosCabesa">'.$nombre.'</label></td>
			
			<td><div class="tdstitulos"> <label class="textosCabesa" >Año:</label></div></td>
			<td><label class="datosCabesa">'.$anio.'</label></td>
			<td ><div class="tdstitulos"><label class="textosCabesa" >Periodo:</label></div></td>
			<td><label class="datosCabesa">'.$periodo.'</label></td>
			
			<td><button class="btnCambiarCentro" onclick="mostrarEdicion()">Editar</button></td>
			</tr>
		';
							  
}

?>
</table>
</div>

<div id="tablaEditable" style="display:none">
<table><tr>
<?php
 echo'<td><img src="../imagenes/centros/'.$logo.'" class="logosCentros" /></td>';
?>
<td><label class="textosCabesa" >Centro Actual:</label></td>
<td>
<select id="selectCentro" onchange="obtenerAnioPeriodoCentro(this.value)">
<?php
$idCentro=$_SESSION['CentroEducativo'];
echo"<option value='$idCentro'>$nombre</option>";

$idUsu=$_SESSION['idUsuario'];

$datos=mysql_query("SELECT a.id,a.nombre FROM centroeducativo a INNER JOIN usuariocentros b ON a.id=b.centro WHERE a.id!='$idCentro' AND b.usuario='$idUsu' ORDER BY a.nombre");
while($reg=mysql_fetch_array($datos)){
		$id=$reg['id'];
		$nom=$reg['nombre'];
		
		echo"<option value='$id'>$nom</option>";
		
}
?>
</select>
</td>
<td><div class="tdstitulos"> <label class="textosCabesa" >Año:</label></div></td>
<td>
<?php
echo '<input type="text" id="campoAnioActual" value="'.$anio.'" />';
?>
</td>
<td><div class="tdstitulos"> <label class="textosCabesa" >Periodo:</label></div></td>
<td>
<?php
echo '<input type="text" id="campoPeriodoActual" value="'.$periodo.'" />';
?>
</td>
<td><button class="btnCambiarCentro" id="btnGuardarCambioCentro" onclick="CambiarInfoCabesa()">Guardar</button></td>
</tr></table>
</div>





</div>

<div id='contenIconosMenu'>
<a href="#">
<img src="../imagenes/menu.png" id='iconoMenu' />
</a>
<?php
$url1="re".$_SESSION['urlActual'].".php";
$url2="bu".$_SESSION['urlActual'].".php";

echo '
<a href="'.$url1.'" title="Registrar">
<img src="../imagenes/mas.png" id="itemMenuSelect" class="iconosMenu" />
</a>
<a href="'.$url2.'" title="Buscar">
<img src="../imagenes/buscar.png" class="iconosMenu" />
</a>
';

?>
<a href="../procesos/cerrar.php" title="Cerrar sesión">
<img src="../imagenes/salir.png" class="iconosMenu" />
</a>
</div>

</div>
</header>
<br /><br /><br />
<div id="menu">
<ul>
<a href="reUsuarios.php"><li id="liUsu">Usuario</li></a>
<a href="reMatricula.php"><li id="liMat">Matricula</li></a>
<a href="reProfesor.php"><li id="liPro">Profesor</li></a>
<a href="reArea.php"><li id="liAre">Área</li></a>
<a href="reMateria.php"><li id="liMate">Materia</li></a>
<a href="reHorario.php"><li id="liHor">Horario</li></a>
<a href="reGrupo.php"><li id="liGru">Grupo</li></a>
<a href="reGrado.php"><li id="liGra">Grado</li></a>
<a href="reIndicador.php"><li id="liInd">Indicador de logro</li></a>
<a href="reAcudiente.php"><li id="liAcu">Acudiente</li></a>
<a href="reCentro.php"><li id="liCen">Centro Educativo</li></a>
</ul>
</div><br />

<?php

@$itemsel=$_SESSION['itemsel'];

if(isset($itemsel)){
	
	echo"
	<script>
	
	var cam=document.getElementById('$itemsel')
	cam.style.backgroundColor='rgba(102,102,102,1)'
	
	</script>
	
	";
}



?>
