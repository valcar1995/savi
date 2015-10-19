<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];
function validarMateria($nomMat,$idno){
	$centroEducativo=$_SESSION['CentroEducativo'];
	$existe="no";
	$datos=mysql_query("SELECT id FROM materia WHERE nombre='$nomMat' AND centroEducativo='$centroEducativo' AND id!='$idno'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



// validar si existe materia
@$nomMat=$_POST['nomMat'];
if(isset($nomMat)){
	echo validarMateria($nomMat,$_SESSION['materiaEdit']);
}


// registrar materia

@$nMat=$_POST['reMatNom'];
if(isset($nMat)){
	
	$nAre=$_POST['reMatAre'];
	$porcen=$_POST['reMatPor'];
	$existe=validarMateria($nMat,0);
	
	
	if($existe=="si"){
		header("location:../../administrador/reMateria.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reMateria.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO materia (nombre,porcentaje,area,centroEducativo) VALUES ('$nMat','$porcen','$nAre','$centroEducativo')",$con);
		header("location:../../administrador/reMateria.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reMateria.php?error=no";</script>'; 
	}
	
	
}

// buscar materia
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Nombre</td>
		 <td>Porcentaje</td>
		 <td>Área</td>
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
	
	$datos=mysql_query("SELECT *  FROM materia $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$nombre=$reg['nombre'];
		$porcent=$reg['porcentaje'];
		
		$area=$reg['area'];
		$nomarea="";
		
		$datos2=mysql_query("SELECT nombre FROM area WHERE id='$area'");
		while($reg2=mysql_fetch_array($datos2)){
			$nomarea=$reg2['nombre'];
		}
		
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$nombre</td>
		<td>$porcent</td>
		<td>$nomarea</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarMateria($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM materia WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $nombre=$reg['nombre'];
		 $porcent=$reg['porcentaje'];
		 $area=$reg['area'];
		 $nomArea="";
		 $hay=false;
		 $datos2=mysql_query("SELECT nombre FROM area WHERE id='$area'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $nomArea=$reg2['nombre'];
			 $hay=true;
		 }
		 
		 
		 $_SESSION['materiaEdit']=$id;
		 
		 echo '
		
		<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarMateria.php" method="post">
                        <fieldset class="step">
						    <input type="hidden" value="'.$id.'" name="acId" readonly="readonly" />
                            <legend>ACTUALIZAR MATERIA </legend>
                            
                            <p>
                                <label for="username">Nombre</label>
                                <input value="'.$nombre.'"  name="acMatNom" onkeyup="validarMateria(this.value)" required />
                                <span class="error" id="existeMat"></span>
                            </p>
                            
							<p>
                                <label for="username">Porcentaje en el área</label>
                                <input type="number" value="'.$porcent.'" max="100" min="1" id="acMatPor" name="acMatPor" required />
                            </p>
                            
                            <p>
                                <label for="username">Área</label>
                                
                               
                               ';
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM area WHERE centroEducativo='$centro' AND id!='$area' ORDER BY nombre");
                                
								echo"<select name='acMatAre' required>
								<option value='$area'>$nomArea</option>";
								
								   $areasOcultas=false;
                                   while($reg=mysql_fetch_array($datos)){
									   $hay=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   
									   $porcenArea=0;
									   $datos2=mysql_query("SELECT sum(porcentaje) AS totalP FROM materia WHERE area='$id'");
                                       while($reg2=mysql_fetch_array($datos2)){
										   $porcenArea=$reg2['totalP'];
									   }
									   
									   if($porcenArea<100){
									   echo "<option value='$id'>$nombre</option>";
									   }
									   else{
										   $areasOcultas=true;
									   }
								   }
						        echo"</select>";
								
								if($areasOcultas==true){
								  echo"<br><br><span class='mensajesAviso'>Es posible que en la lista no aparezcan algunas áreas debido a que estas ya tienen todas sus materias asignadas.</span>";	
								}
								
								if($hay==false){
								  echo"<br><br><span class='error'>No hay áreas registradas en el actual centro educativo.</span>";	
								}
								
                               
                          echo'  </p>
                            
                            <p class="submit">
                                <button id="registerButton" type="submit">Actualizar</button>
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
	
	$nMat=$_POST['acMatNom'];	
	$nAre=$_POST['acMatAre'];
	$npor=$_POST['acMatPor'];
	
	$existe=validarMateria($nMat,$_SESSION['materiaEdit']);
	
	if($existe=="si"){
		header("location:../../administrador/buMateria.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buMateria.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE materia SET nombre='$nMat', porcentaje='$npor', area='$nAre' WHERE id='$acId'",$con);
		$_SESSION['areaEdit']=0;
		header("location:../../administrador/buMateria.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buMateria.php?error=no";</script>'; 
	}
}



?>