<?php
include("../conexion.php");
include("validarIngresoA.php");
$centroEducativo=$_SESSION['CentroEducativo'];

// registrar horario
@$horGru=$_POST['reHorGru'];
if(isset($horGru)){
	
	$horDia=$_POST['reHorDia'];
	$horPro=$_POST['reHorPro'];
	$horMat=$_POST['reHorMat'];
	$horH1=$_POST['reHorH1'];
	$horH2=$_POST['reHorH2'];
	
	$anio=date("Y");
	$periodo=1;
	
	$datos=mysql_query("SELECT anio FROM centroeducativo WHERE id='$centroEducativo'");
	 while($reg=mysql_fetch_array($datos)){
		 $anio=$reg['anio'];
	 }
	
	
   mysql_query("INSERT INTO horario (grupo,dia,profesor,materia,horaInicio,horaFin,anio,centroEducativo) VALUES   ('$horGru','$horDia','$horPro','$horMat','$horH1','$horH2','$anio','$centroEducativo')",$con);
   header("location:../../administrador/reHorario.php?error=no");
   echo '<script type="text/javascript">window.location.href="../../administrador/reHorario.php?error=no";</script>'; 

	
}

// buscar Horario
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Grupo</td>
		 <td>Dia</td>
		 <td>Profesor</td>
		 <td>Materia</td>
		 <td>Hora inicio</td>
		 <td>Hora fin</td>
		 <td>Año</td>
		 <td>Editar</td>
		 <td>Eliminar</td>
	 </tr>";
	 
	 $anio=$_SESSION['anio'];
	
	if($centro=="no"){
		$textFiltro=" WHERE (id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (anio='$anio') ORDER BY id LIMIT $cant";
	}
	else{
		$centroEducativo=$_SESSION['CentroEducativo'];
		$textFiltro=" WHERE(id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (centroEducativo='$centroEducativo') AND (anio='$anio') ORDER BY id LIMIT $cant";
	}
	
	
	$index=1;
	
	$datos=mysql_query("SELECT *  FROM horario $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$grupo=$reg['grupo'];
		$dia=$reg['dia'];
		$profesor=$reg['profesor'];
		$materia=$reg['materia'];
		$horaInicio=$reg['horaInicio'];
		$horafin=$reg['horaFin'];
		$anioH=$reg['anio'];
		
		$datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
		while($reg2=mysql_fetch_array($datos2)){
			$grupo=$reg2['nombre'];
		}
		
		$datos2=mysql_query("SELECT nombres, apellidos FROM profesor WHERE id='$profesor'");
		while($reg2=mysql_fetch_array($datos2)){
			$profesor=$reg2['nombres']." ".$reg2['apellidos'];
		}
		
		$datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
		while($reg2=mysql_fetch_array($datos2)){
			$materia=$reg2['nombre'];
		}
		
		
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$grupo</td>
		<td>$dia</td>
		<td>$profesor</td>
		<td>$materia</td>
		<td>$horaInicio</td>
		<td>$horafin</td>
		<td>$anioH</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarHorario($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM horario WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		$id=$reg['id'];
		$grupo=$reg['grupo'];
		$dia=$reg['dia'];
		$profesor=$reg['profesor'];
		$materia=$reg['materia'];
		$horaInicio=$reg['horaInicio'];
		$horafin=$reg['horaFin'];
		$anioH=$reg['anio'];
		
		$nomGrupo="";
		$nomProfesor="";
		$nomMateria="";
		
		$hayg=false;
		$hayp=false;
		$haym=false;
		 
		 
		 
		 $datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $nomGrupo=$reg2['nombre'];
			 $hayg=true;
		 }
		 
		 $datos2=mysql_query("SELECT nombres,apellidos FROM profesor WHERE id='$profesor'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $nomProfesor=$reg2['nombres']." ".$reg2['apellidos'];
			 $hayp=true;
		 }
		 
		 $datos2=mysql_query("SELECT nombre FROM materia WHERE id='$materia'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $nomMateria=$reg2['nombre'];
			 $haym=true;
		 }
		 
		 
		 echo '
		<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarHorario.php" method="post">
                        <fieldset class="step">
						<input type="hidden" value="'.$id.'" name="acId" readonly="readonly" />
                            <legend>ACTUALIZAR HORARIO</legend>
                            
                            <p>
                                <label for="username">Grupo</label>
                               ';
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT * FROM grupo WHERE centroEducativo='$centro' AND id!='$grupo' ORDER BY nombre");
                                
								echo"<select name='acHorGru' required><option value='$grupo'>$nomGrupo</option>";
								
                                   while($reg=mysql_fetch_array($datos)){
									   $hayg=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hayg==false){
								  echo"<br><br><span class='error'>No hay grupos registrados en el actual centro educativo.</span>";	
								}
								echo '
                               
                            </p>
                            
                            <p>
                            <label>Día</label>
                            <select name="acHorDia">
							<option>'.$dia.'</option>
                             <option>Lunes</option>
                             <option>Martes</option>
                             <option>Miércoles</option>
                             <option>Jueves</option>
                             <option>Viernes</option>
                             <option>Sábado</option>
                             <option>Domingo</option>
                            </select>
                            </p>
                            
                            
                            <p>
                                <label for="username">Profesor</label>
                                
                               
                               ';
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT id,nombres,apellidos FROM profesor WHERE centroEducativo='$centro' AND id!='$profesor' ORDER BY nombres,apellidos");
                                
								echo"<select name='acHorPro' required><option value='$profesor'>$nomProfesor</option>";
								
                                   while($reg=mysql_fetch_array($datos)){
									   $hayp=true;
									   $id=$reg['id'];
									   $nombre=$reg['nombres']." ".$reg['apellidos'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hayp==false){
								  echo"<br><br><span class='error'>No hay profesores registrados en el actual centro educativo.</span>";	
								}
								echo'
                               
                            </p>
                            
                            <p>
                                <label for="username">Materia</label>
                                
                               
                              ';
							     $centro=$_SESSION['CentroEducativo'];
                                 $datos=mysql_query("SELECT id,nombre FROM materia WHERE centroEducativo='$centro' AND id!='$materia' ORDER BY nombre");
                                
								echo"<select name='acHorMat' required><option value='$materia'>$nomMateria</option>";
								
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
                              <label for="username">Hora de inicio</label>
                              <input value="'.$horaInicio.'" name="acHorH1" type="time" required="required" /> 
                            </p>
                            <p>
                              <label for="username">Hora de finalización</label>
                              <input value="'.$horafin.'" name="acHorH2" type="time" required="required" /> 
                            </p>
							<p>
                              <label for="username">Año</label>
                              <input style="background-color:rgba(234,234,234,1)" value="'.$anioH.'" type="text" readonly="readonly"/> 
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
	
	$horGru=$_POST['acHorGru'];	
	$horDia=$_POST['acHorDia'];
	$horPro=$_POST['acHorPro'];
	$horMat=$_POST['acHorMat'];
	$horH1=$_POST['acHorH1'];
	$horH2=$_POST['acHorH2'];
	
	
		
		mysql_query("UPDATE horario SET grupo='$horGru', dia='$horDia', profesor='$horPro', materia='$horMat', horaInicio='$horH1', horaFin='$horH2' WHERE id='$acId'",$con);
		$_SESSION['areaEdit']=0;
		header("location:../../administrador/buHorario.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buHorario.php?error=no";</script>'; 
	
}


?>