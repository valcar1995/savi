<?php
include("conexion.php");


//buscar cantidad de datos de una tabla con filtro (pagin)
@$datos=$_POST['datos'];
if(isset($datos)){
	
	$limite=intval($_POST['limitePag']);
	$tipoFiltro=$_POST['tipoFiltro'];
	$filtro=$_POST['filtro'];
	$centro=$_POST['centro'];
	$paginaActual=$_POST['pagina'];
	$textFiltro="";
	$masHorario="";
	$masUsu="";
	$camposel="id";
	
	if($datos=="horario"){
		$anio=$_SESSION['anio'];
		$masHorario="AND (anio='$anio')";
	}
	
	if($datos=="usuario"){
		$padre=$_SESSION['usuarioPadre'];
		$masUsu="AND (id!='$padre')";
	}
	
	if($centro=="no"){
		$textFiltro=" WHERE ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') $masHorario $masUsu";
	
	}
	else{
		$centroEducativo=$_SESSION['CentroEducativo'];
		$textFiltro=" WHERE ($tipoFiltro LIKE '%$filtro%' OR $tipoFiltro LIKE '$filtro%' OR $tipoFiltro LIKE '%$filtro') AND (centroEducativo='$centroEducativo') $masHorario $masUsu";
	}
	
	if($datos=="centroeducativo"){
		$camposel="a.id";
		$idUsu=$_SESSION['idUsuario'];
		$tipoFiltroI="a.".$tipoFiltro;
		$textFiltro="a INNER JOIN usuariocentros b ON a.id=b.centro  WHERE ($tipoFiltroI LIKE '%$filtro%' OR $tipoFiltroI LIKE '$filtro%' OR $tipoFiltroI LIKE '%$filtro') AND b.usuario='$idUsu'";
	
	}
	
	
	$respuesta="";
	$index=1;
	$pag=1;
	$masdeUno=false;
	
	$miPadre=$_SESSION['usuarioPadre'];
	
	$datos=mysql_query("SELECT $camposel  FROM $datos $textFiltro");
    while($reg=mysql_fetch_array($datos)){
		$id=$reg['id'];
		
		$estaP=esPadre($miPadre,$id);
		
		if($index%$limite==0 ){
			
			$idbtn=($paginaActual==$pag)?"pagSelected":"btnPag".$pag;
			
			if($masdeUno==false){
				$masdeUno=true;
				
				$respuesta.="<button id='$idbtn' class='btnsPag' onclick='buscarDatos(0,$pag)'>$pag</button>";
				$pag++;
			}
			
			$idbtn=($paginaActual==$pag)?"pagSelected":"btnPag".$pag;
			
			$respuesta.="<button id='$idbtn' class='btnsPag' onclick='buscarDatos($id,$pag)'>$pag</button>";
			$pag++;
		}
		if($estaP!=true){
		$index++;
		}
     }
	echo $respuesta;
}

function esPadre($idInicial,$IdmiPadre){
	
	$esta=false;
	$continua=true;
	
	
	
	do{
		//echo "inicio=$idInicial, padre=$IdmiPadre n1=$nom1, n2=$nom2 <br>";
		
		if($idInicial==$IdmiPadre){
			$esta=true;
	        $continua=false;
		}
		else{
			$datos3=mysql_query("SELECT usuarioPadre,usu  FROM usuario WHERE id='$idInicial'");
            while($reg3=mysql_fetch_array($datos3)){
				$idInicial=$reg3['usuarioPadre'];
				
			}
		}
		
	}while($idInicial!=0 && $continua==true);
	//echo "inicio=$idInicial, padre=$IdmiPadre ($esta) <br>";
	
	return $esta;
	
}

?>