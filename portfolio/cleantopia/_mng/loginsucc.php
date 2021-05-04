<?
require "../Conf/config.php";
session_start();

require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

$SysLib = new SysLib;
$SysLib->dbCon();

//================================ ALL - start =========================================================//
$GET['lg_ip']=GETENV("REMOTE_ADDR");
$Spass=$SysLib->Get_Content("SELECT * FROM tb_admin WHERE m_use=1 and m_id='".$SysLib->getParam('id')."'");

$onepwd=$SysLib->Get_Content("select password('".$SysLib->getParam('pwd')."') as pass ", 1);

if ($onepwd == $Spass[m_pwd]) { // 암호화 비번 일치하는지 비교. 
	$_SESSION['admin_id']=$Spass[m_id];
	$_SESSION['admin_name']=$Spass[m_name];
	$_SESSION['admin_no']=$Spass[idx];
	$_SESSION['admin_grd']=$Spass[m_grd];
	$_SESSION['admin_auth1']=$Spass[m_auth1];
	$_SESSION['admin_auth2']=$Spass[m_auth2];
	$SysLib->Set_Content('tb_admin','mod_dt','now()',"m_id='$Spass[m_id]'");
	$SysLib->pageNext('index.php?WS=130',1);
} else {
	$SysLib->altPopBack('아이디나 비밀번호가 틀렸습니다.');
}

//================================ ALL - end ===========================================================//

$SysLib->dbClose(); 
?>