<?php
include("../conexion.php");
include("validarIngresoA.php");
// validar si existe un acudiente con ese documento
function validarAcudiente($nomAcu,$idNo){
	$existe="no";
	$datos=mysql_query("SELECT id FROM acudiente WHERE docId='$nomAcu' AND id!='$idNo'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}


@$acure=$_POST['docAcu'];
if(isset($acure)){
	 echo validarAcudiente($acure,$_SESSION['acudienteEdit']);
}



// registrar un nuevo acudiente

@$ra1=$_POST['ra1'];//docId
if(isset($ra1)){
	
	@$ra2=$_POST['ra2'];// nombres 
	@$ra3=$_POST['ra3'];// apellidos
	@$ra4=$_POST['ra4'];// direccion
	@$ra5=$_POST['ra5'];// telefono
	@$ra6=$_POST['ra6'];// ocupacion
	
	
	
	$centroEducativo=$_SESSION['CentroEducativo'];
	
	$existe=validarAcudiente($ra1,0);
	
	if($existe=="si"){
		header("location:../../administrador/reAcudiente.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reAcudiente.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO acudiente (docId,nombres,apellidos,direccion,telefono,ocupacion,centroEducativo) VALUES ('$ra1','$ra2','$ra3','$ra4','$ra5','$ra6','$centroEducativo')",$con);
		header("location:../../administrador/reAcudiente.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reAcudiente.php?error=no";</script>';
	}
}

// buscar acudiente
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>DocId</td>
		 <td>Nombres</td>
		 <td>Apellidos</td>
		 <td>Dirección</td>
		 <td>Teléfono</td>
		 <td>Ocupación</td>
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
	
	$datos=mysql_query("SELECT *  FROM acudiente $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$direccion=$reg['direccion'];
		$telefono=$reg['telefono'];
		$ocupacion=$reg['ocupacion'];
		
		
		
		$respuesta.="<tr class='$classTR'>
		 <td>$docId</td>
		 <td>$nombres</td>
		 <td>$apellidos</td>
		 <td>$direccion</td>
		 <td>$telefono</td>
		 <td>$ocupacion</td>
		 
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarAcudiente($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM acudiente WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		$id=$reg['id'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$direccion=$reg['direccion'];
		$telefono=$reg['telefono'];
		$ocupacion=$reg['ocupacion'];
		 
		 $_SESSION['acudienteEdit']=$id;
		 
		 echo '
		 <div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarAcudiente.php" method="post">
                        <fieldset class="step">
						<input type="hidden" value="'.$id.'" name="acId" readonly="readonly" />
                            <legend>ACTUALIZAR ACUDIENTE</legend>
                    
                           <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input value="'.$docId.'"  name="aa1" onkeyup="validarAcudiente(this.value)" required />
                                 <span class="error" id="existeAcu"></span>
                            </p>
                            <p>
                                <label for="email">Nombres(*)</label>
                                <input value="'.$nombres.'"  name="aa2" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input value="'.$apellidos.'"  name="aa3" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                             <p>
                                <label for="email">Dirección</label>
                                <input value="'.$direccion.'"  name="aa4"  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                             <p>
                                <label for="email">Teléfono(*)</label>
                                <input value="'.$telefono.'"  name="aa5" required  type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                            <p>
                                <label for="email">Ocupación</label>
                                <input value="'.$ocupacion.'"  name="aa6"   type="text" AUTOCOMPLETE=OFF />
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


// actualizar información de usuario
@$acId=$_POST['acId'];

if(isset($acId)){
	
	$aa1=$_POST['aa1'];//docId
	$aa2=$_POST['aa2'];// nombres 
	$aa3=$_POST['aa3'];// apellidos
	$aa4=$_POST['aa4'];// direccion
	$aa5=$_POST['aa5'];// telefono
	$aa6=$_POST['aa6'];// ocupacion
	
	$existe=validarAcudiente($aa1,$_SESSION['acudienteEdit']);
	
	
	if($existe=="si"){
		header("location:../../administrador/buAcudiente.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buAcudiente.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE acudiente SET docId='$aa1', nombres='$aa2', apellidos='$aa3', direccion='$aa4', telefono='$aa5', ocupacion='$aa6' WHERE id='$acId'",$con);
		$_SESSION['acudienteEdit']=0;
		header("location:../../administrador/buAcudiente.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buAcudiente.php?error=no";</script>'; 
	}
}


?>