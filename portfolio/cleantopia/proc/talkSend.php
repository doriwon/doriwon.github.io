<?session_start();?>
<meta charset="utf-8">
<?
	require $_SERVER['DOCUMENT_ROOT']."/Conf/config.php";
	require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
	require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

	$SysLib = new SysLib;
	$SysLib->dbCon();



	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	/*
	-url 정보
	개발 : http://121.133.161.109:8089/sms/eventSend.jsp
	운영 : http://company.cleantopia.kr/sms/eventSend.jsp
	-파라메터 정보
	cd_code : 가맹점 번호
	ds_name : 고객명
	ds_hanp : 고객 핸드폰 번호
	*/


	


	$ch = curl_init();


	$cd_code = "0943";
	$ds_name = "권호삼";
	$ds_hanp = "01090775303";


	$postparam = "cd_code={$cd_code}&ds_name={$ds_name}&ds_hanp={$ds_hanp}";
	curl_setopt($ch, CURLOPT_URL,"http://w.imakeit.co.kr/job/exam/curl/curlsave.php");
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postparam);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$server_output = curl_exec($ch);

	curl_close ($ch);

	$server_output = trim($server_output); 

	$sendLog = $postparam."///".$server_output;

	$SysLib->writeLog($sendLog);

	$SysLib->dbClose();

	$R = json_decode($server_output, true); 

	echo $R["CODE"];
	echo "<br/>";
	echo $R["MSG"];



?>
