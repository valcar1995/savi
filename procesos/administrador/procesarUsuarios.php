<?php
include("../conexion.php");
// validar si existe un nombre de usuario

function validarUsuario($acLogUsu,$acId){
	$existe="no";
	$datos=mysql_query("SELECT id FROM usuario WHERE usu='$acLogUsu' AND id!='$acId'");
	 while($reg=mysql_fetch_array($datos)){
		 $existe="si";
	 }
	 return $existe;
}



@$usure=$_POST['usuRe'];
if(isset($usure)){
	echo validarUsuario($usure,$_SESSION['usuarioEdit']);
}

// obtener los datos de un usuario por el documento

@$usuOb=$_POST['usuOb'];
if(isset($usuOb)){
	
	$respuesta="";
	
	
	// profesor
	$datos=mysql_query("SELECT nombres,apellidos FROM profesor WHERE docId='$usuOb' ");
	 while($reg=mysql_fetch_array($datos)){
		 $respuesta=$reg['nombres']." ".$reg['apellidos']." (Profesor)";
	 }
	
	
	
	 
	 // estudiante
	$datos=mysql_query("SELECT nombres,apellidos FROM matricula WHERE docId='$usuOb'");
	 while($reg=mysql_fetch_array($datos)){
		 $respuesta=$reg['nombres']." ".$reg['apellidos']." (Estudiante)";
	 }

	 echo $respuesta;
}


// registrar un nuevo usuario

