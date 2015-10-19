<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Horario</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reHorario.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Horario";
$_SESSION['itemsel']="liHor";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarHorario.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO HORARIO</legend>
                            
                            <p>
                                <label for="username">Grupo</label>
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT g.id,g.nombre FROM grupo g INNER JOIN grado gr ON g.grado=gr.id WHERE g.centroEducativo='$centro' ORDER BY gr.numero");
                                
								echo"<select name='reHorGru' required>";
								$hay=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay grupos registrados en el actual centro educativo.</span>";	
								}
								?>
                               
                            </p>
                            
                            <p>
                            <label>Día</label>
                            <select name="reHorDia">
                             <option>Lunes</option>
                             <option>Martes</option>
                             <option>Miércoles</option>
                             <option>Jueves</option>
                             <option>Viernes</option>
                             <option>Sábado</option>
                             <option>Domingo</option>
                            </select>
                            </p>
                            
                            
                            <p>
                                <label for="username">Profesor</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT id,nombres,apellidos FROM profesor WHERE centroEducativo='$centro' ORDER BY nombres,apellidos");
                                
								echo"<select name='reHorPro' required>";
								$hay=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombres']." ".$reg['apellidos'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay profesores registrados en el actual centro educativo.</span>";	
								}
								?>
                               
                            </p>
                            
                            <p>
                                <label for="username">Materia</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT id,nombre FROM materia WHERE centroEducativo='$centro' ORDER BY nombre");
                                
								echo"<select name='reHorMat' required>";
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
                              <label for="username">Hora de inicio</label>
                              <input name="reHorH1" type="time" required="required" /> 
                            </p>
                            <p>
                              <label for="username">Hora de finalización</label>
                              <input name="reHorH2" type="time" required="required" /> 
                            </p>
                            
                            <?php
							$anioH=$_SESSION['anio'];
							$periodoH=$_SESSION['periodo'];
							echo'
							<p>
                              <label for="username">Año</label>
                              <input style="background-color:rgba(234,234,234,1)" value="'.$anioH.'" type="text" readonly="readonly"/> 
                            </p>
							';
							
							?>
                            
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