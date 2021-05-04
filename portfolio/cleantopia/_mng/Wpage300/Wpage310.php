<?

$list_qry1="SELECT ta.* FROM tb_board_cal ta where ta.idx>0 ";
$list_qry2=" ORDER BY ta.start_ymd Desc, ta.idx DESC";

$colum_pw="b_cd,m_id,m_name,view_yn,title,contents,start_ymd,end_ymd,time_hh,reg_dt,mod_dt,reg_ip";
$colum_pe="b_cd,view_yn,title,contents,start_ymd,end_ymd,time_hh,mod_dt";


$calKindList = array("person"=>"개인일정","center"=>"센터일정","car"=>"차량관리","room"=>"상담실");

$board_name = "일정";

$SysLib->dbCon();

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();
$params['m_id'] = $_SESSION['admin_id'];
$params['m_name'] = $_SESSION['admin_name'];
if(!$params['view_yn']) $params['view_yn'] = "n";
if(!$params['b_cd']) $params['b_cd'] = "person";

$b_cd = $params['b_cd'];
$cpage	=$params['page'];
$idx = $params['idx'];
$act = $params['act'];
$params['end_ymd'] = $params['start_ymd'];


$upDir = $WEBDIR['web_root'].$WEBDIR['fdata']."/".$b_cd;
$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

if(!$cpage) $cpage=1;

$_SESSION['URL_QUERY']['PAGING'].= $params['WS'];
$_SESSION['URL_QUERY']['PAGING'].='&b_cd='.$b_cd;

$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

switch ($params['act']) {
	case('cl'):
		//검색조건
		$where_qry1 = " idx >= 0 and b_cd='$b_cd' and view_yn='y' ";

		//if ($b_cd == "person") $where_qry1.=" and m_id ='{$params['m_id']}'";
		if ($params['start']) $where_qry1.=" and start_ymd >='{$params['start']}'";
		if ($params['end'])	  $where_qry1.=" and end_ymd <='{$params['end']}'";
		
		
		$rowList=$SysLib->Get_List_All("select * FROM tb_board_cal where {$where_qry1} ORDER BY idx");
		for($i=0; $i<count($rowList); $i++) {	
			
			$start = $rowList[$i]['start_ymd'];
			$end = $rowList[$i]['end_ymd'];
			$end = date("Y-m-d", strtotime($end."1day"));

			if ($b_cd == "person") {
				$rowList[$i]['title'] = "[".$rowList[$i]['m_name']."]".$rowList[$i]['title'];
			}
			
			$data[] = array(
			  'id'   => $rowList[$i]['idx'],
			  'title'   => $rowList[$i]['title'],
			  'start'   => $start,
			  'end'   => $end
			  
			 );


		}
		
		echo json_encode($data);
		exit;
	case('cv'):
		if ($idx) {
			$row=$SysLib->Get_Content("select * from tb_board_cal where idx='$idx'");
			$row['id']=$row['idx'];
			$row['b_cd']=$calKindList[$row['b_cd']];
			$row['contents']=nl2br($row['contents']);
		}
		echo json_encode($row);
		exit;
	break;

	case('pv'):
		$sch_Day=$params['sch_Day'];
		
		if ($idx) {
			$row=$SysLib->Get_Content("select * from tb_board_cal where idx='$idx'");
			$row['title']=stripslashes($row['title']);
			//파일
			if($row['attach_thum']) $row['attach_thum'] = $SysLib->Get_ThumName($fileDir."/".$row['attach_thum']); //썸네일 있으면썸으로 없으면 원본

			//포토리스트
			$fileList=$SysLib->Get_List_All("select * from tb_board_file where p_idx='$idx' order by fidx asc");

		}

		$_SESSION['URL_QUERY']['PAGING'].='&page='.$cpage.'&act=pl&sch_Day='.$sch_Day;		
		if($b_cd=="careers" || $b_cd=="request" || $b_cd=="qna") {
			$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'C.html';		
		} else {
			$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'V.html';		
		}
	break;	
	case('pw'):	
		
		//인서트
		$idx=$SysLib->Set_ContentP('tb_board_cal',$colum_pw,$params,NULL);
	
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):	
		
		$SysLib->Set_ContentP('tb_board_cal',$colum_pe,$params,"idx='$idx'");		

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&act=pv&page=".$cpage."&idx=".$idx,1);	
		
	break;

	case('pd'):
		if (is_array($params['idx'])) $ar_data=$params['idx'];
		else $ar_data[0]=$params['idx'];
		
		foreach ($ar_data as $k=>$v) {			
			$SysLib->Act_Del("tb_board_cal","idx='$v'");
		}

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&page=".$cpage,1);
	break;
	case('pl'):
		$kfield=$params['kfield'];
		$kword=$params['kword'];
		$s_sdate=$params['sch_from'];
		$s_edate=$params['sch_to'];
		$sch_Day=$params['sch_Day'];

		if ($sch_Day) {
			$s_sdate = str_replace("-","",$sch_Day);
			$s_edate = str_replace("-","",$sch_Day);
		}


		$sel_line=10;

		//검색조건
		$where_qry1 = " and b_cd='".$b_cd."' ";

		
		
		if($_SESSION['admin_grd'] =="1") {

		} else {
			$where_qry1.=" and (view_yn='y' or m_id ='{$params['m_id']}') ";
		}
	

		
		if (($s_sdate)&&($s_edate)) $where_qry1.=" and (REPLACE(start_ymd, '-', '') between '$s_sdate' and '$s_edate')";

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

		$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'L.html';	
	break;
	default:
		


	break;
}

$SysLib->dbClose();
?>