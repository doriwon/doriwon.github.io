<?


$ar_use = array("사용","정지","퇴사");

$list_qry1="SELECT * FROM tb_admin where 1";
$list_qry2=" ORDER BY idx DESC";
$colum_pw="m_id,m_pwd,m_name,m_phone,m_email,reg_dt,mod_dt,reg_ip";
$colum_pe="m_name,m_phone,m_email,mod_dt"; //필수업데이트

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
	
		
		$SysLib->Set_ContentED('tb_admin',$colum_pw,$params,NULL, 'm_phone');
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):
		$idx=$SysLib->getParam('idx');


		
		//암호화
		if($params['m_pwd']) {			
			$colum_pe.=",m_pwd";
		} 

		//m_pwd단방향, m_phone 양방향암호화
		$rtnCnt = $SysLib->Set_ContentED('tb_admin',$colum_pe,$params,"idx='".$idx."'", 'm_pwd','m_phone');
	
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	default:
		
		$row=$SysLib->Get_Content("SELECT *, AES_DECRYPT(UNHEX(m_phone), 'mrromance') as phone FROM tb_admin where m_id='{$_SESSION['admin_id']}' ");
		$useS=$row['m_use'];


		//연락처양방향(복호화)
		//$crypto = new Crypto();
		//$row['m_phone'] = $crypto->decrypt($row['m_phone']);
		$row['m_phone'] = $row['phone'];

		$useSO=$SysLib->Get_SelectA($ar_use,$useS,'사용여부선택',1);

		$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'V.html';
	break;
	
}


$SysLib->dbClose();
?>