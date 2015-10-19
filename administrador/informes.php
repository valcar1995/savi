<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Informes</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/informes.css"/>
<script type="text/javascript" src="../js/administrador/informes.js"></script>
</head>

<body>
<div id="toltip">
<img src="../imagenes/flechaArriba.png" id="imgArribaResult" />
<div id="descripcionToltip">hola bb</div>
</div>
<?php
$_SESSION['itemsel']="liInf";
include("busquedas.php");
?>
<center>
<div id='todo' style="padding-left:250px; padding-top:10px;">
  <a href="ampiarInforme.php?urlInforme=informeFinalPeriodo.php&tituloInf=Informe Final">
  <div class="informes" onmousemove="verDescripcion(this,event)" onmouseout="quitarDescripcion(this,event)">
   <img src="../imagenes/iconoInforme.jpg" class="imgInformes" />
   <div class="tituloInforme">Informe final del periodo</div>
   <div class="descripcionInforme">Informe final de la calificación de las areas y materias de cada estudiante por periodo.</div>
  </div>
  </a>
  
  <a href="ampiarInforme.php?urlInforme=informeFinal.php&tituloInf=Informe Final">
  <div class="informes" onmousemove="verDescripcion(this,event)" onmouseout="quitarDescripcion(this,event)">
   <img src="../imagenes/iconoInforme.jpg" class="imgInformes" />
   <div class="tituloInforme">Informe final del año</div>
   <div class="descripcionInforme">Informe final de la calificación de las areas y materias de cada estudiante de todo el año.</div>
  </div>
  </a>
  </div>
  
 
  
</center>


</body>
</html>