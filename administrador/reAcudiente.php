
<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Acudiente</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reAcudiente.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Acudiente";
$_SESSION['acudienteEdit']=0;
$_SESSION['itemsel']="liAcu";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarAcudiente.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO ACUDIENTE</legend>
                    
                           <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input id="ra1" name="ra1" onkeyup="validarAcudiente(this.value)" required />
                                 <span class="error" id="existeAcu"></span>
                            </p>
                            <p>
                                <label for="email">Nombres(*)</label>
                                <input id="ra2" name="ra2" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input id="ra3" name="ra3" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                             <p>
                                <label for="email">Dirección</label>
                                <input id="ra4" name="ra4"  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                             <p>
                                <label for="email">Teléfono(*)</label>
                                <input id="ra5" name="ra5" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                            <p>
                                <label for="email">Ocupación</label>
                                <input id="ra6" name="ra6"   type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                            <p class="submit">
                                <button id="registerButton" type="submit">Registrar</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
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