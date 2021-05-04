<?

$list_qry1="SELECT ta.* FROM tb_board_data ta where ta.idx>0 ";
$list_qry2=" ORDER BY ta.notice desc,ta.idx DESC";

$colum_pw="b_cd,corp,name,email,hp,f_flowkind,f_fluidkind,f_size,f_amount,f_type,title,contents,step,row,notice,hit,etc1,etc2,reg_dt,mod_dt,reg_ip";
$colum_pe="email,corp,name,hp,title,contents,notice,hit,mod_dt";
$colum_pf="b_cd";

$colum_fw="p_idx,b_cd,f_file,f_file_name,f_size,reg_dt";

##조회수업데이트 가능여부체크
$aryHitChkList = array("notice"=>true);
$aryCateChkList = array("selfsupport"=>true,"bcnews"=>true,"referenceroom"=>true,"jobfoundationinfo"=>true);
$aryUrlChkList = array("gallery"=>true);



$SysLib->dbCon();

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();

$cpage	=$params['page'];
$idx	=$params['idx'];
$b_cd	=$params['b_cd'];
if(!$params['notice']) $params['notice'] = '0';


$upDir = $WEBDIR['web_root'].$WEBDIR['fdata']."/".$b_cd;
$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;


if(!$cpage) $cpage=1;

$_SESSION['URL_QUERY']['PAGING'].= $params['WS'];
$_SESSION['URL_QUERY']['PAGING'].='&b_cd='.$b_cd;

$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

//게시판 이름

$board_name = "굿즈 이벤트";

switch ($params['act']) {
	case('pv'):
		
		if ($idx) {
			$row=$SysLib->Get_Content("select * from tb_board_data where idx='$idx'");
			$row['title']=stripslashes($row['title']);
			

		}

		$_SESSION['URL_QUERY']['PAGING'].='&page='.$cpage;		
	
		$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'V.html';		

	break;	
	case('pw'):	
		if(!is_dir($upDir)) mkdir($upDir,0777);
		
		//인서트
		$idx=$SysLib->Set_ContentP('tb_board_data',$colum_pw,$params,NULL);
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):
	
		$SysLib->Set_ContentP('tb_board_data',$colum_pe,$params,"idx='$idx'");		

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&act=pv&page=".$cpage."&idx=".$idx,1);	
		
	break;
	
	case('pd'):
		if (is_array($params['idx'])) $ar_data=$params['idx'];
		else $ar_data[0]=$params['idx'];
		
		foreach ($ar_data as $k=>$v) {
			$SysLib->Act_Del("tb_board_data","idx='$v'");
		}

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&page=".$cpage,1);
	break;
	
	default:
		$kfield=$params['kfield'];
		$kword=$params['kword'];
		$s_sdate=$params['sch_from'];
		$s_edate=$params['sch_to'];
		$sel_line=10;

		
		//검색조건
		$where_qry1 = " and b_cd='".$b_cd."' ";
		if (($s_sdate)&&($s_edate)) $where_qry1.=" and (date_format(reg_dt,'%Y%m%d') between '$s_sdate' and '$s_edate')";

		if ($kword) {
			if($kfield) {
				$where_qry1.=" and ".$kfield." like '%".$kword."%' ";
			} else {
				$where_qry1.=" and (title like '%".$kword."%' or contents like '%".$kword."%') ";			
			}
		}

		//URL data
		$SysLib->UrlQuery('s_sdate',$s_sdate);
		$SysLib->UrlQuery('s_edate',$s_edate);
		$SysLib->UrlQuery('kfield',$kfield);
		$SysLib->UrlQuery('kword',$kword);


		$rowList=$SysLib->Get_List_Page($list_qry1.$where_qry1,$list_qry2,$cpage,$sel_line);
		$PAGING=$SysLib->listPage($cpage,10,$sel_line);

		$pageArr=$SysLib->listPaging($cpage,10,$sel_line);


	break;
}

$SysLib->dbClose();
?>