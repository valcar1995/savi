

<?php
include("../procesos/token2.php");
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Grupos</title>
<link rel="stylesheet" type="text/css" href="../css/profesor/grupos.css"/>
<script type="text/javascript" src="../js/profesor/grupos.js"></script>


</head>

<body>
<?php include("menu.php") ?>
<div id="menu">
<ul>
<?php

    $profe=$_SESSION['idProfesor'];
	$anio=date("Y");
	
	$centro=$_SESSION['CentroEducativo'];
	$datos=mysql_query("SELECT anio FROM centroeducativo WHERE id='$centro' ");
	 while($reg=mysql_fetch_array($datos)){
		 $anio=$reg['anio'];
	 }
	 
	 $respuesta="";
	 $index=0;
	 $datos=mysql_query("SELECT g.id,g.nombre FROM horario h
	                     INNER JOIN grupo g ON h.grupo=g.id 
						 INNER JOIN grado gr ON g.grado=gr.id
						 WHERE h.profesor='$profe' AND h.anio='$anio' 
						 GROUP BY h.grupo
						 ORDER BY gr.numero");
	 while($reg=mysql_fetch_array($datos)){
		 $idg=$reg['id'];
		 $nombreg=$reg['nombre'];
		 $index++;
		 $idli="grupo".$index;
		 
		 echo'<li id="'.$idli.'" class="gruposMenu" onclick="cambiarGrupo('.$idg.','.$index.')">'.$nombreg.'</li>';
	 }

?>
</ul>
</div>

<center>

<div id="recibeGrupos"></div>

</center>
<?php

$item=$_SESSION['grupoItemElegido'];
$item="grupo".$item;
echo "<script>
document.getElementById('$item').click()
</script>";

?>
</body>
</html>



