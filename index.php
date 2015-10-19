<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Sefu</title>
<link rel="stylesheet" type="text/css" href="css/administrador/formularios.css"/>
<link rel="stylesheet" type="text/css" href="css/index.css"/>
<script type="text/javascript" src="js/index.js"></script>
<script type="text/javascript" src="js/jquery.min.js"></script>
</head>

<body id="myBody" onload="iniciar()">
<div id="fondo" onclick="cerrarFormRegistro()"></div>
<center>

<div style="height:0px; position:relative; z-index:6">

<div id="confirmRegistroUsu">
 
  <?php
    @$error2=$_GET['errorR'];
	if($error2=="no"){
		echo '
		 <img src="imagenes/ok.png" width="20" height="20" /><label class="mensajesAviso">
		  Registro realizado exitosamente!!.</label>
		';
	}
	else{
		echo "<span class='error'>Hubo un problema en el registro. Por favor realice de nuevo el procedimiento</span>";
	}
  ?>
  <br /><br /><button class="btns" onclick="cerrarFormRegistro()">Continuar</button></div>
</div>

</div>

<div style="height:0px; position:relative; z-index:6">
   
  
<div id="contenFormRegistro">

<table>
<tr>
<td>
<div id="contenFomu1">
<div id="content">
         <button class="btnCerrar" onclick="cerrarFormRegistro()">X</button>  
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem">
                        <fieldset class="step">
                            <legend>OBTENER CUENTA</legend>
                            
                            <div class="mensajesAviso">Este formulario solo esta habilitado para los estudiantes que ya se han matriculado en algun centro educativo y poseen su respectivo número de matrícula.</div>
                            <p>
                                <label for="password">Numero de matricula(*)</label>
                                <input id="reUsuMat" name="reUsudoc" type="text" AUTOCOMPLETE=OFF />
                               
                            </p>
                            <p>
                                <label for="password">Documento de identidad(*)</label>
                                <input id="reUsudoc" name="reUsudoc" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            <p class="submit">
                                <button id="registerButton" type="submit" onclick=" return obtenerRegistro()">Obtener formulario de registro</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
				</div>
               
            </div>
        </div>
</div>
</td>
<td style="padding-left:30px;">
<div id="recibeFormRegistro">
     
     
</div>
</td>
</tr>
</table>

</div>

</div>
</center><br />
		
			<div id="info">
				<h1>SEFU</h1>
				<h2>Sistema Evaluativo Fundacionista</h2>
				<p id="galeria">Aplicación web que permite un manejo eficiente y confiable de la información concerniente al proceso evaluativo; en el cual el docente puede realizar el registro y la revisión constante de los logros obtenidos por sus estudiantes de forma sistemática; y éstos accederán a una información oportuna de su seguimiento académico.</p>
			</div>
			<div id="acceso">
				<div id="conte">
                   <form action="procesos/login.php" method="post">
					<input id="usuario" name="log" type="text" placeholder="Usuario">
					<input id="password" name="pw" type="password" placeholder="Contrasena">
					<input id="entrar" class="btns" type="submit" value="Entrar">
                   </form>
                   <br /><br />
                    <div id="conteNopcionesLog">
                      <a href="#" onclick="mostrarRegistro()">Obtener Cuenta</a><br />
                      <a href="#" onclick="alert('Comunícate con las administrativas del centro educativo para recuperar tu cuenta.')">¿olvidaste tu cuenta?</a>
                    </div>
				</div>
				   
			</div>
		
		
	</body>
    <?php
	@$error=$_GET['error'];
	if(isset($error)){
	    echo"<script>alert('Nombre de usuario y/o contraseña incorrectos.')</script>";	
	}
	@$error2=$_GET['errorR'];
	if(isset($error2)){
		echo"<script>verErrorRegistro()</script>";
	}
	?>

</html>