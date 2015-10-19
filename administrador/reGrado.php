<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Grado</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reGrado.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Grado";
$_SESSION['gradoEdit']=0;
$_SESSION['itemsel']="liGra";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarGrado.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO GRADO</legend>
                            
                            <p>
                                <label for="username">Nombre</label>
                                <input id="reGraNom" name="reGraNom" onkeyup="validarGrado(this.value)" required />
                                <span class="error" id="existeGra"></span>
                            </p>
                              
                            <p>
                                <label for="username">Número relacionado</label>
                                <input id="reGraNom" type="number" name="reGraNu" required />
                                <span class="mensajesAviso">El número relacionado tiene que ver con el valor numérico del grado. Ej. 1 si es primero, 2 si es segundo, 6 si es sexto... Este valor permitirá visualizar la información de forma más organizada.</span>
                            </p>
                            
                            <p class="submit">
                                <button id="registerButton" type="submit">Registrar</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
				</div>
               
            </div>
        </div>

</center>
</div>

<?php

@$error=$_GET['error'];
if($error=="no"){
	echo"
	<script>
	alert('Registro realizado exitosamente')
	</script>
	
	";
}
if($error=="si"){
	echo"
	<script>
	alert('Hubo un problema al realizar el registro')
	</script>
	
	";
}

?>

</body>
</html>