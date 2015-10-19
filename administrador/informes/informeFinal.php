<?php
include("../../procesos/tokenInf.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" href="../../css/administrador/informesUnitarios.css"/>
<script type="text/javascript" src="../../js/administrador/informes/informeFinal.js"></script>
</head>

<body>
<center>
<?php
$_SESSION['itemsel']="liInf";

?>

  <table cellpadding="0" id="tablaselectores" cellspacing="0" style="text-align:center" width="80%">
  <tr>
  
  <td><label class="lbltitulosSelect">Año:</label></td>
  <td>
  <select onchange="ElegirAnio(this.value)" id="selectAnio" class="selectores">
     <option></option>
     <?php
	 
	   $idCentro=$_SESSION['CentroEducativo'];
	   $datos=mysql_query("SELECT c.anio FROM grupo g
	                       INNER JOIN conceptoevaluacion c ON c.grupo=g.id
	                       WHERE g.centroeducativo='$idCentro'
						   GROUP by c.anio
	                        ");
		 while($reg=mysql_fetch_array($datos)){
			 $anio=$reg['anio'];
			 echo "<option value='$anio'>$anio</option>";
		 }
	 ?>
  </select>
  </td>
  
  <td><label class="lbltitulosSelect">Grupo:</label></td>
  <td>
  
   <div class="contenBarrasLoader" id="contenBarraGrupo">
  <img src="../../imagenes/barra.gif" class="barrasLoader" width="70" />
  </div>
  <div id="contenSelectGrupo">
  <select id="selectGrupo" class="selectores" onchange="ElegirGrupo(this.value)">
     
  </select>
  </div>
  </td>
  
  <td><label class="lbltitulosSelect">Estudiante:</label></td>
  <td>
  
  <div class="contenBarrasLoader" id="contenBarraEst">
  <img src="../../imagenes/barra.gif" class="barrasLoader" />
  </div>
  <div id="contenSelectEst">
  <select id="selectEst" class="selectores" onchange="cambiarBotones(0)">
     
  </select>
  </div>
  </td>
  
  <td>
  
 <button class="btnsEnvio" id="btnGenerrarInforme" style="width:150px; margin-left:10px;" onclick="generarInformeFinal()">Generar informe</button>
  <button class="btnsEnvio" id="btnImprimir" style="width:150px; margin-left:10px; display:none" onclick="imprimirInforme()">Imprimir</button>
 
  </td>
  
  </tr>
</table>
 <br />
 
 <div id="recibeDatos">
 
 
 
 </div>
 <div id="divError1" class="errordatos"></div>
</center>


</body>
</html>