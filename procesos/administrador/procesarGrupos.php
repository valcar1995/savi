<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];

function validarGrupo($nomGru,$idNo){
	$centroEducativo=$_SESSION['CentroEducativo'];
	$existe="no";
	$datos=mysql_query("SELECT id FROM grupo WHERE nombre='$nomGru' AND centroEducativo='$centroEducativo' AND id!='$idNo'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe centro educativo
@$nomGru=$_POST['nomGru'];
if(isset($nomGru)){
	echo validarGrupo($nomGru,$_SESSION['grupoEdit']);
}


// registrar grado

@$nGru=$_POST['reGruNom'];
if(isset($nGru)){
	$existe=validarGrupo($nGru,0);
	
	$gradoGru=$_POST['reGruGra'];
	if($existe=="si"){
		header("location:../../administrador/reGrupo.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reGrupo.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO grupo (nombre,grado,centroEducativo) VALUES ('$nGru','$gradoGru','$centroEducativo')",$con);
		header("location:../../administrador/reGrupo.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reGrupo.php?error=no";</script>'; 
	}
	
	
}

// buscar grupo
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Nombre</td>
		 <td>Grado</td>
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
	
	$datos=mysql_query("SELECT *  FROM grupo $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		
		$grado=$reg['grado'];
		$nomgrado="";
		
		$datos2=mysql_query("SELECT nombre FROM grado WHERE id='$grado'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomgrado=$reg2['nombre'];
		}
		
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$nombre</td>
		<td>$nomgrado</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarGrupo($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM grupo WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $nombre=$reg['nombre'];
		 $grado=$reg['grado'];
		 $nomGrado="";
		 $hay=false;
		 $datos2=mysql_query("SELECT nombre FROM grado WHERE id='$grado'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $nomGrado=$reg2['nombre'];
			 $hay=true;
		 }
		 
		 
		 $_SESSION['grupoEdit']=$id;
		 
		 echo '
		
		<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarGrupos.php" method="post">
                        <fieldset class="step">
						    <input type="hidden" value="'.$id.'" name="acId" readonly="readonly" />
                            <legend>ACTUALIZAR GRUPO </legend>
                            
                            <p>
                                <label for="username">Nombre</label>
                                <input value="'.$nombre.'"  name="acGruNom" onkeyup="validarGrupo(this.value)" required />
                                <span class="error" id="existeGru"></span>
                            </p>
                            
                            
                            <p>
                                <label for="username">Grado</label>
                                
                               
                               ';
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM grado WHERE centroEducativo='$centro' AND id!='$grado' ORDER BY nombre");
                                
								echo"<select name='acGruGra' required>
								<option value='$grado'>$nomGrado</option>";
								
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
								
                               
                          echo'  </p>
                            
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


// actualizar información del area
@$acId=$_POST['acId'];
if(isset($acId)){
	
	$nGru=$_POST['acGruNom'];	
	$gradoGru=$_POST['acGruGra'];
	
	$existe=validarGrupo($nGru,$_SESSION['grupoEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buGrupo.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buGrupo.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE grupo SET nombre='$nGru', grado='$gradoGru' WHERE id='$acId'",$con);
		$_SESSION['grupoEdit']=0;
		header("location:../../administrador/buGrupo.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buGrupo.php?error=no";</script>'; 
	}
}



?>
