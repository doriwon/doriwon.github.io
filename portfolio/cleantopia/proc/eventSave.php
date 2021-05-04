<?session_start();?>
<?
	require $_SERVER['DOCUMENT_ROOT']."/Conf/config.php";
	require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
	require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

	$colum_pw="b_cd,corp,name,email,hp,f_flowkind,f_fluidkind,f_size,f_amount,f_type,title,contents,stat,step,row,notice,hit,etc1,etc2,reg_dt,mod_dt,reg_ip";


	$SysLib = new SysLib;
	$SysLib->dbCon();


	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	if(!$params['b_cd']) $params['b_cd'] = "inquiry";
	$params['stat'] = "대기";
	$b_cd = $params['b_cd'];

	$returnUrl = "/customer/inquiry_write.php";
	if($b_cd == "flowmeter") {
		$returnUrl = "/flowmeter/flowmeter03.php";
	} 

	$upDir = $WEBDIR['web_root'].$WEBDIR['fdata']."/".$b_cd;
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

	if(!is_dir($upDir)) mkdir($upDir,0777);

	if($params['name'] == "" || $params['hp'] == "" ) {
		$SysLib->altPopBack("정상적인 접근이 아닙니다.",$returnUrl);
		return;
	}


	if($_FILES){
		foreach($_FILES as $key => $afile){
			if($key == "attach_file") {
				$ar_imginfo=$SysLib->Set_File2($upDir,$afile,0,0); //썸네일 생성안함					 
			} else {
				$ar_imginfo=$SysLib->Set_File2($upDir,$afile,$thumw,$thumh);					
			}
			$params[$key]=$ar_imginfo['ofname'];
			$params[$key.'_name']=$ar_imginfo['fname'];

			if($ar_imginfo['fname']) {
				$colum_pw.=",".$key;
				$colum_pw.=",".$key."_name";
			}
		}
	}

	$idx=$SysLib->Set_ContentP('tb_board_data',$colum_pw,$params,NULL);
	if($idx >= 1){
		$query ="select m_email from tb_admin where m_id='ki_admin'";
		$toMail=$SysLib->Get_Content($query, 1);							
		if($toMail){
			if ($b_cd =="flowmeter") {
				$Mtitle = '홈페이지[교정신청하기] 문의글이 등록되었습니다.';
				$Mcont  = " 신청인: ".$params['name'];
				$Mcont  .= "<br/>연락처 : ".$params['hp'];
				$Mcont  .= "<br/>유량계종류 : ".$params['f_flowkind'];
				$Mcont  .= "<br/>유체종류 : ".$params['f_fluidkind'];
				$Mcont  .= "<br/>구경 : ".$params['f_size'];
				$Mcont  .= "<br/>수량 : ".$params['f_amount'];
				$Mcont  .= "<br/>교정여부 : ".$params['f_type'];
			} else {
				$Mtitle = '홈페이지[제품문의] 문의글이 등록되었습니다.';
				$Mcont  = " 회사명: ".$params['corp'];
				$Mcont  .= "<br/>담당자 : ".$params['name'];
				$Mcont  .= "<br/>연락처 : ".$params['hp'];
				$Mcont  .= "<br/>이메일 : ".$params['email'];
				$Mcont  .= "<br/>문의제목 : ".$params['title'];
				$Mcont  .= "<br/><br/>".$params['contents'];
			}

			if ($_SERVER['REMOTE_ADDR'] =="211.168.226.176") { 
				$toMail = "kwon5353@nate.com";
			}
			$SysLib->Send_Mail($Mtitle,"관리자",$toMail,$Mcont);					
		}
	}

	$SysLib->altPopNext("정상등록되었습니다.",$returnUrl);
	
	$SysLib->dbClose();
?>