
<?php
include("../procesos/token.php");

$idUsu=$_SESSION['idUsuario'];
$cantidadCentros=0;
$datos=mysql_query("SELECT count(*) as total FROM usuariocentros WHERE usuario='$idUsu'");
 while($reg=mysql_fetch_array($datos)){
	 $cantidadCentros=$reg['total'];
 }

if($cantidadCentros==0){
	$mens="Su usuario no tiene centros educativos asignados.";
	header("location:../paginaError.php?error=$mens");
	echo '<script type="text/javascript">window.location.href="../paginaError.php?error=$mens";</script>'; 
}
else{
	if($cantidadCentros==1){
		
		$cenEle=0;
		$datos=mysql_query("SELECT centro FROM usuariocentros WHERE usuario='$idUsu'");
		 while($reg=mysql_fetch_array($datos)){
			 $cenEle=$reg['centro'];
		 }
		
		$_SESSION['CentroEducativo']=$cenEle;
	
		$datos=mysql_query("SELECT anio,periodo  FROM centroeducativo WHERE id='$cenEle'");
		while($reg=mysql_fetch_array($datos)){
			$_SESSION['anio']=$reg['anio'];
			$_SESSION['periodo']=$reg['periodo'];
			
		}
		
		//echo "cen=".$_SESSION['CentroEducativo'].",an=".$_SESSION['anio'].",per=".$_SESSION['periodo'];
		
		header("location:reUsuarios.php");
		echo '<script type="text/javascript">window.location.href="reUsuarios.php";</script>'; 
	}
	
	else{
		$idUsu=$_SESSION['idUsuario'];
		$cenEle=0;
		$datos=mysql_query("SELECT centro FROM usuariocentros WHERE usuario='$idUsu' limit 1");
		 while($reg=mysql_fetch_array($datos)){
			 $cenEle=$reg['centro'];
		 }
		
		$_SESSION['CentroEducativo']=$cenEle;
	
		$datos=mysql_query("SELECT anio,periodo  FROM centroeducativo WHERE id='$cenEle'");
		while($reg=mysql_fetch_array($datos)){
			$_SESSION['anio']=$reg['anio'];
			$_SESSION['periodo']=$reg['periodo'];
			
		}
	}
}


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Elegir Centro</title>

<link rel="stylesheet" type="text/css" href="../css/administrador/formularios.css"/>
<link rel="stylesheet" type="text/css" href="../css/administrador/elegirCentro.css"/>
</head>

<body>

<center>


<div id="contenElige">

<div id="content">
           
            <div id="wrapper">
                <div id="steps">
                    <form id="formElem" name="formElem" action="../procesos/administrador/procesarCentroElegido.php" method="post">
                        <fieldset class="step">
                            <legend>Elija el centro educativo sobre el cual realizará las acciones</legend>
                            
                            <p style="text-align:center">
                           
                                <select name="cenEleg" style="float:none">
                                <?php
								  $idUsu=$_SESSION['idUsuario'];
								  $datos=mysql_query("SELECT a.id,a.nombre FROM centroeducativo a INNER JOIN usuariocentros b ON a.id=b.centro WHERE a.id!='$idCentro' AND b.usuario='$idUsu' ORDER BY a.nombre");
                                   while($reg=mysql_fetch_array($datos)){
										 $id=$reg['id'];
										 $nom=$reg['nombre'];
										 echo"<option value='$id'>$nom</option>";
									 }
								  
								?>
                                </select>
                                
                            </p>
                            
                            <p class="submit">
                                <button id="registerButton" type="submit">Continuar</button>
                            </p>
                        </fieldset>
                       
                       
                       
						
                    </form>
						
				</div>
               
            </div>
        </div>

</div>

</center>

</body>
</html>