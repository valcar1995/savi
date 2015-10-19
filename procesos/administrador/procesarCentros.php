<?php
include("../conexion.php");
include("validarIngresoA.php");

function validarCentro($nomCen,$idNo){
	$existe="no";
	$datos=mysql_query("SELECT id FROM centroeducativo WHERE nombre='$nomCen' AND id!='$idNo'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe centro educativo
@$nomCen=$_POST['cenRe'];
if(isset($nomCen)){
	echo validarCentro($nomCen,$_SESSION['centroEdit']);
}


// registrar centro educativo

@$nCen=$_POST['reCenNom'];
if(isset($nCen)){
	$uCen=$_POST['reCenUb'];
	
	$existe=validarCentro($nCen,0);
	
	$anio=date("Y");
	$logo=$_POST['reCenLog'];
	$nit=$_POST['reCenNi'];
	$dane=$_POST['reCenDa'];
	$CD=$_POST['reCenCD'];
	$SE=$_POST['reCenSE'];
	
	if($existe=="si"){
		header("location:../../administrador/reCentro.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reCentro.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO centroeducativo (nombre,ubicacion,anio,periodo,logo,nit,dane,complementodane,sistemaevaluativo) VALUES ('$nCen','$uCen','$anio','1','$logo','$nit','$dane','$CD','$SE')",$con);
		
		
		// usuarios centro
		$centroId=0;
		$datos=mysql_query("SELECT id FROM centroeducativo ORDER BY id DESC LIMIT 1");
		 while($reg=mysql_fetch_array($datos)){
			$centroId=$reg['id']; 
		 }
		 
		 
		 $idUsu=$_SESSION['idUsuario'];
		 do{
			 mysql_query("INSERT INTO usuariocentros (usuario,centro) VALUES ('$idUsu','$centroId')",$con);
			 $datos=mysql_query("SELECT usuarioPadre FROM usuario WHERE id='$idUsu'");
			 while($reg=mysql_fetch_array($datos)){
				 $idUsu=$reg['usuarioPadre'];
			 }
		 }while($idUsu!=0);
		
		header("location:../../administrador/reCentro.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reCentro.php?error=no";</script>'; 
	}
	
	
}

// buscar centros educativo del usuario
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
	     <td>Logo</td>
		 <td>Nombre</td>
		 <td>Ubicación</td>
		 <td>NIT</td>
		 <td>DANE</td>
		 <td>Complemento DANE</td>
		 <td>Sistema Evaluativo</td>
		 <td>Editar</td>
		 <td>Eliminar</td>
	 </tr>";
	
	$tipoFiltroI="a.".$tipoFiltro;
	$idUsu=$_SESSION['idUsuario'];
	$textFiltro="a INNER JOIN usuariocentros b ON a.id=b.centro WHERE (a.id>'$idInicial') AND ($tipoFiltroI LIKE '%$filtro%' OR $tipoFiltroI LIKE '$filtro%' OR $tipoFiltroI LIKE '%$filtro') AND (b.usuario='$idUsu') ORDER BY a.id LIMIT $cant";
	
	
	$index=1;
	
	$datos=mysql_query("SELECT a.*  FROM centroeducativo $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$logo=$reg['logo'];
		$nit=$reg['nit'];
		$dane=$reg['dane'];
		$CD=$reg['complementodane'];
		$SE=$reg['sistemaevaluativo'];
		$ubicacion=$reg['ubicacion'];
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td><img src='../imagenes/centros/$logo' class='imgLogosCent' /></td>
		<td>$nombre</td>
		<td>$ubicacion</td>
		<td>$nit</td>
		<td>$dane</td>
		<td>$CD</td>
		<td>$SE</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarCentro($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM centroeducativo WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $nombre=$reg['nombre'];
		 $ubicacion=$reg['ubicacion'];
		 $nit=$reg['nit'];
		 $dane=$reg['dane'];
		 $CD=$reg['complementodane'];
		 $SE=$reg['sistemaevaluativo'];
		 $logo=$reg['logo'];
		 $_SESSION['centroEdit']=$id;
		 
		 echo '
		 <div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarCentros.php" method="post">
                        <fieldset class="step">
						<input type="hidden" value="'.$id.'" name="acId" readonly="readonly" />
                            <legend>ACTUALIZAR INFORMACIÓn</legend>
                            
                            <p>
                                <label for="username">Nombre(*)</label>
                                <input value="'.$nombre.'"  name="acCenNom" onkeyup="validarCentro(this.value)" required />
                                <span class="error" id="existeCen"></span>
                            </p>
                            
                            <p>
                                <label for="password">Ubicación(*)</label>
                                <input value="'.$ubicacion.'" name="acCenUb"  required="required" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
							
							<p>
                                <label for="password">NIT</label>
                                <input value="'.$nit.'" name="acCenNi" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            <p>
                                <label for="password">DANE(*)</label>
                                <input value="'.$dane.'" name="acCenDa"  required="required" type="text" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            
                            <p>
                                <label for="password">Complemento DANE</label>
                                <textarea style="width:380px" value="'.$CD.'" name="acCenCD" type="text" placeholder="Ej. Aprobado por Resol. N° 043024 21 de noviembre de 2011" AUTOCOMPLETE=OFF>'.$CD.'</textarea>
                        
                            </p>
                            
                            <p>
                                <label for="password">Sistema evaluativo</label>
                                <textarea style="width:380px" value="'.$SE.'" name="acCenSE"  type="text" placeholder="Ej. SISTEMA EVALUATIVO SEGÚN ACUERDO N° 007 DE ABRIL 29 DE 2013" AUTOCOMPLETE=OFF >'.$SE.'</textarea>
                                
                            </p>
                            
                            <p class="submit">
                                <button id="registerButton" type="submit">Guardar</button>
                            </p>
							
							 <legend>ACTUALIZAR LOGO</legend>
							
							 
							  <iframe name="frameLogoCentro" src="../procesos/subirImagenes.php?defEdit='.$logo.'" id="frameLogoCentro" class="framesimg" width="200" height="150"></iframe><br />
							  <label onclick="document.getElementById(\'reCenLogo\').click()" class="elecimgtext2" >Elegir imagen</label>
						   
							
                        </fieldset>
                       
                       
                       
						
                    </form>
					
					<form method="post" id="formLogo" enctype="multipart/form-data" action="../procesos/subirImagenes.php" target="frameLogoCentro">
					   <input type="hidden" value="'.$id.'" name="idLogoEdit" readonly="readonly" />
                       <input type="file" id="reCenLogo" accept="image/*" name="AcCenLogo" onChange="enviarImagen()" style="display:none" />
                       <input type="submit" id="enviarLogo"  form="formLogo" style="display:none" />
                    </form>
					
					</div>
               
            </div>
        </div>

		 ';
		 
	 }
}


// actualizar información del centro
@$acId=$_POST['acId'];
if(isset($acId)){
	
	$nCen=$_POST['acCenNom'];
	$uCen=$_POST['acCenUb'];
	$nit=$_POST['acCenNi'];
	$dane=$_POST['acCenDa'];
	$CD=$_POST['acCenCD'];
	$SE=$_POST['acCenSE'];
	
	$existe=validarCentro($nCen,$_SESSION['centroEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buCentro.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buCentro.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE centroeducativo SET nombre='$nCen', ubicacion='$uCen', nit='$nit', dane='$dane', complementodane='$CD', sistemaevaluativo='$SE' WHERE id='$acId'",$con);
		$_SESSION['centroEdit']=0;
		header("location:../../administrador/buCentro.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buCentro.php?error=no";</script>'; 
	}
}

?>
