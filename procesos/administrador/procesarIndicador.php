<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];

function validarIndicador($nomInd,$idNo){
	$centroEducativo=$_SESSION['CentroEducativo'];
	$existe="no";
	$datos=mysql_query("SELECT id FROM indicadorLogro WHERE nombre='$nomInd' AND centroEducativo='$centroEducativo' AND id!='$idNo'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe centro educativo
@$nomInd=$_POST['nomInd'];
if(isset($nomInd)){
	echo validarIndicador($nomInd,$_SESSION['indicadorEdit']);
}


// registrar indicador

@$nInd=$_POST['reIndNom'];
if(isset($nInd)){
	$existe=validarIndicador($nInd,0);
	
	echo $existe;
	$graInd=$_POST['reIndGra'];
	$perInd=$_POST['reIndPer'];
	$matInd=$_POST['reIndMat'];
	
	
	if($existe=="si"){
		header("location:../../administrador/reIndicador.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reIndicador.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO indicadorlogro (nombre,grado,periodo,materia,centroEducativo,fechaModificacion) VALUES ('$nInd','$graInd','$perInd','$matInd','$centroEducativo',NOW())",$con);
		header("location:../../administrador/reIndicador.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reIndicador.php?error=no";</script>'; 
	}
	
	
}


// buscar indicador de logro
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Descripción</td>
		 <td>Grado</td>
		 <td>Materia</td>
		 <td>Periodo</td>
		 <td>Editar</td>
		 <td>Eliminar</td>
	 </tr>";
	
	if($centro=="no"){
		$textFiltro=" WHERE (id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') ORDER BY id LIMIT $cant";
	}
	else{
		$centroEducativo=$_SESSION['CentroEducativo'];
		$textFiltro=" WHERE(id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (centroEducativo='$centroEducativo') ORDER BY id LIMIT $cant";
	}
	
	
	$index=1;
	
	$datos=mysql_query("SELECT *  FROM indicadorlogro $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$grado=$reg['grado'];
		$periodo=$reg['periodo'];
		$materia=$reg['materia'];
		
		$nomGrado="";
		$nomMateria="";
		
		$datos2=mysql_query("SELECT nombre FROM grado WHERE id='$grado'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomGrado=$reg2['nombre'];
		}
		
		$datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomMateria=$reg2['nombre'];
		}
		
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$nombre</td>
		<td>$nomGrado</td>
		<td>$nomMateria</td>
		<td>$periodo</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarIndicador($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM indicadorlogro WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$grado=$reg['grado'];
		$periodo=$reg['periodo'];
		$materia=$reg['materia'];
		
		$nomGrado="";
		$nomMateria="";
		
		$hayg=false;
		$haym=false;
		
		$datos2=mysql_query("SELECT nombre FROM grado WHERE id='$grado'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomGrado=$reg2['nombre'];
			$hayg=true;
		}
		
		$datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomMateria=$reg2['nombre'];
			$haym=true;
		}
		 
		 
		 $_SESSION['indicadorEdit']=$id;
		 
		 echo '
		
		<div id="content">
				   
					<div id="wrapper">
						<div id="steps">
							<form id="formElem" name="formElem" action="../procesos/administrador/procesarIndicador.php" method="post">
								<fieldset class="step">
								<input type="hidden" name="acId" value="'.$id.'" readonly="readonly" />
									<legend>ACTUALIZAR INDICADOR DE LOGRO</legend>
									
									<p>
										<label for="username">Descripcion(*)</label>
										<textarea value="'.$nombre.'"  name="acIndNom" onkeyup="validarIndicador(this.value)" required>'.$nombre.'</textarea>
										<span class="error" id="existeInd"></span>
									</p>
									
									
									<p>
										<label for="username">Grado(*)</label>
										
									   
									   ';
										 $centro=$_SESSION['CentroEducativo'];
										 $datos=mysql_query("SELECT * FROM grado WHERE centroEducativo='$centro' AND id!='$grado' ORDER BY nombre");
										
										echo"<select name='acIndGra' required><option value='$grado'>$nomGrado</option>";
										
										   while($reg=mysql_fetch_array($datos)){
											   $hayg=true;
											   $id=$reg['id'];
											   $nombre=$reg['nombre'];
											   echo "<option value='$id'>$nombre</option>";
										   }
										echo"</select>";
										if($hayg==false){
										  echo"<br><br><span class='error'>No hay grados registrados en el actual centro educativo.</span>";	
										}
										echo'
									   
									</p>
									
									
									<p>
										<label for="username">Materia(*)</label>
										
									   
									   ';
										 $centro=$_SESSION['CentroEducativo'];
										 $datos=mysql_query("SELECT * FROM materia WHERE centroEducativo='$centro' AND id!='$materia' ORDER BY nombre");
										
										echo"<select name='acIndMat' required><option value='$materia'>$nomMateria</option>";
										
										   while($reg=mysql_fetch_array($datos)){
											   $haym=true;
											   $id=$reg['id'];
											   $nombre=$reg['nombre'];
											   echo "<option value='$id'>$nombre</option>";
										   }
										echo"</select>";
										if($haym==false){
										  echo"<br><br><span class='error'>No hay materias registradas en el actual centro educativo.</span>";	
										}
										echo'
									   
									</p>
									
									 <p>
									<label>Periodo(*)</label>
									<input name="acIndPer" value="'.$periodo.'" type="number" value="1" required="required" />
									</p>
									
									<p class="submit">
										<button id="registerButton" type="submit">Guardar</button>
									</p>
								</fieldset>
							   
							   
							   
								
							</form>
								
						</div>
					   
					</div>
				</div> 
        
		 
		 ';
		 
	 }
}


// actualizar información del indicador
@$acId=$_POST['acId'];

if(isset($acId)){
	
	$nInd=$_POST['acIndNom'];
	$graInd=$_POST['acIndGra'];
	$perInd=$_POST['acIndPer'];
	$matInd=$_POST['acIndMat'];
	
	$existe=validarIndicador($nInd,$_SESSION['indicadorEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buIndicador.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buIndicador.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE indicadorlogro SET nombre='$nInd', grado='$graInd', periodo='$perInd', materia='$matInd'  WHERE id='$acId'",$con);
		$_SESSION['indicadorEdit']=0;
		header("location:../../administrador/buIndicador.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buIndicador.php?error=no";</script>'; 
	}
}



?>
