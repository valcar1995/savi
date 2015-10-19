<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Indicador de logro</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reIndicador.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Indicador";
$_SESSION['indicadorEdit']=0;
$_SESSION['itemsel']="liInd";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarIndicador.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO INDICADOR DE LOGRO</legend>
                            
                            <p>
                                <label for="username">Descripcion(*)</label>
                                <textarea id="reIndNom" name="reIndNom" onkeyup="validarIndicador(this.value)" required></textarea>
                                <span class="error" id="existeInd"></span>
                            </p>
                            
                            
                            <p>
                                <label for="username">Grado(*)</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM grado WHERE centroEducativo='$centro' ORDER BY numero");
                                
								echo"<select name='reIndGra' required>";
								$hay=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay grados registrados en el actual centro educativo.</span>";	
								}
								?>
                               
                            </p>
                            
                            
                            <p>
                                <label for="username">Materia(*)</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM materia WHERE centroEducativo='$centro' ORDER BY nombre");
                                
								echo"<select name='reIndMat' required>";
								$hay=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay materias registradas en el actual centro educativo.</span>";	
								}
								?>
                               
                            </p>
                            
                             <p>
                            <label>Periodo(*)</label>
                            <input name="reIndPer" type="number" value="1" required="required" />
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