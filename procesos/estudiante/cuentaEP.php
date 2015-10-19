<?php
include("../conexion.php");
include("validarIngresoE.php");
// validar cuenta actual
@$susAnt=$_POST['logAntE'];
if(isset($susAnt)){
	$pwAnt=$_POST['pwAntE'];
	
	$usuActual=$_SESSION['idUsuario'];
	
	$valido="no";
	$datos=mysql_query("SELECT usu,pw FROM usuario WHERE id='$usuActual'");
	while($reg=mysql_fetch_array($datos)){
		
		$usu=$reg['usu'];
		$pw=$reg['pw'];
		
		if($susAnt==$usu && $pwAnt==$pw){
			$valido="si";
		}
	}
	
	echo $valido;
}


// validar nuevo nombre de usuario

@$usuNuevo=$_POST['valUsuNuevo'];
if(isset($usuNuevo)){
	$usuActual=$_SESSION['idUsuario'];
	
	$esta="no";
	$datos=mysql_query("SELECT id FROM usuario WHERE id!='$usuActual' AND usu='$usuNuevo'");
	while($reg=mysql_fetch_array($datos)){
		$esta="si";
	}
	
	echo $esta;
}

// actualizar cuenta
@$usuaAnt=$_POST['logAnt'];
if(isset($usuaAnt)){
	$pwAnt=$_POST['pwAnt'];
	$nomNuevo=$_POST['nomNuevo'];
	$nomUsuNuevo=$_POST['nomUsuNuevo'];
	$pwNuevo=$_POST['pwNuevo'];
	$pwNuevo2=$_POST['pwNuevo2'];
	
	$valido=true;
	
	$usuActual=$_SESSION['idUsuario'];
	
	$datos=mysql_query("SELECT usu,pw FROM usuario WHERE id='$usuActual'");
	while($reg=mysql_fetch_array($datos)){
		$usu=$reg['usu'];
		$pw=$reg['pw'];
		if($usuaAnt!=$usu || $pwAnt!=$pw){
			$valido=false;
		}
	}
	
	$datos=mysql_query("SELECT id FROM usuario WHERE id!='$usuActual' AND usu='$nomNuevo'");
	while($reg=mysql_fetch_array($datos)){
		$valido=false;
	}
	
	if($pwNuevo!=$pwNuevo2){
		$valido=false;
	}
	
	if($valido==true){
		$pwNuevo=$pwNuevo;
		mysql_query("UPDATE usuario SET nombre='$nomNuevo',usu='$nomUsuNuevo', pw='$pwNuevo'  WHERE id='$usuActual'",$con);
		header("location:../../estudiante/cuentaE.php?errorAct=no");
		echo '<script type="text/javascript">window.location.href="../../estudiante/cuentaE.php?errorAct=no";</script>'; 
		
	}
	else{
		header("location:../../estudiante/cuentaE.php?errorAct=si");
		echo '<script type="text/javascript">window.location.href="../../estudiante/cuentaE.php?errorAct=si";</script>'; 
	}
	
	
}


?>