<?php
include("../conexion.php");
include("validarIngresoA.php");

// eliminar usuario
@$idUsu=$_POST['elimUsu'];
if(isset($idUsu)){
	eliminarUsuario($idUsu);
	echo "si";
}

// eliminar matricula
@$idMatri=$_POST['elimMatri'];
if(isset($idMatri)){
	eliminarMatricula($idMatri);
	echo "si";
}

// eliminar profesor
@$idPro=$_POST['elimPro'];
if(isset($idPro)){
	eliminarProfesor($idPro);
	echo "si";
}

// eliminar area
@$idAre=$_POST['elimAre'];
if(isset($idAre)){
	eliminarArea($idAre);
	echo "si";
}

// eliminar materia
@$idMat=$_POST['elimMat2'];
if(isset($idMat)){
	eliminarMateria($idMat);
	echo "si";
}


// eliminar horario
@$idHor=$_POST['elimHor'];
if(isset($idHor)){
	eliminarHorario($idHor);
	echo "si";
}

// eliminar grupo
@$idGru=$_POST['elimGru'];
if(isset($idGru)){
	eliminarGrupo($idGru);
	echo "si";
}

// eliminar grado
@$idGra=$_POST['elimGra'];
if(isset($idGra)){
	eliminarGrado($idGra);
	echo "si";
}

// eliminar indicador
@$idInd=$_POST['elimInd'];
if(isset($idInd)){
	eliminarIndicador($idInd);
	echo "si";
}

// eliminar acudiente
@$idAcu=$_POST['elimAcu'];
if(isset($idAcu)){
	eliminarAcudiente($idAcu);
	echo "si";
}

// eliminar centro
@$idCen=$_POST['elimCen'];
if(isset($idCen)){
	eliminarCentroEducativo($idCen);
	echo "si";
}

// funciones


function eliminarUsuario($idUsu){
	 mysql_query("DELETE FROM usuario WHERE id='$idUsu'");
     eliminarUsuarioCentrosByUsuario($idUsu);
}

function eliminarMatricula($idMatr){
	
	$acudiante=0;
	
	$datos2=mysql_query("SELECT acudiente FROM matricula WHERE id='$idMatr'");
		 while($reg2=mysql_fetch_array($datos2)){
			 $acudiante=$reg2['acudiente'];
		 }
	eliminarAcudiente($acudiante);
	 mysql_query("DELETE FROM planilla WHERE matricula='$idMatr'");
	 mysql_query("DELETE FROM matricularecuperacion WHERE matricula='$idMatr'");
	 mysql_query("DELETE FROM matricula WHERE id='$idMatr'");
}

function eliminarProfesor($idPro){
	 mysql_query("DELETE FROM profesor WHERE id='$idPro'");
}

function eliminarArea($idAre){
	 mysql_query("DELETE FROM area WHERE id='$idAre'");
}

function eliminarMateria($idMat){
	mysql_query("DELETE FROM materia WHERE id='$idMat'");
}

function eliminarHorario($idHor){
	mysql_query("DELETE FROM horario WHERE id='$idHor'");
	
}

function eliminarGrupo($idGru){
	mysql_query("DELETE FROM grupo WHERE id='$idGru'");
}

function eliminarGrado($idGra){
	mysql_query("DELETE FROM grado WHERE id='$idGra'");
}

function eliminarIndicador($idInd){
	mysql_query("DELETE FROM indicadorlogro WHERE id='$idInd'");
}

function eliminarAcudiente($idAcu){
	mysql_query("DELETE FROM acudiente WHERE id='$idAcu'");
}

function eliminarCentroEducativo($idCen){
	mysql_query("DELETE FROM centroeducativo WHERE id='$idCen'");
	eliminarUsuarioCentrosByCentro($idCen);
}

function eliminarUsuarioCentrosByUsuario($usuario){
	mysql_query("DELETE FROM usuariocentros WHERE usuario='$usuario'");
}
function eliminarUsuarioCentrosByCentro($centro){
	mysql_query("DELETE FROM usuariocentros WHERE centro='$centro'");
}

?>