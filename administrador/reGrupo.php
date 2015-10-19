<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Grupo</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reGrupo.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Grupo";
$_SESSION['grupoEdit']=0;
$_SESSION['itemsel']="liGru";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarGrupos.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO GRUPO</legend>
                            
                            <p>
                                <label for="username">Nombre</label>
                                <input id="reGruNom" name="reGruNom" onkeyup="validarGrupo(this.value)" required />
                                <span class="error" id="existeGru"></span>
                            </p>
                            
                            <p>
                                <label for="username">Grado</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM grado WHERE centroEducativo='$centro' ORDER BY numero");
                                
								echo"<select name='reGruGra' required>";
								$hay=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay grados registradas en el actual centro educativo.</span>";	
								}
								?>
                               
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