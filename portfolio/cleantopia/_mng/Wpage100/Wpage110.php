<?


$ar_use = array("사용","정지","퇴사");




$list_qry1="SELECT * FROM tb_admin ";
$list_qry2=" ORDER BY idx DESC";
$colum_pw="m_id,m_pwd,m_name,m_phone,m_email,m_grd,m_auth1,m_auth2,m_use,reg_dt,mod_dt,reg_ip";
$colum_pe="m_name,m_phone,m_email,m_grd,m_auth1,m_auth2,m_use,mod_dt"; //필수업데이트

$SysLib->dbCon();

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();

$cpage	=$params['page'];
$idx	=$params['idx'];

if(!$cpage) $cpage=1;
$_SESSION['URL_QUERY']['PAGING'].=$SysLib->getParam('WS');

$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

// ALL - start //
switch ($params['act']) {	
	case('pw'):
		$auth2 ="";
		foreach($params['auth1'] as $chkTemp) $auth1 .= "[".$chkTemp."]";
		$params['m_auth1']=$auth1;
		foreach($params['auth2'] as $chkTemp) $auth2 .= "[".$chkTemp."]";
		$params['m_auth2']=$auth2;

		$SysLib->Set_ContentED('tb_admin',$colum_pw,$params,NULL,'m_pwd','m_phone');
	
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):
		$idx=$SysLib->getParam('idx');
		
		//암호화
		if($params['m_pwd']) {			
			$colum_pe.=",m_pwd";
		} 
		$auth1 ="";
		$auth2 ="";
		if($params['auth1']) {
			foreach($params['auth1'] as $chkTemp) $auth1 .= "[".$chkTemp."]";
		}
		$params['m_auth1']=$auth1;
		if($params['auth2']) {
			foreach($params['auth2'] as $chkTemp) $auth2 .= "[".$chkTemp."]";
		}
		$params['m_auth2']=$auth2;


		$rtnCnt = $SysLib->Set_ContentED('tb_admin',$colum_pe,$params,"idx='".$idx."'", 'm_pwd','m_phone');
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pv'):

		if ($idx) {
			$row=$SysLib->Get_Content("SELECT *, AES_DECRYPT(UNHEX(m_phone), 'mrromance') as phone FROM tb_admin WHERE idx='".$idx."'");
			$useS=$row['m_use'];
			$row['m_phone'] = $row['phone'];
		}

		$bcdList1=$SysLib->Get_List_All("select b_cd, b_name from tb_board_set ");

		

		$useSO=$SysLib->Get_SelectA($ar_use,$useS,'사용여부선택',1);

		$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'V.html';
	break;
	case('pd'):
		if (is_array($params['idx'])) $ar_data=$params['idx'];
		else $ar_data[0]=$params['idx'];
		
		foreach ($ar_data as $k=>$v) {
			$SysLib->Act_Del("tb_admin","idx='$v'");
		}

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&page=".$cpage,1);
	break;
	
	default:		
		$sel_line=10;
		$rowList=$SysLib->Get_List_Page($list_qry1.$where_qry1,$list_qry2,$cpage,$sel_line);
		$PAGING=$SysLib->listPage($cpage,10,$sel_line);
		$pageArr=$SysLib->listPaging($cpage,10,$sel_line);


	break;
}


// ALL - end //

$SysLib->dbClose();
?>