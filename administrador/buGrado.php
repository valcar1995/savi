<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Buscar Grado</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<link rel="stylesheet" type="text/css" href="../css/administrador/busquedas.css"/>
<script type="text/javascript" src="../js/general.js"></script>
<script type="text/javascript" src="../js/administrador/buGrado.js"></script>
<script type="text/javascript" src="../js/administrador/reGrado.js"></script>

</head>

<body onload="iniciar()">

<?php
$_SESSION['urlActual']="Grado";
$_SESSION['itemsel']="liGra";
include("busquedas.php");
?>
<center>
<div id="todo" style="padding-left:197px;">
    <div id="todo2"></div>
    <div id="contenBusqueda">
    
        <div style="text-align:left; display:inline-table">
        
            <input type="text" placeholder="Nombre..."  class="filtros" id="campoBuscar" />
            <button class="btnsEnvio" onclick="filtrarDatos()">Buscar</button>
            <div id="resultados">
            
            </div>
            
            <div id="pagin"></div>
            
            </div>
        
        
        
        </div>
    </div>

</div>
</center>
<?php

@$error=$_GET['error'];
if($error=="no"){
	echo"
	<script>
	alert('Modificación realizada exitosamente')
	</script>
	
	";
}
if($error=="si"){
	echo"
	<script>
	alert('Hubo un problema al realizar la Modificación')
	</script>
	
	";
}

?>

</body>
</html>