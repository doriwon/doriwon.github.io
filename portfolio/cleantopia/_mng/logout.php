<?
	//// Session Regist - start ////
	session_start();
	
	$sess=session_id();
	$sesstime=time();
	
	
	//// Session Regist - end ////
	$_SESSION['admin_id'] = '';
	$_SESSION['admin_name'] = '';
	$_SESSION['admin_no'] = '';
	$_SESSION['admin_grd'] = '';
	echo "<meta http-equiv='Refresh' content='0; URL=login.html'>";
?> 
