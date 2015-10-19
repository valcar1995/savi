<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];
function validarArea($nomAre,$idno){
	$centroEducativo=$_SESSION['CentroEducativo'];
	$existe="no";
	$datos=mysql_query("SELECT id FROM area WHERE nombre='$nomAre' AND centroEducativo='$centroEducativo' AND id!='$idno'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe el area
@$nomAre=$_POST['nomAre'];
if(isset($nomAre)){
	echo validarArea($nomAre,$_SESSION['areaEdit']);
}


// registrar area

@$nAre=$_POST['reAreNom'];
if(isset($nAre)){
	$existe=validarArea($nAre,0);
	
	
	if($existe=="si"){
		header("location:../../administrador/reArea.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reArea.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO area (nombre,centroEducativo) VALUES ('$nAre','$centroEducativo')",$con);
		header("location:../../administrador/reArea.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reArea.php?error=no";</script>'; 
	}
	
	
}


// buscar area
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Nombre</td>
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
	
	$datos=mysql_query("SELECT *  FROM area $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$nombre</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarArea($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM area WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $nombre=$reg['nombre'];
		 
		 $_SESSION['areaEdit']=$id;
		 
		 echo '
		 <div id="content" style="position:relative; bottom:10px;">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarArea.php" method="post">
                        
						<input type="hidden" name="acId" value="'.$id.'" readonly="readonly">
						
						<fieldset class="step">
                            <legend>ACTUALIZAR ÁREA</legend>
                            
                            <p>
                                <label for="username">Nombre(*)</label>
                                <input value="'.$nombre.'" name="acA" onkeyup="validarArea(this.value)" required />
                                <span class="error" id="existeAre"></span>
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
	$acA=$_POST['acA'];
	$existe=validarArea($acA,$_SESSION['areaEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buArea.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buArea.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE area SET nombre='$acA' WHERE id='$acId'",$con);
		$_SESSION['areaEdit']=0;
		header("location:../../administrador/buArea.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buArea.php?error=no";</script>'; 
	}
}

?>
