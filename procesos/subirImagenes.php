
<style>
*{margin:0px; padding:0px;}
.envimagenes{
	position:absolute;
}
</style>

<body>

<?php
include("conexion.php");

//subir el logo de un centro educativo
@$logoImg=$_POST['subirLogoCentro'];
if(isset($logoImg)){
	
	$ultimoCentro=0;
	$datos=mysql_query("SELECT id FROM centroeducativo ORDER BY id DESC LIMIT 1");
    while($reg=mysql_fetch_array($datos)){
	  $centroId=$reg['id'];
	  $ultimoCentro=$centroId+1;
	}
	
	$nombre=$_FILES ['reCenLogo']['name'];
	$porciones = explode(".", $nombre);
	$formato=$porciones[1];
	$nombreImg=$ultimoCentro.".".$formato;
	
    $carpeta= "../imagenes/centros/";
	opendir ($carpeta);
    $desino=$carpeta.$nombreImg;
    copy ($_FILES['reCenLogo']['tmp_name'],$desino);
    $srcImagen=$carpeta.$nombreImg;
	
	echo "<img src='$srcImagen' title='$nombreImg' height='150' width='200' class='envimagenes' id='imagenLogoCentro' />";
}

// cargar el logo por defecto 
@$LogoDefecto=$_GET['def'];
if(isset($LogoDefecto)){
	echo "<img src='../imagenes/centros/0.jpg' title='0.jpg' height='150' width='200' class='envimagenes' id='imagenLogoCentro' />";
}

// subri logo en edición
@$logEdit=$_POST['idLogoEdit'];
if(isset($logEdit)){
	
	$datos=mysql_query("SELECT logo FROM centroeducativo WHERE id='$logEdit'");
    while($reg=mysql_fetch_array($datos)){
	  $logoCent=$reg['logo'];
	  if($logoCent!="0.jpg"){
	  unlink("../imagenes/centros/$logoCent");
	  }
	}
	
	$nombre=$_FILES ['AcCenLogo']['name'];
	$porciones = explode(".", $nombre);
	$formato=$porciones[1];
	$nombreImg=$logEdit.".".$formato;
	
    $carpeta= "../imagenes/centros/";
	opendir ($carpeta);
    $desino=$carpeta.$nombreImg;
    copy ($_FILES['AcCenLogo']['tmp_name'],$desino);
    $srcImagen=$carpeta.$nombreImg;
	
	mysql_query("UPDATE centroeducativo SET logo='$nombreImg' WHERE id='$logEdit'",$con);
	
	echo "<img src='$srcImagen' title='$nombreImg' height='150' width='200' class='envimagenes' id='imagenLogoCentro' />";
	
}

// cargar logo por defecto edicion
@$editDef=$_GET['defEdit'];
if(isset($editDef)){
	echo "<img src='../imagenes/centros/$editDef' title='0.jpg' height='150' width='200' class='envimagenes' id='imagenLogoCentro' />";
}



?>

</body>