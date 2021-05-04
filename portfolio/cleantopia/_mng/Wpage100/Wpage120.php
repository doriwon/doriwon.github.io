<?
$list_qry1="SELECT * FROM tb_board_set where 1";
$list_qry2=" ORDER BY idx DESC";
$colum_pw="b_cd,b_name,b_kind,b_row,b_state,b_noti,b_thum,b_file,b_files,reg_dt,mod_dt,reg_ip";
$colum_pe="b_cd,b_name,b_kind,b_row,b_state,b_noti,b_thum,b_file,b_files,mod_dt,reg_ip";

$SysLib->dbCon();

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();

if(!$params['b_state']) $params['b_state'] = "1";
if(!$params['b_noti']) $params['b_noti'] = "N";
if(!$params['b_thum']) $params['b_thum'] = "N";
if(!$params['b_file']) $params['b_file'] = "N";
if(!$params['b_files']) $params['b_files'] = "N";

$cpage	=$params['page'];
$idx	=$params['idx'];
if(!$cpage) $cpage=1;
$_SESSION['URL_QUERY']['PAGING'].=$SysLib->getParam('WS');

$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

// ALL - start //
switch ($params['act']) {	
	case('pw'):
		$SysLib->Set_ContentP('tb_board_set',$colum_pw,$params,NULL);
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):
		$SysLib->Set_ContentP('tb_board_set',$colum_pe,$params,"idx='".$idx."'");
		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pd'):
		if (is_array($params['idx'])) $ar_data=$params['idx'];
		else $ar_data[0]=$params['idx'];
		
		foreach ($ar_data as $k=>$v) {
			$SysLib->Act_Del("tb_board_set","idx='$v'");
		}

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&page=".$cpage,1);
	break;
	
	default:
		
		$sel_line=10;

		//검색조건
		$where_qry1 = "";
			
		$rowList=$SysLib->Get_List_Page($list_qry1.$where_qry1,$list_qry2,$cpage,$sel_line);
		$PAGING=$SysLib->listPage($cpage,5,$sel_line);

		$pageArr=$SysLib->listPaging($cpage,5,$sel_line);


	break;
}


// ALL - end //

$SysLib->dbClose();
?>