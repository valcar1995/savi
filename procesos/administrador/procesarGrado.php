<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];
function validarGrado($nomGra,$idNo){
	$centroEducativo=$_SESSION['CentroEducativo'];
	$existe="no";
	$datos=mysql_query("SELECT id FROM grado WHERE nombre='$nomGra' AND centroEducativo='$centroEducativo' AND id!='$idNo'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe grado
@$nomGra=$_POST['nomGra'];
if(isset($nomGra)){
	echo validarGrado($nomGra,$_SESSION['gradoEdit']);
}


// registrar grado

@$nGra=$_POST['reGraNom'];
if(isset($nGra)){
	$existe=validarGrado($nGra,0);
	$numero=$_POST['reGraNu'];
	
	if($existe=="si"){
		header("location:../../administrador/reGrado.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reGrado.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO grado (nombre,numero,centroEducativo) VALUES ('$nGra','$numero','$centroEducativo')",$con);
		header("location:../../administrador/reGrado.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reGrado.php?error=no";</script>'; 
	}
	
	
}

// buscar grado
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Nombre</td>
		 <td>Número</td>
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
	
	$datos=mysql_query("SELECT *  FROM grado $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$numero=$reg['numero'];
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$nombre</td>
		<td>$numero</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarGrado($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM grado WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $nombre=$reg['nombre'];
		 $numero=$reg['numero'];
		 $_SESSION['gradoEdit']=$id;
		 
		 echo '
		 <div id="content" style="position:relative; bottom:10px;">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarGrado.php" method="post">
                        
						<input type="hidden" name="acId" value="'.$id.'" readonly="readonly">
						
						<fieldset class="step">
                            <legend>ACTUALIZAR GRADO</legend>
                            
                            <p>
                                <label for="username">Nombre(*)</label>
                                <input value="'.$nombre.'" name="acG" onkeyup="validarGrado(this.value)" required />
                                <span class="error" id="existeGra"></span>
                            </p>
							
							 <p>
                                <label for="username">Número relacionado</label>
                                <input id="reGraNom" value="'.$numero.'" type="number" name="acGNu" required />
                                <span class="mensajesAviso">El número relacionado tiene que ver con el valor numérico del grado. Ej. 1 si es primero, 2 si es segundo, 6 si es sexto... Este valor permitirá visualizar la información de forma más organizada.</span>
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


// actualizar información del area
@$acId=$_POST['acId'];

if(isset($acId)){
	$acG=$_POST['acG'];
	$numero=$_POST['acGNu'];
	
	$existe=validarGrado($acG,$_SESSION['gradoEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buGrado.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buGrado.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE grado SET nombre='$acG', numero='$numero' WHERE id='$acId'",$con);
		$_SESSION['areaEdit']=0;
		header("location:../../administrador/buGrado.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buGrado.php?error=no";</script>'; 
	}
}


?>