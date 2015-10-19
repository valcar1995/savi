

<?php
//include("../procesos/token.php");
?>

<script type="text/javascript" src="../js/profesor/menu.js"></script>
<link rel="stylesheet" type="text/css" href="../css/profesor/menu.css">
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>

<div id="fondoProfe" onclick="cerrarEditCuentaPro()"></div>
<div id="contenEditCuenta" style="position:absolute">
    <center>
    <div id="editCuenta">
    <button class="btnCerrar" onclick="cerrarEditCuentaPro()">X</button>
       
			
			<div id="content">
					   
						<div id="wrapper">
							<div id="steps">
								<form id="formElem" name="formElem" action="../procesos/profesor/cuentaP.php" method="post">
									
                                    <?php
									 @$error=$_GET['errorAct'];
										if(isset($error)){
											
											echo "<script>
											setTimeout(function(){verEditCuentaPro()},100)
											</script>";
											
											if($error=="no"){
												echo "<div class='NotificaGrandeBn'>Modificación de cuenta realizada exitosamente.</div>";
											}
											else{echo "<div class='NotificaGrandeError'>Hubo un error al realizar la modificación de la cuenta.</div><br>";}
										}
									?>
                                    
                                    <fieldset class="step">
										<legend>MODIFICAR CUENTA</legend>
										<?php
										
										$url=$_SERVER['REQUEST_URI'];
										echo '<input name="urlVolver" type="hidden" value="'.$url.'" />';
										?>
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

    
    
    </div>
    </center>

</div>

<header>
<div id="cabesa">
<img src="../imagenes/logo.jpg" id="logo" />

<div id="contenPeriodo">
<?php
$anio=$_SESSION['anio'];
$periodo=$_SESSION['periodo'];
echo "<span>$anio - $periodo</span>";
?>



</div>

<a href="../procesos/cerrar.php">
<img src="../imagenes/salir.png" class="iconosMenu" />
</a>
<a href="areas.php">
<div class="itemsProf" id="itemPro1">Más...</div>
</a>
<a href="grupos.php">
<div class="itemsProf" id="itemPro2">Planillas</div>
</a>
<a href="horario.php">
<div class="itemsProf" id="itemPro3">Horario</div>
</a>


</div>
<div id="contenPerfilUsu">
   <div id="perfilUsu" onclick="verEditCuentaPro()" title="Editar cuenta">
     <label id="nombrePerfil">
     <?php
	   echo $_SESSION['nombreUsurio'];
	 ?>
     </label><br />
     <img src="../imagenes/iconoProfesor.png" id="imagenPerfil" />
   </div>
</div>



</header>




