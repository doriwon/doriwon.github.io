<?
	require $_SERVER['DOCUMENT_ROOT']."/Conf/conf.php";

	$list_qry1="SELECT ta.* FROM tb_board_data ta where ta.idx>0 ";
	$list_qry2=" ORDER BY ta.notice desc,ta.idx DESC";

	$colum_pw="b_cd,name,pwd,email,tel,title,contents,step,row,notice,hit,stat,etc1,etc2,reg_dt,mod_dt,reg_ip";
	$colum_pe="name,pwd,email,tel,title,contents,mod_dt";
	

	$SysLib = new SysLib;
	$SysLib->dbCon();

	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	if(!$params['b_cd']) $params['b_cd'] = "qna"; 

	$params['stat'] = "대기";
	$b_cd = $params['b_cd'];

	if($params['tel1'] && $params['tel1'] && $params['tel3']) {
		$params['tel'] = $params['tel1']."-".$params['tel2']."-".$params['tel3'];
	}

	if($params['email1'] && $params['email2']) {
		$params['email'] = $params['email1']."@".$params['email2'];
	}


	$upDir = $WEBDIR['web_root'].$WEBDIR['fdata']."/".$b_cd;
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

	if(!is_dir($upDir)) mkdir($upDir,0777);

	$resultArray = array();

	$resultArray['result'] = "fail";
	
	switch ($params['act']) {
		case('pw'):
				
			$chkAry = array('name' => '이름을 입력해주세요','pwd' => '비밀번호를 입력해주세요',	'tel' => '연락처를 입력해주세요','title' => '제목을 입력해주세요','contents' => '내용을 입력해주세요');
			

			$rtnChkAry = $SysLib->checkAry($params, $chkAry);
			$resultArray['rtnmsg'] = $rtnChkAry;

			if($rtnChkAry != ""){
				$resultArray['rtnmsg'] = $rtnChkAry;		
			} else {
				if($_FILES){
					foreach($_FILES as $key => $afile){
						$ar_imginfo=$SysLib->Set_File2($upDir,$afile,0,0);
						$params[$key]=$ar_imginfo['ofname'];
						$params[$key.'_name']=$ar_imginfo['fname'];

						if($ar_imginfo['fname']) {
							$colum_pw.=",".$key;
							$colum_pw.=",".$key."_name";
						}						
					}
				}

				$idx=$SysLib->Set_ContentP('tb_board_data',$colum_pw,$params,NULL);
				$resultArray['result'] = "success";
			}

			
			echo json_encode($resultArray);
			exit;		

			
		break;
		
		case('pd'):
			if($params['idx'] == "" || $params['pwd'] == "" ) {
				$resultArray['rtnmsg'] = $rtnChkAry;	
			}

			$fileRow=$SysLib->Get_Content("select * from tb_board_data where idx='".$params['idx']."' and pwd='".$params['pwd']."'");
			
			if($fileRow){
				if($fileRow['attach_file']){
					if (file_exists($upDir.'/'.$fileRow['attach_file'])) unlink($upDir.'/'.$fileRow['attach_file']);
				}

				$SysLib->Act_Del("tb_board_data","idx='".$params['idx']."'");
				$resultArray['result'] = "success";				
			} 
			echo json_encode($resultArray);
			exit;
			
		break;
		case('pe'):	
	
			$chkAry = array('name' => '이름을 입력해주세요','pwd' => '비밀번호를 입력해주세요',	'tel' => '연락처를 입력해주세요','title' => '제목을 입력해주세요','contents' => '내용을 입력해주세요');
		

			$rtnChkAry = $SysLib->checkAry($params, $chkAry);
			$resultArray['rtnmsg'] = $rtnChkAry;

			if($rtnChkAry != ""){
				$resultArray['rtnmsg'] = $rtnChkAry;		
			} else {
				if($_FILES){
					foreach($_FILES as $key => $afile){
						$ar_imginfo=$SysLib->Set_File2($upDir,$afile,0,0);
						$params[$key]=$ar_imginfo['ofname'];
						$params[$key.'_name']=$ar_imginfo['fname'];

						if($ar_imginfo['fname']) {
							$colum_pe.=",".$key;
							$colum_pe.=",".$key."_name";
						}						
					}
				}

				$SysLib->Set_ContentP('tb_board_data',$colum_pe,$params,"idx='".$params['idx']."'");
				$resultArray['result'] = "success";
			}

			echo json_encode($resultArray);
			exit;

		break;
		default:
			
		
		break;
	}
	
	$SysLib->dbClose();
?>