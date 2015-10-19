
<?php
session_start();
session_destroy();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Error</title>

<style>
body{background-color:rgba(102,102,102,1)}
#cuadroDialogo{
	padding:30px;
	background-color:rgba(255,255,255,1);
	border:4px solid rgba(0,102,255,1);
	width:700px;
	margin-top:5%;
}
#mensajeError{
	font-size:24px;
	color:rgba(153,0,0,1);
	font-weight:bold;	
}

a{text-decoration:none;}
#volver{
	padding:5px;
	border:0px solid;
	background-color:rgba(0,102,153,1);
	color:rgba(255,255,255,1);
	font-size:18px;
	cursor:pointer;
	border-radius:4px;	
}
</style>

</head>

<body>

<center>
<div id="cuadroDialogo">
<div><img height="300" width="400" src="imagenes/error.png" /></div><br />
<div id="mensajeError">
<?php
@$err=$_REQUEST['error'];
if(isset($err)){
	echo $err;
}
?>
</div>
<br />
<a href="index.php"><button id="volver">Volver al inicio</button></a>

</div>
</center>


</body>
</html>
