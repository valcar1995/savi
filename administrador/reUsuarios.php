
<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar usuarios</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reUsuario.js"></script>

</head>

<body>

<?php
$_SESSION['urlActual']="Usuarios";
$_SESSION['itemsel']="liUsu";
$_SESSION['usuarioEdit']=0;
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarUsuarios.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO USUARIO</legend>
                            
                            <p>
                                <label for="password">Documento de identidad(*)</label>
                                <input id="reUsudoc" name="reUsudoc" onkeyup="ObtenerUsuario(this.value)"   required="required" type="text" AUTOCOMPLETE=OFF />
                                <br /><br /><span class="notificaciones" id="infoUsu"></span>
                            </p>
                            
                            <p>
                                <label for="username">Nombre(*)</label>
                                <input type="text" name="reUsunom"  required />
                            <p>
                                <label for="username">Nombre de usuario(*)</label>
                                <input value="" name="reUsulog" onkeyup="validarUsuario(this.value)" autocomplete="off" required />
                                <span class="error" id="existeUsu"></span>
                            </p>
                            
                            <p>
                                <label for="password">Contraseña(*)</label>
                                <input value="" name="reUsupw"  required="required" type="password" autocomplete="off" />
                                
                            </p>
                            
                            
                             <p>
                                <label for="password">PERFIL(*)</label>
                                 <select name="reUsuper" id="reUsuper" onchange="cambiarPerfil(this.value)">
                                    <option>Administrador</option>
                                     <option>Profesor</option>
                                      <option>Estudiante</option>
                                 </select><br /><br />
                            </p>
                            
                           
                               <div id="divPerisosAdmin" style="text-align:center; ">
                               <div class="divisoresForms">Seleccione los centros educativos a los cuales el usuario tendrá permisos</div>
                               
                               <div id="contenChecsAdmin">
                               <table border="0" width="100%">
                               
                               <?php
							     $idUsu=$_SESSION['idUsuario'];
							     $cantidadDatos=0;
								 $datos=mysql_query("SELECT count(*) as total FROM usuariocentros WHERE usuario='$idUsu' ");
								 while($reg=mysql_fetch_array($datos)){
									 $cantidadDatos=$reg['total'];
								 }
								 
							     echo "<input type='hidden' value='$cantidadDatos' readonly='readonly' name='cantidadCentrosPer' />";
							     
								 $index=1;
							     $datos=mysql_query("SELECT pc.centro, c.nombre FROM usuariocentros pc INNER JOIN centroeducativo c ON c.id=pc.centro WHERE pc.usuario='$idUsu' ");
								 while($reg=mysql_fetch_array($datos)){
									 
									 $centroId=$reg['centro'];
									 $centroNombre=$reg['nombre'];
									 $nomCam="c".$index;
									 
									 echo'
									   <tr>
									   <td class="tdsChecks"><input checked type="checkbox" name="'.$nomCam.'" value="'.$centroId.'" /></td>
									   <td class="tdsValoreschecks" width="90%"><label class="lblsValoresPermisos">'.$centroNombre.'</label></td>
									   </tr>
									 ';
									 $index++;
								 }
							   
							   ?>

     
                               </table>
                               </div>
                               </div>
                           
                            
                            
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