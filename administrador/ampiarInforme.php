<?php
include("../procesos/token.php");

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<?php
@$TitInform=$_GET['tituloInf'];
if(isset($TitInform)){
	echo"<title>$TitInform</title>";
}
?>

<style>

#iframePrincipal{
	min-width:990px;
	width:100%;
	border:0px solid;
	overflow:hidden;	
}

</style>

<script>

var inicial=true;
function iniciarSize(){
	autofitIframe()
}

function autofitIframe(){
var IframeF=document.getElementById("iframePrincipal")
var tam
if (!window.opera && document.all && document.getElementById){
	tam=IframeF.contentWindow.document.body.scrollHeight;
    IframeF.style.height=tam;
} 
else if(document.getElementById) {
	tam=IframeF.contentDocument.body.scrollHeight
    IframeF.style.height=tam+"px";
}

if(tam<1000){
	setTimeout("autofitIframe()",500)
}

}

</script>

</head>

<body>

<?php
$_SESSION['itemsel']="liInf";
include("busquedas.php");
?>
<center>
<div id='todo' style="padding-left:250px; padding-top:10px;">
<?php
@$urlInform=$_GET['urlInforme'];
if(isset($urlInform)){
	echo "<iframe src='informes/$urlInform' id='iframePrincipal' onload='iniciarSize()'>
	</iframe>";
}
?>
 </div> 
</center>


</body>
</html>