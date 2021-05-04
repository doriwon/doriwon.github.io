<?php
session_start();
require "../Conf/config.php";
require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

$SysLib = new SysLib;

##### Call Module - start #####
if(is_file($WEBDIR['adm_root'].$WEBDIR['mdir'].'/'.$WEBDIR['mfile'].'.php')) require $WEBDIR['adm_root'].$WEBDIR['mdir'].'/'.$WEBDIR['mfile'].'.php';
##### Call Module - end #####

?>