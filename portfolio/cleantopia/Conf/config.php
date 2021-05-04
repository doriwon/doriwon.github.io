<?php
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT); 
ini_set("display_errors", 1); 

//암호화키 설정
define("AES_KEY","mrromance");

##### Array Dir - start #####
$WEBDIR['web_default']='/';
$WEBDIR['web_root']=$_SERVER['DOCUMENT_ROOT'].$WEBDIR['web_default'];
$WEBDIR['adm_root']=$WEBDIR['web_root'].'/_mng/';
$WEBCNF['TITLE']='크린토피아 굿즈이벤트';	//싸이트 명칭
$WEBCNF['site']='800cleantopia.com';	//싸이트 URL
$WEBCNF['email']='sender@800cleantopia.com';	//메일주소(폼메일 보내는메일주소)
$WEBDIR['MKEY']='Wpage'; //메뉴키

$WEBDIR['conf']='Conf';
$WEBDIR['lib']='Lib';
$WEBDIR['fdata']='FileData';
##### Array Dir - end #####

##### Array File - start #####
$WEBFILE['TOP']='/top.html';
$WEBFILE['LEFT']='/left.html';
$WEBFILE['BOTTOM']='/bottom.html';
$WEBFILE['BODY']='/body.html';
$WEBFILE['MAIN']='/main.html';
##### Array File - end #####

##### Set Dir - start #####
if (!$_GET['WS']) {
	$WEBDIR['mdir']=$WEBDIR['MKEY'];
} else {
	$WEBDIR['mdir']=$WEBDIR['MKEY'].substr($_GET['WS'],0,1).'00';
}

$WEBDIR['mfile']=$WEBDIR['MKEY'].$_GET['WS'];
##### Set Dir - end #####


?>
