<?php
@$profe=$_SESSION['profe'];
if($profe!=true){
	header("location:../../paginaError.php?error=Acceso inadecuado al sistema");
	echo '<script type="text/javascript">window.location.href="../../paginaError.php?error=Acceso inadecuado al sistema";</script>';
  return;
}
?>