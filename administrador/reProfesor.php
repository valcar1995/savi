
<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Profesor</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reProfesor.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Profesor";
$_SESSION['itemsel']="liPro";
$_SESSION['profesorEdit']=0;
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarProfesor.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO PROFESOR</legend>
                            
                            <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input id="rp1" onkeyup="validarProfesor(this.value)" name="rp1" required />
                                 <span class="error" id="existePro"></span>
                            </p>
                            <p>
                                <label for="username">Nombres(*)</label>
                                <input id="rp2" name="rp2" required />
                            </p>
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input id="rp3" name="rp3" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                            <p>
                                <label for="password">Teléfono(*)</label>
                                <input id="rp4" name="rp4" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                             <p>
                                <label for="password">Dirección</label>
                                <input id="rp5" name="rp5" type="text" AUTOCOMPLETE=OFF />
                            </p>
                            <p>
                                <label for="password">E-mail(*)</label>
                                <input id="rp6" name="rp6"  required="required"type="email" placeholder="celia@sefu.net"AUTOCOMPLETE=OFF />
                            </p>
                              <p>
                                <label for="password">Eps</label>
                                <select name="rp7" id="rp7">
                                <?php
                                 $datos=mysql_query("SELECT * FROM eps");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $eps=$reg['nombre'];
									   echo "<option>$eps</option>";
								   }
						
								?>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Especialización</label>
                                <input id="rp8" name="rp8"  type="text" AUTOCOMPLETE=OFF />
                            </p>
                             <p>
                                <label for="password">Estado civil</label>
                                <select name="rp9" id="rp9">
                                <?php
                                 $datos=mysql_query("SELECT * FROM estadocivil ");
                                   while($reg=mysql_fetch_array($datos)){
									   $est=$reg['nombre'];
									   echo "<option>$est</option>";
								   }
								   
						
								?>
                                </select>
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