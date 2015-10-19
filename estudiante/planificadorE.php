
<?php
include("../procesos/token3.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Planificador</title>
<?php
  $anioAct=$_SESSION['anio'];
  echo"<script>
  
  var anioActual=$anioAct
  
  </script>";
?>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/estudiante/planificadorE.js"></script>
<link rel="stylesheet" type="text/css" href="../css/estudiante/planificadorE.css"/>

</head>

<body style="min-width:1200px; overflow:auto">

<?php
include("menu.php");
?>

<center>


<div id="contenResultadosTareas">
<img src="../imagenes/flechaArriba.png" id="imgArribaResult" />
<div id="resultadosTareas">hola bb</div>
</div><br />
<center>
<div id="conten">

<?php
  $esRep=$_SESSION['EstRepresentante'];
	
	if($esRep=="si"){
		echo "<div id='textoInformativoRepresentanteGrupo'>Apreciado estudiante, este espacio es de gran importancia ya que permite tener un registro de todas las tareas, evaluaciones y clases que estan asignadas a su grupo. Usted al ser el representante tiene la obligación de registrar cada uno de los anteriores eventos para que el grupo en general esté informado de sus obligaciones.</div>";
	}
?>

<div id="todoIframe">
  <div id="contenNavegadores">
       
       <table style="float:left;">
       <tr><td><div class="coloresExp" style="background-color:rgba(255,0,0,0.5);"></div></td><td style="text-align:left">Evaluación</td></tr>
       <tr><td><div class="coloresExp" style="background-color:rgba(255,204,0,0.8);"></div></td><td style="text-align:left">Tarea</td></tr>
       <tr><td><div class="coloresExp" style="background-color:rgba(0,153,102,1);"></div></td><td style="text-align:left">Clase</td></tr>
       </table>
       <div id="navMeseIzq" style="margin-left:-100px" class="btnNavegadores" onclick="navegarReservas(0)"></div>
                <div id="ContenInfoMes">
                    <div class="itemSelect">
                        <div id="valorMes"></div>
                        <div class="FlechaAbajo"></div>
                        <?php echo $anioAct; ?>
                   </div>
                     <div class="dvUl" id="contenMeses"></div>
                </div>
    <div id="navMeseDer" class="btnNavegadores" onclick="navegarReservas(1)"></div>
  </div>
  
  <?php
    
	$esRep=$_SESSION['EstRepresentante'];
	
	if($esRep=="si"){
		 
		 echo '
		 
		 <div id="contenFomrNuevaTarea">
  
  <form action="../procesos/estudiante/planificadorEP.php" method="post">
  
   <table width="100%">
   <tr>
   <td><label class="lblTitFomr">Programar:</label></td>
   <td>
   <select class="camposForm" name="tipoTarea" required="required" onchange="cambiarColorTarea(this.value)">
   <option>Evaluación</option>
   <option>Tarea</option>
   <option>Clase</option>
   </select>
   </td>
   <td>
   <div class="coloresTareas" id="colorTarea1"></div>
   <div class="coloresTareas" id="colorTarea2"></div>
   <div class="coloresTareas" id="colorTarea3"></div>
   </td>
   
   <td><label class="lblTitFomr">Fecha:</label></td>
   <td>
    <input type="date" style="width:123px;" class="camposForm" name="fechaTarea" id="fechaTarea" required="required" onchange="cambiarFechaCampo(this.value)" />
   </td>
  
   
   <td><label class="lblTitFomr">Materia:</label></td>
   <td>
    <select class="camposForm" name="materiaTarea" style="width:120px;" id="selectMateriaTarea" required="required">
     <option></option>';
   
       $grupo=$_SESSION['idGrupoEstudiante'];	 
	   $datos=mysql_query("SELECT m.id, m.nombre FROM horario h INNER JOIN materia m ON h.materia=m.id WHERE h.grupo='$grupo' GROUP BY h.materia ORDER BY m.nombre");
		 while($reg=mysql_fetch_array($datos)){
			 $idM=$reg['id'];
			 $nomM=$reg['nombre'];
			 echo "<option value='$idM'>$nomM</option>";
		 }
   
   
   
  
   echo'</select>
   <img src="../imagenes/barra.gif" width="100" id="imgCragaMaterias" style="display:none" />
   </td>
   
   <td><label class="lblTitFomr">Descripción:</label></td>
   
   <td><textarea name="descripcionTarea" required="required"></textarea></td>
   
   <td><input type="submit" value="Guardar" class="btns" onclick="return ValidarCampos()"  /></td>
   </tr>
   </table>
  </form>
  
  </div>
		 
		 ';
		
	}
  
  ?>
  
  
   <div id="ContenTablareservas">
   
   <div id="cuadro1" class="cuadros"></div>
  
   </div>

</div>

</center>

</body>

</center>

</body>
</html>