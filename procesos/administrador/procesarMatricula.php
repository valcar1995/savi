<?php
include("../conexion.php");
include("validarIngresoA.php");
// validar si existe una matricula con sierto numero
function validarNumMatricula($numMat,$idno){
	$existe="no";
	$centroEducativo=$_SESSION['CentroEducativo'];
	$datos=mysql_query("SELECT id FROM matricula WHERE numero='$numMat' AND centroEducativo='$centroEducativo' AND id!='$idno'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}

// validar si existe un documento de identidad de cierta matricula
function validarDocMatricula($docMat,$idno){
	$existe="no";
	$datos=mysql_query("SELECT id FROM matricula WHERE docId='$docMat' AND id!='$idno'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}


@$numMat=$_POST['numMat'];
if(isset($numMat)){
	 echo validarNumMatricula($numMat,$_SESSION['matriculaEdit']);
}

@$docMat=$_POST['docMat'];
if(isset($docMat)){
	 echo validarDocMatricula($docMat,$_SESSION['matriculaEdit']);
}



// registrar un nuevo acudiente

@$m1=$_POST['m1'];//numero
if(isset($m1)){
	
	$m2=$_POST['m2'];//docid
	$m3=$_POST['m3'];//nombres
	$m4=$_POST['m4'];//apellidos
	$m5=$_POST['m5'];//grupo
	$m6=$_POST['m6'];//acudiante
	$m7=$_POST['m7'];//parentesco
	$m8=$_POST['m8'];//ciudad docId
	$m9=$_POST['m9'];//telefono
	$m10=$_POST['m10'];//direccion
	$m11=$_POST['m11'];//fechaNac
	$m12=$_POST['m12'];//e-mail
	$m13=$_POST['m13'];//eps
	$m14=$_POST['m14'];//desplazado
	$m15=$_POST['m15'];//familias en acción
	$m16=$_POST['m16'];//religion
	$m17=$_POST['m17'];//estrato
	$m18=$_POST['m18'];//rh
	$m19=$_POST['m19'];//nivel sisven
	
	$m20="no";
	if(isset($_POST['m20'])){
		$m20="si";
	}
	
	echo " $m1/$m2/$m3/$m4/$m5/$m6/$m7/$m8/$m9/$m10/$m11/$m12/$m13/$m14/$m15/$m16/$m17/$m18/$m19";
	
	
	$centroEducativo=$_SESSION['CentroEducativo'];
	
	$existe1=validarNumMatricula($m1,0);
	$existe2=validarDocMatricula($m2,0);
	
	if($existe1=="si" || $existe2=="si"){
		header("location:../../administrador/reMatricula.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reMatricula.php?error=si";</script>'; 
	}
	else{
		mysql_query("INSERT INTO matricula (numero, docId, nombres, apellidos, grupo, acudiente, parentesco, ciudadDoc, telefono, direccion, fechaNac, email, eps, desplazado, familiasEnAccion, religion, estrato, rh, nivelSisben, centroEducativo, representantegrupo) VALUES ('$m1', '$m2', '$m3', '$m4', '$m5', '$m6', '$m7', '$m8','$m9', '$m10', '$m11', '$m12', '$m13', '$m14', '$m15', '$m16', '$m17', '$m18', '$m19', '$centroEducativo','$m20')",$con);
		
		
		$idM=0;
		$datos=mysql_query("SELECT id FROM matricula ORDER BY id DESC limit 1");
		 while($reg=mysql_fetch_array($datos)){
			 $idM=$reg['id'];
		 }
		 
		 
		
		// insertar notas si existen
		 $anio=$_SESSION['anio'];
		 $periodo=$_SESSION['periodo'];
		 
		 $datos=mysql_query("SELECT id FROM conceptoevaluacion WHERE grupo='$m5' AND anio='$anio' AND periodo='$periodo'");
		 while($reg=mysql_fetch_array($datos)){
			 $idC=$reg['id'];
			  mysql_query("INSERT INTO planilla (matricula,concepto,nota) VALUES ('$idM','$idC','0')",$con);
		 }
		
		header("location:../../administrador/reMatricula.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reMatricula.php?error=no";</script>'; 
	}
}



// buscar matricula
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	
	 
	 
	
	if($centro=="no"){
		$textFiltro=" WHERE (id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') ORDER BY id LIMIT $cant";
	}
	else{
		$centroEducativo=$_SESSION['CentroEducativo'];
		$textFiltro=" WHERE(id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (centroEducativo='$centroEducativo') ORDER BY id LIMIT $cant";
	}
	
	
	$respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Número</td>
		 <td>DocId</td>
		 <td>Nombres</td>
		 <td>Apellidos</td>
		 <td>Grupo</td>
		 <td>Acudiente</td>
		 <td>Parentesco</td>
		 <td>CiudadDoc</td>
		 <td>Teléfono</td>
		 <td>Dirección</td>
		 <td>Fecha Nac.</td>
		 <td>Email</td>
		 <td>Eps</td>
		 <td>Desplazado</td>
		 <td>Familias en ac.</td>
		 <td>Religión</td>
		 <td>Estrato</td>
		 <td>Rh</td>
		 <td>Nivel siben</td>
		 <td>Editar</td>
		 <td>Eliminar</td>
	 </tr>";
	
	$index=1;
	
	$datos=mysql_query("SELECT *  FROM matricula $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		$index++;
		
		$id=$reg['id'];
		$numero=$reg['numero'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$grupo=$reg['grupo'];
		$acudiente=$reg['acudiente'];
		$parentesco=$reg['parentesco'];
		$ciudadDoc=$reg['ciudadDoc'];
		$telefono=$reg['telefono'];
		$direccion=$reg['direccion'];
		$fechaNac=$reg['fechaNac'];
		$email=$reg['email'];
		$eps=$reg['eps'];
		$desplazado=$reg['desplazado'];
		$familiasEnAccion=$reg['familiasEnAccion'];
		$religion=$reg['religion'];
		$estrato=$reg['estrato'];
		$rh=$reg['rh'];
		$nivelSisben=$reg['nivelSisben'];
		$representante=$reg['representantegrupo'];
		
		
		$complementoGrupo="<div class='error'>Grupo sin registrar</div>";
		$datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
	    while($reg2=mysql_fetch_array($datos2)){
		   $grupot=$reg2['nombre'];
		   $complementoGrupo="<div class='notificaciones'>$grupot</div>";
	    }
		
		$complementoAcudiente="<div class='error'>Acudiente sin registrar</div>";
		$datos2=mysql_query("SELECT nombres,apellidos FROM acudiente WHERE id='$acudiente'");
	    while($reg2=mysql_fetch_array($datos2)){
		   $acudientet=$reg2['nombres']." ".$reg2['apellidos'];
		   $complementoAcudiente="<div class='notificaciones'>$acudientet</div>";
	    }
		
		
		$complementorepresentante=($representante=="si")?' <img src="../imagenes/iconoRepresentante.png" title="Medalla de representante de grupo" class="imagenRepresentanteGrupo" />':"";
		
		
		$respuesta.="<tr class='$classTR'>
		<td>$numero</td>
		<td>$docId</td>
		<td>$nombres</td>
		<td>$apellidos</td>
		<td style='text-align:center'>$complementoGrupo $complementorepresentante</td>
		<td>$complementoAcudiente</td>
		<td>$parentesco</td>
		<td>$ciudadDoc</td>
		<td>$telefono</td>
		<td>$direccion</td>
		<td>$fechaNac</td>
		<td>$email</td>
		<td>$eps</td>
		<td>$desplazado</td>
		<td>$familiasEnAccion</td>
		<td>$religion</td>
		<td>$estrato</td>
		<td>$rh</td>
		<td>$nivelSisben</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarMatricula($id)' /></td>
		</tr>";
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 
@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT *  FROM matricula WHERE id='$idF'");
    while($reg=mysql_fetch_array($datos)){
		
		$id=$reg['id'];
		$numero=$reg['numero'];
		$docId=$reg['docId'];
		$nombres=$reg['nombres'];
		$apellidos=$reg['apellidos'];
		$grupo=$reg['grupo'];
		$acudiente=$reg['acudiente'];
		$parentesco=$reg['parentesco'];
		$ciudadDoc=$reg['ciudadDoc'];
		$telefono=$reg['telefono'];
		$direccion=$reg['direccion'];
		$fechaNac=$reg['fechaNac'];
		$email=$reg['email'];
		$eps=$reg['eps'];
		$desplazado=$reg['desplazado'];
		$familiasEnAccion=$reg['familiasEnAccion'];
		$religion=$reg['religion'];
		$estrato=$reg['estrato'];
		$rh=$reg['rh'];
		$nivelSisben=$reg['nivelSisben'];
		$representante=$reg['representantegrupo'];
		
		$_SESSION['matriculaEdit']=$id;
		
		echo '
		<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarMatricula.php" method="post">
                        <fieldset class="step">
						<input type="hidden" value="'.$id.'" name="acId"  readonly="readonly"/>
                            <legend>ACTUALIZAR MATRICULA</legend>
                            <div class="divisoresForms">Información requerida</div>
                            <p>
                                <label for="username">Numero(*)</label>
                                <input value="'.$numero.'" onkeyup="validarNumeroMatricula(this.value)" name="ma1" required />
                                 <span class="error" id="existeNumMat"></span>
                            </p>
                            <p>
                                <label for="username">Documento de identidad(*)</label>
                                <input value="'.$docId.'"  name="ma2" onkeyup="validarDocIdMatricula(this.value)" required />
                                <span class="error" id="existeDocMat"></span>
                            </p>
                            <p>
                                <label for="username">Nombres(*)</label>
                                <input value="'.$nombres.'"  name="ma3" required />
                            </p>
                            <p>
                                <label for="email">Apellidos(*)</label>
                                <input value="'.$apellidos.'"  name="ma4" required type="text" AUTOCOMPLETE=OFF />
                            </p>
                            
                           <p>
                                <label for="username">Grupo(*)</label>
                                
                               
                               ';
							     $nomGrupo="";
								 $hay=false;
								 $datos2=mysql_query("SELECT nombre FROM grupo WHERE id='$grupo'");
                                   while($reg2=mysql_fetch_array($datos2)){
									   $nomGrupo=$reg2['nombre'];
									   $hay=true;
								   }
							   
							     $centro=$_SESSION['CentroEducativo'];
                                 
								 
								 $datos2=mysql_query("SELECT * FROM grupo WHERE centroEducativo='$centro' AND id!='$grupo' ORDER BY nombre");
                                
								echo"<select name='ma5' required><option value='$grupo'>$nomGrupo</option>";
								
                                   while($reg2=mysql_fetch_array($datos2)){
									   $hay=true;
									   $id=$reg2['id'];
									   $nombre=$reg2['nombre'];
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay grupos registrados en el actual centro educativo.</span>";	
								}
								
                               
							   $checkRep=($representante=="si")?'checked="checked"':'';
                            echo '</p>
                            
                            <div class="divisoresForms">Información adicional</div>
                            
							
							 <p>
                                <label for="password">Representante de grupo:</label>
                                <input type="checkbox" style="float:left; margin-top:20px" '.$checkRep.' name="ma20" />
                            </p>
							
                             <p>
                                <label for="password">Acudiente</label>
                               ';
							   $nombreAcu="";
							   $hay=false;
							   $datos2=mysql_query("SELECT docId,nombres,apellidos FROM acudiente WHERE id='$acudiente'");
                                   while($reg2=mysql_fetch_array($datos2)){
									   $nombreAcu=$reg2['nombres']." ".$reg2['apellidos']."(".$reg2['docId'].")";
									   $hay=true;
								   }
							   
								 echo"<select name='ma6' ><option value='$acudiente'>$nombreAcu</option>";
							     $centro=$_SESSION['CentroEducativo'];
								 
                                 $datos2=mysql_query("SELECT id,docId,nombres,apellidos FROM acudiente WHERE centroEducativo='$centro' AND id!='$acudiente' ORDER BY nombres, apellidos");
                                   while($reg2=mysql_fetch_array($datos2)){
									   $hay=true;
									   $id=$reg2['id'];
									   $nombre=$reg2['nombres']." ".$reg2['apellidos']."(".$reg2['docId'].")";
									   echo "<option value='$id'>$nombre</option>";
								   }
						        echo"</select>";
								if($hay==false){
								  echo"<br><br><span class='error'>No hay Acudientes registrados en el actual centro educativo.</span>";	
								}
								
                           echo ' </p>
                            
                            <p>
                                <label for="password">Parentesco</label>
                                <select name="ma7" >
                                <option>'.$parentesco.'</option>
                               ';
                                 $datos=mysql_query("SELECT * FROM parentesco");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $pr=$reg['nombre'];
									   echo "<option>$pr</option>";
								   }
						
								echo'
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Ciudad documento</label>
                                <input value="'.$ciudadDoc.'" type="text" name="ma8" />
                            </p>
                            
                            <p>
                                <label for="password">Teléfono</label>
                                <input value="'.$telefono.'" type="text" name="ma9" />
                            </p>
                            
                            <p>
                                <label for="password">Dirección</label>
                                <input value="'.$direccion.'" type="text" name="ma10" />
                            </p>
                            <p>
                                <label for="password">Fecha de nacimiento</label>
                                <input value="'.$fechaNac.'" type="text" placeholder="dd-mm-yy" name="ma11" id="ma11" />
                            </p>
                            <p>
                                <label for="password">Email</label>
                                <input value="'.$email.'" type="email" name="ma12" />
                            </p>
                            
                            <p>
                                <label for="password">EPS</label>
                                <select name="ma13" >
                                <option>'.$eps.'</option>
                               ';
                                 $datos=mysql_query("SELECT * FROM eps");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $eps=$reg['nombre'];
									   echo "<option>$eps</option>";
								   }
						
								echo'
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Desplazado</label>
                                <select name="ma14">
                                <option>'.$desplazado.'</option>
                                <option>No</option>
                                <option>Si</option>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Familias en acción</label>
                                <select name="ma15">
                                <option>'.$familiasEnAccion.'</option>
                                <option>No</option>
                                <option>Si</option>
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Religión</label>
                                <select name="ma16" >
                                <option>'.$religion.'</option>
                                ';
                                 $datos=mysql_query("SELECT * FROM religion");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $rel=$reg['nombre'];
									   echo "<option>$rel</option>";
								   }
						
								echo'
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Estrato</label>
                                <input value="'.$estrato.'" type="number" name="ma17" />
                            </p>
                            
                            <p>
                                <label for="password">Rh</label>
                                <select name="ma18" >
                                <option>'.$rh.'</option>
                               ';
                                 $datos=mysql_query("SELECT * FROM rh");
                                
                                   while($reg=mysql_fetch_array($datos)){
									   $rh=$reg['nombre'];
									   echo "<option>$rh</option>";
								   }
						
								echo'
                                </select>
                            </p>
                            
                            <p>
                                <label for="password">Nivel del sisben</label>
                                <input value="'.$nivelSisben.'" type="number" name="ma19" />
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


// actualizar información de matricula
@$acId=$_POST['acId'];
if(isset($acId)){
	$ma1=$_POST['ma1'];//numero	
	$ma2=$_POST['ma2'];//docid
	$ma3=$_POST['ma3'];//nombres
	$ma4=$_POST['ma4'];//apellidos
	$ma5=$_POST['ma5'];//grupo
	$ma6=$_POST['ma6'];//acudiante
	$ma7=$_POST['ma7'];//parentesco
	$ma8=$_POST['ma8'];//ciudad docId
	$ma9=$_POST['ma9'];//telefono
	$ma10=$_POST['ma10'];//direccion
	$ma11=$_POST['ma11'];//fechaNac
	$ma12=$_POST['ma12'];//e-mail
	$ma13=$_POST['ma13'];//eps
	$ma14=$_POST['ma14'];//desplazado
	$ma15=$_POST['ma15'];//familias en acción
	$ma16=$_POST['ma16'];//religion
	$ma17=$_POST['ma17'];//estrato
	$ma18=$_POST['ma18'];//rh
	$ma19=$_POST['ma19'];//nivel sisven
	
	$ma20="no";
	if(isset($_POST['ma20'])){
		$ma20="si";               // representante de grupo
	}
	
	$existe1=validarNumMatricula($ma1,$_SESSION['matriculaEdit']);
	$existe2=validarDocMatricula($ma2,$_SESSION['matriculaEdit']);
	
	if($existe1=="si" || $existe2=="si"){
		header("location:../../administrador/buMatricula.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buMatricula.php?error=si";</script>'; 
	}
	else{
		mysql_query("UPDATE `matricula` SET numero='$ma1', docId='$ma2', nombres ='$ma3', apellidos='$ma4', grupo='$ma5', acudiente='$ma6', parentesco='$ma7', ciudadDoc='$ma8', telefono='$ma9', direccion='$ma10', fechaNac='$ma11', email='$ma12', eps='$ma13', desplazado='$ma14', familiasEnAccion='$ma15', religion='$ma16', estrato='$ma17', rh='$ma18', nivelSisben='$ma19', representantegrupo='$ma20' WHERE id='$acId'",$con);
		$_SESSION['matriculaEdit']=0;
		
		
		// insertar notas si existen
		 $anio=$_SESSION['anio'];
		 $periodo=$_SESSION['periodo'];
		 
		 $datos=mysql_query("SELECT id FROM conceptoevaluacion WHERE grupo='$ma5' AND anio='$anio' AND periodo='$periodo'");
		 while($reg=mysql_fetch_array($datos)){
			 $idC=$reg['id'];
			 
			 $existe=false;
			 $datos2=mysql_query("SELECT id FROM planilla WHERE matricula='$acId' AND concepto='$idC'");
		     while($reg2=mysql_fetch_array($datos2)){
				 $existe=true;
			 }
			 if($existe==false){
			  mysql_query("INSERT INTO planilla (matricula,concepto,nota) VALUES ('$acId','$idC','0')",$con);
			 }
		 }
		
		header("location:../../administrador/buMatricula.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buMatricula.php?error=no";</script>'; 
	}
	
}

?>