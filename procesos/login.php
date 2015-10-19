<?php
include("conexion.php");

// procesar login del inicio
@$log=$_POST['log'];
if(isset($log)){
	
	$pw=$_POST['pw'];
	
	$existe=false;
	$datos=mysql_query("SELECT id,perfil,docId,usuarioPadre,nombre,centroeducativo FROM usuario WHERE usu='".mysql_real_escape_string($log)."' AND pw='".mysql_real_escape_string($pw)."' ");
	 while($reg=mysql_fetch_array($datos)){
		 $id=$reg['id'];
		 $docId=$reg['docId'];
		 $perfil=$reg['perfil'];
		 $padre=$reg['usuarioPadre'];
		 $nombre=$reg['nombre'];
		 $existe=true;
		 
		 //-------------------------------------------Jenifer te amo------------------------------------------------
		 
		 $_SESSION['nombreUsurio']=$nombre;
		 $_SESSION['idUsuario']=$id;
		 $_SESSION['usuarioPadre']=$padre;
		 $_SESSION['docId']=$docId;
		 $_SESSION['log']=true;
		 $_SESSION['CentroEducativo']=$reg['centroeducativo'];
		 $idcentro=$reg['centroeducativo'];
		 
		 $_SESSION['anio']=date("Y");
	     $_SESSION['periodo']=1;
		 
		 $datos2=mysql_query("SELECT anio,periodo FROM centroeducativo WHERE id='$idcentro'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $_SESSION['anio']=$reg2['anio'];
			 $_SESSION['periodo']=$reg2['periodo'];
		 }
		 
		switch ($perfil) {
			
			case "Administrador":
			 iniciarSesionesAdmin();
			 header("location:../administrador/elegirCentro.php");
			 echo '<script type="text/javascript">window.location.href="../administrador/elegirCentro.php";</script>'; 
			 
				
			break;
			case "Profesor":
			    iniciarSesionesProfesor();
			     
				 $existePro=false;
				 $_SESSION['idProfesor']=0;
				 $datos2=mysql_query("SELECT id FROM profesor WHERE docId='$docId'");
	             while($reg2=mysql_fetch_array($datos2)){
					 $_SESSION['idProfesor']=$reg2['id'];
					 $existePro=true;
				 }
				 
				 if($existePro==true){
					  $_SESSION['profe']=true;
					  header("location:../profesor/horario.php");
					  echo '<script type="text/javascript">window.location.href="../profesor/horario.php";</script>'; 
				 }
				 else{
					 header("location:../paginaError.php?error=Aún no estas registrado en el sistema como profesor. Comuníquese con el administrador de la plataforma para que solucione su problema.");
				    echo '<script type="text/javascript">window.location.href="../paginaError.php?error=Aún no estas registrado en el sistema como profesor. Comuníquese con el administrador de la plataforma para que solucione su problema.";</script>'; 
				 }
			
				 
			
				
			break;
			case "Estudiante":
			
			     $existeEst=false;
				 $_SESSION['idEstudiante']=0;
				 $datos2=mysql_query("SELECT id,grupo,representantegrupo FROM matricula WHERE docId='$docId'");
	             while($reg2=mysql_fetch_array($datos2)){
					 $_SESSION['idEstudiante']=$reg2['id'];
					 $_SESSION['idGrupoEstudiante']=$reg2['grupo'];
					 $_SESSION['EstRepresentante']=$reg2['representantegrupo'];
					 $existeEst=true;
				 }
			
			     if($existeEst==true){
				 $_SESSION['estudiante']=true;
				 header("location:../estudiante/horarioE.php");
				 echo '<script type="text/javascript">window.location.href="../estudiante/horarioE.php";</script>'; 
				 }
				 else{
					 header("location:../paginaError.php?error=Aún no estas registrado en el sistema como Estudiante. Comuníquese con el administrador de la plataforma para que solucione su problema.");
				     echo '<script type="text/javascript">window.location.href="../paginaError.php?error=Aún no estas registrado en el sistema como Estudiante. Comuníquese con el administrador de la plataforma para que solucione su problema.";</script>'; 
				 }
			 
				
			break;
	   }
	   
	   
		 
	 }
	 
	if($existe==false){
		header("location:../index.php?error=true");
		echo '<script type="text/javascript">window.location.href="../index.php?error=true";</script>'; 
	}
	
}


function iniciarSesionesAdmin(){
	
	$_SESSION['admin']=true;
	
	$_SESSION['urlActual']="#";
	
	
	
	$_SESSION['usuarioEdit']=0;
	$_SESSION['matriculaEdit']=0;
	$_SESSION['profesorEdit']=0;
	$_SESSION['areaEdit']=0;
	$_SESSION['materiaEdit']=0;
	$_SESSION['grupoEdit']=0;
	$_SESSION['gradoEdit']=0;
	$_SESSION['indicadorEdit']=0;
	$_SESSION['acudienteEdit']=0;
	$_SESSION['centroEdit']=0;
	
}

function iniciarSesionesProfesor(){
	$_SESSION['grupoElegido']=0;
	$_SESSION['materiaElegida']=0;
	$_SESSION['grupoItemElegido']=1;
}






?>
