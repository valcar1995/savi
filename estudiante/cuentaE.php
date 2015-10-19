
<?php
include("../procesos/token3.php");
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Mi cuenta</title>
<script type="text/javascript" src="../js/jquery.min.js"></script>
<script type="text/javascript" src="../js/estudiante/cuentaE.js"></script>
<link rel="stylesheet" type="text/css" href="../css/estudiante/cuentaE.css"/>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>

</head>

<body style="min-width:1200px; overflow:auto" onload="iniciar()">

<?php
include("menu.php");
?>
<br />
<center>

<?php
@$error=$_GET['errorAct'];
if(isset($error)){
	if($error=="no"){
		echo "<div class='NotificaGrandeBn'>Modificación de cuenta realizada exitosamente.</div>";
	}
	else{echo "<div class='NotificaGrandeError'>Hubo un error al realizar la modificación de la cuenta.</div><br>";}
}

?>

<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/estudiante/cuentaEP.php" method="post">
                        <fieldset class="step">
                            <legend>MODIFICAR CUENTA</legend>
                            
                            <div class="divisoresForms">Información actual</div>
                            <p>
                                <label for="username">Nombre de usuario(*)</label>
                                <input value="" name="logAnt" id="logAnt" onkeyup="validarUsuario()" autocomplete="off" required />
                                <span class="error" id="existeUsu"></span>
                            </p>
                            
                            <p>
                                <label for="password">Contraseña(*)</label>
                                <input value="" name="pwAnt" id="pwAnt"  required="required" onkeyup="validarUsuario()" type="password" autocomplete="off" />
                                
                            </p>
                            <div id="recibevalidarUsu"></div>
                            
                            
                            <div class="divisoresForms">Información a modificar</div>
                            
                            <?php
							$nomUsu="";
							$usuActual=$_SESSION['idUsuario'];
							  $datos=mysql_query("SELECT nombre FROM usuario WHERE id='$usuActual'");
								while($reg=mysql_fetch_array($datos)){
									$nomUsu=$reg['nombre'];
								}
								
								echo '
								<p>
                                <label for="username">Nombre(*)</label>
                                <input type="text" value="'.$nomUsu.'" name="nomNuevo"  required />
                                
                            </p>
								
								';
							
							?>
                            
                            
                            <p>
                                <label for="username">Nombre de usuario(*)</label>
                                <input value="" name="nomUsuNuevo" onkeyup="validarUsuarioNuevo(this.value)" autocomplete="off" required />
                                <span class="error" id="existeUsuNuevo"></span>
                            </p>
                            
                            <p>
                                <label for="password">Contraseña(*)</label>
                                <input value="" name="pwNuevo" id="pwNuevo"  required="required" type="password" autocomplete="off" />
                                
                            </p>
                            
                            <p>
                                <label for="password">Confirmar contraseña(*)</label>
                                <input value="" name="pwNuevo2" id="pwNuevo2"  required="required" type="password" autocomplete="off" />
                                
                            </p>
                            
                            
                            
                            
                            <p class="submit">
                                <button id="registerButton" type="submit" onclick="return validarTodo()">Actualizar</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
				</div>
               
            </div>
        </div>

</center>

</body>
</html>