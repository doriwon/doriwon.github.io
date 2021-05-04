<?php
session_start();
require "../Conf/config.php";
require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

$SysLib = new SysLib;

##### Check Login - start #####
if (!$_SESSION['admin_id']) echo "<script>window.location.href='login.html';</script>";
##### Check Login - end #####

//$auth2List = array("volunteer"=>"자원봉사","support"=>"후원","visit"=>"기간방문");

##### Call Module - start #####
if(is_file($WEBDIR['adm_root'].$WEBDIR['mdir'].'/'.$WEBDIR['mfile'].'.php')) require $WEBDIR['adm_root'].$WEBDIR['mdir'].'/'.$WEBDIR['mfile'].'.php';
##### Call Module - end #####

##### Page Display - start #####
$frames=array();
$frames['TOP']='./'.$WEBDIR['MKEY'].$WEBFILE['TOP'];
$frames['LEFT']='./'.$WEBDIR['MKEY'].$WEBFILE['LEFT'];
$frames['BODY']='./'.$WEBDIR['mdir'].$WEBFILE['BODY'];		//Change case by case
$frames['BOTTOM']='./'.$WEBDIR['MKEY'].$WEBFILE['BOTTOM'];
$frames['MAIN']='./'.$WEBDIR['MKEY'].$WEBFILE['MAIN'];

require $frames['MAIN'];
##### Page Display - end #####

//쿼리 작업중 보기위한 세션값
//$_SESSION['view_query'] = "YES";



?>