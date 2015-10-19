<?php
include("../conexion.php");
include("validarIngresoA.php");
// validar si existe un profesor con ese documento
function validarProfesor($nomPro,$idno){
	$existe="no";
	$datos=mysql_query("SELECT id FROM profesor WHERE docId='$nomPro' AND id!='$idno'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}


@$prore=$_POST['docPro'];
if(isset($prore)){
	 echo validarProfesor($prore,$_SESSION['profesorEdit']);
}



// registrar un nuevo acudiente

@$rp1=$_POST['rp1'];//docId
if(isset($rp1)){
	
	@$rp2=$_POST['rp2'];// nombres 
	@$rp3=$_POST['rp3'];// apellidos
	@$rp4=$_POST['rp4'];// telefono
	@$rp5=$_POST['rp5'];// direccion
	@$rp6=$_POST['rp6'];// e-mail
	@$rp7=$_POST['rp7'];// eps
	@$rp8=$_POST['rp8'];// especializacion
	@$rp9=$_POST['rp9'];// estado civil
	
	
	
	$centroEducativo=$_SESSION['CentroEducativo'];
	
	$existe=validarProfesor($rp1,0);
	
	if($existe=="si"){
		header("location:../../administrador/reProfesor.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reProfesor.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO profesor (docId,nombres,apellidos,telefono,direccion,email,eps,especializacion,estadocivil,centroEducativo) VALUES ('$rp1','$rp2','$rp3','$rp4','$rp5','$rp6','$rp7','$rp8','$rp9','$centroEducativo')",$con);
		header("location:../../administrador/reProfesor.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reProfesor.php?error=no";</script>'; 
	}
}


// buscar profesor
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
		 <td>Teléfono</td>
		 <td>Dirección</td>
		 <td>Email</td>
		 <td>Eps</td>
		 <td>Especialización</td>
		 <td>Estado Civil</td>
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
	
	$datos=mysql_query("SELECT *  FROM profesor $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$telefono=$reg['telefono'];
		$direccion=$reg['direccion'];
		$email=$reg['email'];
		$eps=$reg['eps'];
		$especializacion=$reg['especializacion'];
		$estadocivil=$reg['estadocivil'];
		
		
		
		$respuesta.="<tr class='$classTR'>
		 <td>$docId</td>
		 <td>$nombres</td>
		 <td>$apellidos</td>
		 <td>$telefono</td>
		 <td>$direccion</td>
		 <td>$email</td>
		 <td>$eps</td>
		 <td>$especializacion</td>
		 <td>$estadocivil</td>
		 
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarProfesor($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM profesor WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		$id=$reg['id'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$telefono=$reg['telefono'];
		$direccion=$reg['direccion'];
		$email=$reg['email'];
		$eps=$reg['eps'];
		$especializacion=$reg['especializacion'];
		$estadocivil=$reg['estadocivil'];
		 
		 $_SESSION['profesorEdit']=$id;
		 
		 echo '
		 <div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarProfesor.php" method="post">
                        <fieldset class="step">
						  <input type="hidden" name="acId" value="'.$id.'" readonly="readonly" />
                            <legend>ACTUALIZAR PROFESOR</legend>
                            
                            <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input value="'.$docId.'" onkeyup="validarProfesor(this.value)" name="ap1" required />
                                 <span class="error" id="existePro"></span>
                            </p>
                            <p>
                                <label for="username">Nombres(*)</label>
                                <input value="'.$nombres.'"  name="ap2" required />
                            </p>
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input value="'.$apellidos.'"  name="ap3" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                            <p>
                                <label for="password">Teléfono(*)</label>
                                <input value="'.$telefono.'"  name="ap4" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                             <p>
                                <label for="password">Dirección</label>
                                <input value="'.$direccion.'"  name="ap5" type="text" AUTOCOMPLETE=OFF />
                            </p>
                            <p>
                                <label for="password">E-mail(*)</label>
                                <input value="'.$email.'"  name="ap6"  required="required"type="email" placeholder="celia@sefu.net"AUTOCOMPLETE=OFF />
                            </p>
                              <p>
                                <label for="password">Eps</label>
                                <select name="ap7" >
								<option>'.$eps.'</option>
                                ';
                                 $datos=mysql_query("SELECT * FROM eps WHERE nombre!='$eps'");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $eps=$reg['nombre'];
									   echo "<option>$eps</option>";
								   }
						
								
                                echo '</select>
                            </p>
                            
                            <p>
                                <label for="password">Especialización</label>
                                <input name="ap8" value="'.$especializacion.'"  type="text" AUTOCOMPLETE=OFF />
                            </p>
                             <p>
                                <label for="password">Estado civil</label>
                                <select name="ap9" >
								<option>'.$estadocivil.'</option>
                               ';
                                 $datos=mysql_query("SELECT * FROM estadocivil WHERE nombre!='$estadocivil' ");
                                   while($reg=mysql_fetch_array($datos)){
									   $est=$reg['nombre'];
									   echo "<option>$est</option>";
								   }
								   
						
								echo'
                                </select>
                            </p>
                            <p class="submit">
                                <button id="registerButton" type="submit">Registrar</button>
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
	$ap1=$_POST['ap1'];//docId	
	$ap2=$_POST['ap2'];// nombres 
	$ap3=$_POST['ap3'];// apellidos
	$ap4=$_POST['ap4'];// telefono
	$ap5=$_POST['ap5'];// direccion
	$ap6=$_POST['ap6'];// e-mail
	$ap7=$_POST['ap7'];// eps
	$ap8=$_POST['ap8'];// especializacion
	$ap9=$_POST['ap9'];// estado civil
	
	$existe=validarProfesor($ap1,$_SESSION['profesorEdit']);
	
	
	if($existe=="si"){
		header("location:../../administrador/buProfesor.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buProfesor.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE profesor SET docId='$ap1', nombres='$ap2', apellidos='$ap3', telefono='$ap4', direccion='$ap5', email='$ap6', eps='$ap7', especializacion='$ap8', estadocivil='$ap9' WHERE id='$acId'",$con);
		$_SESSION['profesorEdit']=0;
		header("location:../../administrador/buProfesor.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buProfesor.php?error=no";</script>'; 
	}
}



?>