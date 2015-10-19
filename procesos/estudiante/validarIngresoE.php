<?php
@$est=$_SESSION['estudiante'];
if($est!=true){
	header("location:../../paginaError.php?error=Acceso inadecuado al sistema");
	echo '<script type="text/javascript">window.location.href="../../paginaError.php?error=Acceso inadecuado al sistema";</script>';
  return;
}
?>