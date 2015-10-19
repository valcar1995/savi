<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Materia</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reMateria.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Materia";
$_SESSION['materiaEdit']=0;
$_SESSION['itemsel']="liMate";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarMateria.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVA MATERIA</legend>
                            
                            <p>
                                <label for="username">Nombre</label>
                                <input id="reMatNom" name="reMatNom" onkeyup="validarMateria(this.value)" required />
                                <span class="error" id="existeMat"></span>
                            </p>
                            
                            <p>
                                <label for="username">Porcentaje en el área</label>
                                <input type="number" max="100" min="1" id="reMatPor" name="reMatPor" required />
                            </p>
                            
                            
                            <p>
                                <label for="username">Área</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM area WHERE centroEducativo='$centro' ORDER BY nombre");
                                
								echo"<select name='reMatAre' required>";
								$hay=false;
								$areasOcultas=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   
									   $porcenArea=0;
									   $datos2=mysql_query("SELECT sum(porcentaje) AS totalP FROM materia WHERE area='$id'");
                                       while($reg2=mysql_fetch_array($datos2)){
										   $porcenArea=$reg2['totalP'];
									   }
									   
									   
									   if($porcenArea<100){
									   echo "<option value='$id'>$nombre</option>";
									   }
									   else{
										   $areasOcultas=true;
									   }
								   }
						        echo"</select>";
								
								if($areasOcultas==true){
								  echo"<br><br><span class='mensajesAviso'>Es posible que en la lista no aparezcan algunas áreas debido a que estas ya tienen todas sus materias asignadas.</span>";	
								}
								
								if($hay==false){
								  echo"<br><br><span class='error'>No hay áreas registradas en el actual centro educativo.</span>";	
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