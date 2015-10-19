<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Registrar Centro educativos</title>
<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<script type="text/javascript" src="../js/administrador/reCentro.js"></script>

</head>

<body>

<?php
$_SESSION['centroEdit']=0;
$_SESSION['urlActual']="Centro";
$_SESSION['itemsel']="liCen";
include("registros.php");
?>
<center>
<div style="padding-left:197px;">
<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarCentros.php" method="post">
                        <fieldset class="step">
                            <legend>NUEVO CENTRO EDUCATIVO</legend>
                            
                            <p>
                                <label for="username">Nombre(*)</label>
                                <input id="reCenNom" name="reCenNom" onkeyup="validarCentro(this.value)" required />
                                <span class="error" id="existeCen"></span>
                            </p>
                            
                            <p>
                                <label for="password">Ubicación(*)</label>
                                <input id="reCenUb" name="reCenUb"  required="required" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            <p>
                                <label for="password">NIT</label>
                                <input id="reCenNi" name="reCenNi" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            <p>
                                <label for="password">DANE(*)</label>
                                <input id="reCenDa" name="reCenDa"  required="required" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            
                            <p>
                                <label for="password">Complemento DANE</label>
                                <textarea style="width:380px" id="reCenCD" name="reCenCD" type="text" placeholder="Ej. Aprobado por Resol. N° 043024 21 de noviembre de 2011" AUTOCOMPLETE=OFF></textarea>
                        
                            </p>
                            
                            <p>
                                <label for="password">Sistema evaluativo</label>
                                <textarea style="width:380px" id="reCenSE" name="reCenSE"  type="text" placeholder="Ej. SISTEMA EVALUATIVO SEGÚN ACUERDO N° 007 DE ABRIL 29 DE 2013" AUTOCOMPLETE=OFF ></textarea>
                                
                            </p>
                            
                            <p >
                            <input type="hidden" name="reCenLog" id="reCenLog" />
                            <label>Logo(*)</label>
                             <!--subir la imagen del centro-->
                              <iframe name="frameLogoCentro" src="../procesos/subirImagenes.php?def=si" id="frameLogoCentro" class="framesimg" width="200" height="150" onload="obtenerDirLogoCentro()" ></iframe><br />
                            <label onclick="document.getElementById('reCenLogo').click()" class="elecimgtext" >Elegir imagen</label>
                           
                            </p>
                            
                            
                            
                            <p class="submit">
                                <button style="display:none" id="envioRegistroCentro" type="submit" form="formElem">Registrar</button>
                            </p>
                        </fieldset>
                    </form>
                    
                     <form method="post" id="formLogo" enctype="multipart/form-data" action="../procesos/subirImagenes.php" target="frameLogoCentro">
                                <input type="hidden" name="subirLogoCentro" />
                                <input type="file" id="reCenLogo" name="reCenLogo" accept="image/*" onChange="enviarImagen()" style="display:none" />
                            
                            <input type="submit" id="enviarLogo"  form="formLogo" style="display:none" />
                            </form>
                            
                            
                            
                            <button class="envioForms" onclick="enviarFormulario()">Registrar</button>
						
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