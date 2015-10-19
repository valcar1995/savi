<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Matricula</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<link rel="stylesheet" type="text/css" href="../js/jquery/jquery-ui.css"/>
<script type="text/javascript" src="../js/jquery/jquery.js"></script>
<script type="text/javascript" src="../js/jquery/jquery-ui.js"></script>
<script type="text/javascript" src="../js/administrador/reMatricula.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Matricula";
$_SESSION['matriculaEdit']=0;
$_SESSION['itemsel']="liMat";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarMatricula.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVA MATRICULA</legend>
                            <div class="divisoresForms">Información requerida</div>
                            <p>
                                <label for="username">Numero(*)</label>
                                <input id="m1" onkeyup="validarNumeroMatricula(this.value)" name="m1" required />
                                 <span class="error" id="existeNumMat"></span>
                            </p>
                            <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input id="m2" name="m2" onkeyup="validarDocIdMatricula(this.value)" required />
                                <span class="error" id="existeDocMat"></span>
                            </p>
                            <p>
                                <label for="username">Nombres(*)</label>
                                <input id="m3" name="m3" required />
                            </p>
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input id="m4" name="m4" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                           <p>
                                <label for="username">Grupo(*)</label>
                                
                               
                               <?php
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT g.id,g.nombre FROM grupo g INNER JOIN grado gr ON g.grado=gr.id WHERE g.centroEducativo='$centro' ORDER BY gr.numero");
                                
								echo"<select name='m5' required>";
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
                            
                            <div class="divisoresForms">Información adicional</div>
                            
                            <p>
                                <label for="password">Representante de grupo:</label>
                                <input type="checkbox" style="float:left; margin-top:20px" name="m20" />
                            </p>
                            
                             <p>
                                <label for="password">Acudiente</label>
                                <?php
								 echo"<select name='m6' id='m6'><option></option>";
							     $centro=$_SESSION['CentroEducativo'];
								 $hay=false;
                                 $datos=mysql_query("SELECT id,docId,nombres,apellidos FROM acudiente WHERE centroEducativo='$centro' ORDER BY nombres, apellidos");
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombres']." ".$reg['apellidos']."(".$reg['docId'].")";
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay Acudientes registrados en el actual centro educativo.</span>";	
								}
								?>
                            </p>
                            
                            <p>
                                <label for="password">Parentesco</label>
                                <select name="m7" id="m7">
                                <option></option>
                                <?php
                                 $datos=mysql_query("SELECT * FROM parentesco");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $pr=$reg['nombre'];
									   echo "<option>$pr</option>";
								   }
						
								?>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Ciudad documento</label>
                                <input type="text" name="m8" />
                            </p>
                            
                            <p>
                                <label for="password">Teléfono</label>
                                <input type="text" name="m9" />
                            </p>
                            
                            <p>
                                <label for="password">Dirección</label>
                                <input type="text" name="m10" />
                            </p>
                            <p>
                                <label for="password">Fecha de nacimiento</label>
                                <input type="text" placeholder="dd-mm-yy" name="m11" id="m11" />
                            </p>
                            <p>
                                <label for="password">Email</label>
                                <input type="email" name="m12" />
                            </p>
                            
                            <p>
                                <label for="password">EPS</label>
                                <select name="m13" id="m13">
                                <option></option>
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
                                <label for="password">Desplazado</label>
                                <select name="m14">
                                <option></option>
                                <option>No</option>
                                <option>Si</option>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Familias en acción</label>
                                <select name="m15">
                                <option></option>
                                <option>No</option>
                                <option>Si</option>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Religión</label>
                                <select name="m16" id="m16">
                                <option></option>
                                <?php
                                 $datos=mysql_query("SELECT * FROM religion");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $rel=$reg['nombre'];
									   echo "<option>$rel</option>";
								   }
						
								?>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Estrato</label>
                                <input type="number" name="m17" />
                            </p>
                            
                            <p>
                                <label for="password">Rh</label>
                                <select name="m18" id="m18">
                                <option></option>
                                <?php
                                 $datos=mysql_query("SELECT * FROM rh");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $rh=$reg['nombre'];
									   echo "<option>$rh</option>";
								   }
						
								?>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Nivel del sisben</label>
                                <input type="number" name="m19" />
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