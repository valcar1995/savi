

<?php
include("../procesos/token2.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Areas</title>
<link rel="stylesheet" type="text/css" href="../css/profesor/areas.css"/>
<script type="text/javascript" src="../js/profesor/areas.js"></script>


</head>

<body>
<?php include("menu.php") ?>
<div id="menu">
<ul>
<li id="menuAreaElegido" class="areasMenu" onclick="cambiarMenuArea(1)">Planillas-periodo</li>
<li id="areaMenu2" class="areasMenu" onclick="cambiarMenuArea(2)">Áreas-estudiantes</li>
<li id="areaMenu3" class="areasMenu" onclick="cambiarMenuArea(3)">Estudiantes de<br /> ingreso tardío</li>


</ul>
</div>

<center>

<div id="contenArea1" style="display:block" class="contenAreas">

<table cellpadding="0" cellspacing="0" style="text-align:center" width="80%">
  <tr>
  
  <td><label class="lbltitulosSelect">Grupo:</label></td>
  <td>
  <select onchange="ElegirGrupo(this.value)" id="selectGrupo" class="selectores">
     <option value="nada"></option>
     <?php
	 
	   $prof=$_SESSION['idProfesor'];
	 
	   $datos=mysql_query("SELECT g.id, g.nombre FROM horario h 
	                       INNER JOIN grupo g ON g.id=h.grupo 
						   INNER JOIN grado gr ON g.grado=gr.id
						   WHERE h.profesor='$prof' 
						   GROUP BY h.grupo 
						   ORDER BY gr.numero");
		 while($reg=mysql_fetch_array($datos)){
			 $idG=$reg['id'];
			 $nomG=$reg['nombre'];
			 echo "<option value='$idG'>$nomG</option>";
		 }
	 ?>
  </select>
  </td>
  
  <td><label class="lbltitulosSelect">Materia:</label></td>
  
  <td>
  <div class="contenBarrasLoader" id="contenBarraMateria">
  <img src="../imagenes/barra.gif" class="barrasLoader" />
  </div>
  <div id="contenSelectMateria">
  
  <select id="selectMateria" class="selectores" onchange="ElegirMateria(this.value)">
    
  </select>
  </div>
  </td>
  
  
  <td><label class="lbltitulosSelect">Periodo:</label></td>
  <td>
  
  
  <select id="selectPeriodo" class="selectores">
      <?php
	    if (file_exists('../sistemaEvaluativo/sistemaEvaluativo.xml')){
	    $xml=simplexml_load_file("../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		foreach ($xml->sistema->periodos->periodo as $periodo) {
		   $per=$periodo['nombre'];
		   echo "<option value='$per'>$per</option>";
		}
		}
	  
	  ?>
  </select>
  </td>
  
  <td><label class="lbltitulosSelect">Año:</label></td>
  <td>
  
  <div class="contenBarrasLoader" id="contenBarraAnio">
  <img src="../imagenes/barra.gif" class="barrasLoader" />
  </div>
  <div id="contenSelectAnio">
  <select id="selectAnio" class="selectores">
     
  </select>
  </div>
  </td>
  
  <td>
  <button class="btnsEnvio" onclick="generarInformePlanillaPeriodo()">Generar informe</button>
  </td>
  
  </tr>
</table>
<div id="divError1" class="errordatos"></div>
<br />
<hr />
<div id="recibeInformePlanillaPeriodo"></div>

</div>


<div id="contenArea2" class="contenAreas">


<table cellpadding="0" cellspacing="0" style="text-align:center" width="80%">
  <tr>

  <td><label class="lbltitulosSelect">Seleccione un grupo</label></td>
  <td><select class="selectores" id="selecGrupoAreaInf" onchange="cambiarGrupoInf(this.value)">
  <option></option>
  <?php
    $prof=$_SESSION['idProfesor'];
	 
	   $datos=mysql_query("SELECT g.id, g.nombre FROM horario h 
	                       INNER JOIN grupo g ON g.id=h.grupo 
						   INNER JOIN grado gr ON g.grado=gr.id
						   WHERE h.profesor='$prof' 
						   GROUP BY h.grupo 
						   ORDER BY gr.numero");
		 while($reg=mysql_fetch_array($datos)){
			 $idG=$reg['id'];
			 $nomG=$reg['nombre'];
			 echo "<option value='$idG'>$nomG</option>";
		 }
	
	
  ?>
  </select></td>
  
  <td><label class="lbltitulosSelect">Seleccione un área</label></td>
  
  <td><select class="selectores" id="selecAreaInf" onchange="cambiarAreaInf(this.value)">
  
  </select>
  <img src="../imagenes/barra.gif" width="150" id="imgCargaAreas" style="display:none" />
  </td>
 
 
 
 <td><label class="lbltitulosSelect">Periodo:</label></td>
  <td>
  
  
  <select id="selectPeriodoAreaInf" class="selectores">
      <?php
	    if (file_exists('../sistemaEvaluativo/sistemaEvaluativo.xml')){
	    $xml=simplexml_load_file("../sistemaEvaluativo/sistemaEvaluativo.xml", 'SimpleXMLElement',LIBXML_NOCDATA);
		foreach ($xml->sistema->periodos->periodo as $periodo) {
		   $per=$periodo['nombre'];
		   echo "<option value='$per'>$per</option>";
		}
		}
	  
	  ?>
  </select>
  </td>
 
 
 <td><label class="lbltitulosSelect">Año:</label></td>
  <td>
  
  
  
  <select id="selectAnioAreaInf" class="selectores">
       
  </select>
  
  <img src="../imagenes/barra.gif" width="150" id="imgCargaAnioArea" style="display:none" />
 
  </td>
 
 
 <td> <button class="btnsEnvio" onclick="generarInformeAreasEstudiantes()">Generar informe</button></td>
 
  </tr></table>
  <br />
  <div id="errordatos2" class="errordatos"></div>
  <hr />
  <br />
  <div id="recibeInformeAreaEstudiante"></div>
  
</div>



<div id="contenArea3" class="contenAreas">
  <br />
   <label class="lbltitulosSelect">Grupo:</label>
  <select id="selectGrupoEstTard" class="selectores">
     <option></option>
     <?php
	 
	   $prof=$_SESSION['idProfesor'];
	 
	   $datos=mysql_query("SELECT g.id, g.nombre FROM horario h 
	                       INNER JOIN grupo g ON g.id=h.grupo 
						   INNER JOIN grado gr ON g.grado=gr.id
						   WHERE h.profesor='$prof' 
						   GROUP BY h.grupo 
						   ORDER BY gr.numero");
		 while($reg=mysql_fetch_array($datos)){
			 $idG=$reg['id'];
			 $nomG=$reg['nombre'];
			 echo "<option value='$idG'>$nomG</option>";
		 }
	 ?>
  </select>
 
  <button class="btnsEnvio" onclick="BuscarEstIngresoTardio()">Buscar Estudiantes</button>
  
  <div id="divError3" class="errordatos"></div>
  <br />
  <hr />
  <div style="width:1000px;">
  <div class="txtInformativo">Este espacio es el lugar donde se registran las notas de periodos pasados de aquellos estudiantes que ingresaron a la institución de forma tardía. Ej. Para un estudiante que ingresa a la institución en el periodo 2 se deben ingresar las notas finales correspondientes al periodo 1 para cada materia. </div>
  <br /><div id="recibeEstudiantesTardios">
  <?php
	@$ver=$_GET['ver'];
	if(isset($ver)){
		echo "<div class='notificaciones'>La nota se registró exitosamente</div>";
	}
	?>
  </div>
  </div>
  
</div>


</center>
<?php
@$ver=$_GET['ver'];
if(isset($ver)){
	echo "
	<script>
	cambiarMenuArea($ver)
	</script>
	
	";
}
?>
</body>
</html>