@$reLogUsu=$_POST['reUsulog'];
if(isset($reLogUsu)){
	$rePwUsu=$_POST['reUsupw'];
	$reperUsu=$_POST['reUsuper'];
	$redocUsu=$_POST['reUsudoc'];
	$centroEducativo=$_SESSION['CentroEducativo'];
	$nombre=$_POST['reUsunom'];
	
	$padre=$_SESSION['idUsuario'];
	
	$existe=validarUsuario($reLogUsu,0);
	
	if($existe=="si"){
		header("location:../../administrador/reUsuarios.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/reUsuarios.php?error=si";</script>'; 
	}
	else{
		
		
		mysql_query("INSERT INTO usuario (nombre,usu,pw,perfil,docId,usuarioPadre,centroEducativo) VALUES ('$nombre','$reLogUsu','$rePwUsu','$reperUsu','$redocUsu','$padre','$centroEducativo')",$con);
		
		if($reperUsu=="Administrador"){
			$idUsuario=0;
			
			$datos=mysql_query("SELECT id FROM usuario ORDER BY id DESC limit 1");
			 while($reg=mysql_fetch_array($datos)){
				 $idUsuario=$reg['id'];
			 }
			
			// registrar permisos
			@$canti=$_POST['cantidadCentrosPer'];
			if(isset($canti)){
				for($i=1; $i<=$canti; $i++){
					@$cen=$_POST['c'.$i];
					if(isset($cen)){
						mysql_query("INSERT INTO usuariocentros (usuario,centro) VALUES ('$idUsuario','$cen')",$con);
					}
				}
			}
		}
		header("location:../../administrador/reUsuarios.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/reUsuarios.php?error=no";</script>'; 
	}
}

// buscar usuarios
@$idInicial=$_POST['idInicial'];
if(isset($idInicial)){
	$cant=intval($_POST['cantidad']);
	
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	$padre=$_SESSION['usuarioPadre'];
	 
	 $respuesta="<table border='1' cellpadding='3' cellspacing='0' class='tablasBusquedas'>
	 <tr class='titulosTablas'>
		 <td>Documento de Identidad</td>
		 <td>Nombre</td>
		 <td>Nombre de Usuario</td>
		 <td>Contraseña</td>
		 <td>Perfil</td>
		 <td>Usuario Padre</td>
		 <td>Editar</td>
		 <td>Eliminar</td>
	 </tr>";
	
	if($centro=="no"){
		$textFiltro=" WHERE (id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (id!='$padre') ORDER BY id ";
	}
	else{
		$centroEducativo=$_SESSION['CentroEducativo'];
		$textFiltro=" WHERE(id>'$idInicial') AND ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (centroEducativo='$centroEducativo') AND (id!='$padre') ";
	}
	
	
	$index=1;
	
	$datos=mysql_query("SELECT *  FROM usuario $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		
		$classTR=($index%2==0)?"trOscuros":"trBlancos";
		
		$id=$reg['id'];
		$usu=$reg['usu'];
		$pw=$reg['pw'];
		$perfil=$reg['perfil'];
		$doc=$reg['docId'];
		$conplementoDocId="";
		$nombre=$reg['nombre'];
		$usuarioPadre=$reg['usuarioPadre'];
		
		$datos2=mysql_query("SELECT nombre FROM usuario WHERE id='$usuarioPadre'");
	        while($reg2=mysql_fetch_array($datos2)){
					$usuarioPadre=$reg2['nombre']; 
			}
		
		switch ($perfil) {
			case "Administrador":
			      $conplementoDocId="";
			break;
			case "Profesor":
			     $conplementoDocId="<div class='error'>(Profesor sin registrar)</div>";
			     $datos2=mysql_query("SELECT nombres,apellidos FROM profesor WHERE docId='$doc'");
	             while($reg2=mysql_fetch_array($datos2)){
		            $nom=$reg2['nombres']." ".$reg2['apellidos'];
					$conplementoDocId="<div class='complementosTablas'>($nom)</div>";
	             }
				
			break;
			case "Estudiante":
				
			 $conplementoDocId="<div class='error'>(Estudiante sin registrar)</div>";
			     $datos2=mysql_query("SELECT nombres,apellidos FROM matricula WHERE docId='$doc'");
	             while($reg2=mysql_fetch_array($datos2)){
		            $nom=$reg2['nombres']." ".$reg2['apellidos'];
					$conplementoDocId="<div class='complementosTablas'>($nom)</div>";
	             }
				
			break;
		}
		
		$miPadre=$_SESSION['usuarioPadre'];
		
		if(esPadre($miPadre,$id)!=true){
		$index++;
		$respuesta.="<tr class='$classTR'>
		<td>$doc $conplementoDocId</td>
		<td>$nombre</td>
		<td>$usu</td>
		<td><div class='dvsContrasenas'>$pw</div></td>
		<td>$perfil</td>
		<td>$usuarioPadre</td>
		<td style='text-align:center'><img src='../imagenes/iconoModificar.png' class='imgEditar' onclick='obtenerFormularioEdicion($id)' /></td>
		<td style='text-align:center'><img src='../imagenes/iconoEliminar.png' class='imgEliminar' onclick='eliminarUsuario($id)' /></td>
		</tr>";
		}
		
		if($index>$cant){
			break;
		}
		
		
	 }
	 $respuesta.="</table>";
	 echo $respuesta;
	
}

// obtener formulario de edicion 

@$idF=$_POST['idFormulario'];
if(isset($idF)){
	$datos=mysql_query("SELECT * FROM usuario WHERE id='$idF'");
	 while($reg=mysql_fetch_array($datos)){
		 
		 $id=$reg['id'];
		 $usu=$reg['usu'];
		 $pw=$reg['pw'];
		 $perfil=$reg['perfil'];
		 $docId=$reg['docId'];
		 $nombre=$reg['nombre'];
		 $usuarioPadre=$reg['usuarioPadre'];
		 
		 $displayPermisos=($perfil=="Administrador")?"block":"none";
		 
		 $_SESSION['usuarioEdit']=$id;
		 
		 echo '
		 <div id="content" style="position:relative; bottom:10px;">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarUsuarios.php" method="post">
                        
						<input type="hidden" name="acId" value="'.$id.' readonly="readonly">
						
						<fieldset class="step">
                            <legend>ACTUALIZAR USUARIO</legend>
							
							<p>
                                <label for="password">Documento de identidad</label>
                                <input id="acUsudoc" value="'.$docId.'" name="acUsudoc" onkeyup="ObtenerUsuario(this.value)"   required="required" type="text" AUTOCOMPLETE=OFF />
                                <br /><br /><span class="notificaciones" id="infoUsu"></span>
								<script>ObtenerUsuario('.$docId.')</script>
                            </p>
							
							<p>
                                <label for="username">Nombre(*)</label>
                                <input value="'.$nombre.'" type="text" name="acUsunom"  required />
                            <p>
                            
                            <p>
                                <label for="username">Nombre de usuario(*)</label>
                                <input value="'.$usu.'" name="acUsulog" onkeyup="validarUsuario(this.value)" required />
                                <span class="error" id="existeUsu"></span>
                            </p>
                            
                            <p>
                                <label for="password">Contraseña(*)</label>
                                <input name="acUsupw"  type="password" AUTOCOMPLETE=OFF />
                                
                            </p>
                            
                            
                             <p>
                                <label for="password">PERFIL(*)</label>
                                 <select name="acUsuper" id="reUsuper">
								 <option>'.$perfil.'</option>
                                    <option>Administrador</option>
                                     <option>Profesor</option>
                                      <option>Estudiante</option>
                                 </select><br /><br />
                            </p>
							
							<div id="divPerisosAdmin" style="text-align:center; display:'.$displayPermisos.'">
                               <div class="divisoresForms">Seleccione los centros educativos a los cuales el usuario tendrá permisos</div>
                               
                               <div id="contenChecsAdmin">
                               <table border="0" width="100%">
                               
                              ';
							     
								 // validar si el usuario a modificar fue creado por el usuario actual
								 
								
							  
							     $idUsu=($usuarioPadre==$_SESSION['idUsuario'])?$usuarioPadre:$id;
							     $cantidadDatos=0;
								 $datos=mysql_query("SELECT count(*) as total FROM usuariocentros WHERE usuario='$idUsu' ");
								 while($reg=mysql_fetch_array($datos)){
									 $cantidadDatos=$reg['total'];
								 }
								 
							     echo "<input type='hidden' value='$cantidadDatos' readonly='readonly' name='cantidadCentrosPer' />";
							     
								 $index=1;
							     $datos=mysql_query("SELECT pc.centro, c.nombre, pc.usuario FROM usuariocentros pc INNER JOIN centroeducativo c ON c.id=pc.centro WHERE pc.usuario='$idUsu' ");
								 while($reg=mysql_fetch_array($datos)){
									 
									 $centroId=$reg['centro'];
									 $centroNombre=$reg['nombre'];
									 $usuario=$reg['usuario'];
									 $nomCam="c".$index;
									 $check="";
									 
									 $datos3=mysql_query("SELECT id FROM usuariocentros  WHERE usuario='$id' AND centro='$centroId' ");
									 while($reg3=mysql_fetch_array($datos3)){
										$check="checked"; 
									 }
									 
									 echo'
									   <tr>
									   <td class="tdsChecks"><input '.$check.' type="checkbox" name="'.$nomCam.'" value="'.$centroId.'" /></td>
									   <td class="tdsValoreschecks" width="90%"><label class="lblsValoresPermisos">'.$centroNombre.'</label></td>
									   </tr>
									 ';
									 $index++;
								 }
							   
							  echo'

     
                               </table>
                               </div>
                               </div>
                            
                            
                            
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
	$acLogUsu=$_POST['acUsulog'];
	
	$actPw="";
	if($_POST['acUsupw']!=""){
		$acPw=$_POST['acUsupw'];
		$actPw="pw='$acPw',";
	}
	$acPwUsu=$_POST['acUsupw'];
	$acperUsu=$_POST['acUsuper'];
	$acdocUsu=$_POST['acUsudoc'];
	$acnomUsu=$_POST['acUsunom'];
	
	$existe=validarUsuario($acLogUsu,$_SESSION['usuarioEdit']);
	
	
	
	if($existe=="si"){
		header("location:../../administrador/buUsuarios.php?error=si");
		echo '<script type="text/javascript">window.location.href="../../administrador/buUsuarios.php?error=si";</script>'; 
	}
	else{
		
		mysql_query("UPDATE usuario SET docId='$acdocUsu',nombre='$acnomUsu',usu='$acLogUsu',$actPw perfil='$acperUsu' WHERE id='$acId'",$con);
		
		mysql_query("DELETE FROM usuariocentros WHERE usuario='$acId'");
		// registrar permisos
		   if($acperUsu=="Administrador"){
				@$canti=$_POST['cantidadCentrosPer'];
				if(isset($canti)){
					for($i=1; $i<=$canti; $i++){
						@$cen=$_POST['c'.$i];
						if(isset($cen)){
							mysql_query("INSERT INTO usuariocentros (usuario,centro) VALUES ('$acId','$cen')",$con);
						}
					}
				}
		   }
		
		
		$_SESSION['usuarioEdit']=0;
		header("location:../../administrador/buUsuarios.php?error=no");
		echo '<script type="text/javascript">window.location.href="../../administrador/buUsuarios.php?error=no";</script>'; 
	}
}


function esPadre($idInicial,$IdmiPadre){
	
	$esta=false;
	$continua=true;
	
	do{
		
		if($idInicial==$IdmiPadre){
			$esta=true;
	        $continua=false;
		}
		else{
			$datos3=mysql_query("SELECT usuarioPadre  FROM usuario WHERE id='$idInicial'");
            while($reg3=mysql_fetch_array($datos3)){
				$idInicial=$reg3['usuarioPadre'];
			}
		}
		
	}while($idInicial!=0 && $continua==true);
	
	
	return $esta;
	
}


// obtener formulario registro para una matricula desde index
@$numMari=$_POST['NumMatri'];
if(isset($numMari)){
	
	session_destroy();
	
	$docIuM=$_POST['DocIdMatri'];
	$respuesta="";
	$existe=false;
	$existeMat=false;
	
	$datos=mysql_query("SELECT id  FROM usuario WHERE docId='$docIuM'");
     while($reg=mysql_fetch_array($datos)){
		$existe=true;
		$respuesta="<div style='width:300px' class='noticiaError'>Ya existe un usuario con ese documento de identidad. Comunícate con las administrativas de tu centro educativo para que te solucionen el problema</div>";		
     }
	 
	 if($existe==false){
		 
		 $nombres="";
		 $centroEd="";
		 $idMatri="";
		 $idCentroEd=0;
		 $datos=mysql_query("SELECT m.id,m.nombres,m.apellidos,m.centroeducativo,c.nombre  FROM matricula m INNER JOIN centroeducativo c ON m.centroeducativo=c.id WHERE m.docId='$docIuM' AND m.numero='$numMari'");
		 while($reg=mysql_fetch_array($datos)){
			 $existeMat=true;
			 $idMatri=$reg['id'];
			 $nombres=$reg['nombres']." ".$reg['apellidos'];
			 $centroEd=$reg['nombre'];
			 $idCentroEd=$reg['centroeducativo'];
			 
		 }
		 
		 if($existeMat==true){
			 session_start();
			 $_SESSION['MatriculaValida']=$idMatri;
			 $_SESSION['centroMatricula']=$idCentroEd;
			 $_SESSION['docIdEstReg']=$docIuM;
		   $respuesta='
		   
		      <div id="content">
         <button class="btnCerrar" onclick="cerrarFormRegistro()">X</button>  
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" action="procesos/administrador/procesarUsuarios.php" method="post">
                        <fieldset class="step">
                            <legend>OBTENER CUENTA</legend>
                            
							<div class="notificacionValida">Usted se encuentra matriculado en el centro educativo '.$centroEd.', por favor complete el formulario para llevar a cabo su registro.</div>
                            
                            <p>
                                <label for="password">Nombre(*)</label>
                                <input id="reUsuNombreInd" value="'.$nombres.'"  name="reUsuNombreInd" type="text" AUTOCOMPLETE=OFF required="required" />
                               
                            </p>
                            <p>
                                <label for="password">Nombre de usuario(*)</label>
                                <input id="reUsuNomUsu" name="reUsuNomUsu" type="text" onkeyup="validarSiExisteUsu(this.value)" AUTOCOMPLETE=OFF required="required"/>
                                <span class="error" id="validaExisteUsu"></span>
                            </p>
							
							<p>
                                <label for="password">Contraseña(*)</label>
                                <input id="reUsucontra" name="reUsucontra" type="password" AUTOCOMPLETE=OFF required="required" />
                               
                            </p>
							
							<p>
                                <label for="password">Confirmar contraseña(*)</label>
                                <input id="reUsucontra2" name="reUsucontra2" type="password" AUTOCOMPLETE=OFF required="required" />
                               
                            </p>
							
                            <p class="submit">
                                <button id="registerButton" type="submit" onclick=" return validarRegistroUsuario()">Registrarme</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
				</div>
               
            </div>
        </div>
		   
		   ';
	      }
		  else{
			  $respuesta="<div style='width:300px' class='noticiaError'>No apareces registrado en ningun centro educativo. Comunícate con las administrativas de tu centro educativo para que te solucionen el problema</div>";
		  }
	 }
	 
	 echo $respuesta;
}


// validar si existe un usuario desde el registro del index
@$existeUsu=$_POST['existeUsuIndex'];
if(isset($existeUsu)){
	echo validarUsuario($existeUsu,0);
}

// registrar usuario desde index
@$nomUsu=$_POST['reUsuNombreInd'];
if(isset($nomUsu)){
	$logUsu=$_POST['reUsuNomUsu'];
	$pw1Usu=$_POST['reUsucontra'];
	$pw2Usu=$_POST['reUsucontra2'];
	@$idmatriC=$_SESSION['MatriculaValida'];
	@$centroEd=$_SESSION['centroMatricula'];
	@$docIdESt=$_SESSION['docIdEstReg'];
	
	$valido=false;
	
	if(isset($idmatriC) && isset($centroEd) && isset($docIdESt) && $pw1Usu==$pw2Usu){
		$existe=validarUsuario($logUsu,0);
		if($existe=="no"){
			$valido=true;
			$pw1Usu=$pw1Usu;
			mysql_query("INSERT INTO usuario (nombre,usu,pw,perfil,docId,usuarioPadre,centroEducativo) VALUES ('$nomUsu','$logUsu','$pw1Usu','Estudiante','$docIdESt','0','$centroEd')",$con);
		}
	}
	
	if($valido==true){
	  header("location:../../index.php?errorR=no");	
	  echo '<script type="text/javascript">window.location.href="../../index.php?errorR=no";</script>'; 
	}
	else{
		header("location:../../index.php?errorR=si");
		echo '<script type="text/javascript">window.location.href="../../index.php?errorR=si";</script>'; 	
	}
	session_destroy();
}
?>