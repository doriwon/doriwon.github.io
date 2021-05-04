<?

$list_qry1="SELECT ta.* FROM tb_board_popup ta where ta.idx>0 ";
$list_qry2=" ORDER BY ta.idx DESC";

$colum_pw="b_cd,title,view_ymd_s,view_ymd_e,p_top,p_left,url,target,viewlng,reg_dt,mod_dt,reg_ip";
$colum_pe="title,view_ymd_s,view_ymd_e,p_top,p_left,url,target,viewlng,mod_dt";

$SysLib->dbCon();

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();

if($params['b_cd']) $params['b_cd'] = "popup";
$cpage	=$params['page'];
$idx	=$params['idx'];
$b_cd	=$params['b_cd'];

$upDir = $WEBDIR['web_root'].$WEBDIR['fdata']."/".$b_cd;
$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

//팝업노출 언어checkbox
$viewlng = "";
if(isset($params['arr_viewlng'])){
    foreach($params['arr_viewlng'] as $item){
       $viewlng=$viewlng."[".$item."]";
    }
}
$params['viewlng'] = $viewlng;


if(!$cpage) $cpage=1;

$_SESSION['URL_QUERY']['PAGING'].= $params['WS'];
$_SESSION['URL_QUERY']['PAGING'].='&b_cd='.$b_cd;

$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

//게시판 이름
$board_name=$SysLib->Get_Content("SELECT b_name FROM tb_board_set WHERE b_cd='".$b_cd."' ", 1);


$thumw=420; 
$thumh=476;




switch ($params['act']) {
	case('pv'):
		
		if ($idx) {
			$row=$SysLib->Get_Content("select * from tb_board_popup where idx='$idx'");
			//파일
			if($row['attach_thum']) $row['attach_thum'] = $SysLib->Get_ThumName($fileDir."/".$row['attach_thum']); //썸네일 있으면썸으로 없으면 원본

			//포토리스트
			$fileList=$SysLib->Get_List_All("select * from tb_board_file where p_idx='$idx' order by fidx asc");

		}

		$_SESSION['URL_QUERY']['PAGING'].='&page='.$cpage;		
		$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'V.html';		
	break;	
	case('pw'):	
		if(!is_dir($upDir)) mkdir($upDir,0777);
		

		//인서트후 파일 업데이트(썸네일)및 인서트(여러파일경우)
		$ar_no=$params['fidx'];
		$fcnt = 0;

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

		$idx=$SysLib->Set_ContentP('tb_board_popup',$colum_pw,$params,NULL);

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING'],1);
	break;
	case('pe'):
	
		if(!is_dir($upDir)) mkdir($upDir,0777);
		
		$ar_no=$params['fidx'];
		$fcnt = 0;
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
					$colum_pe.=",".$key;
					$colum_pe.=",".$key."_name";
				}
			}
		}

		$SysLib->Set_ContentP('tb_board_popup',$colum_pe,$params,"idx='$idx'");

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&act=pv&page=".$cpage."&idx=".$idx,1);	
		
	break;

	case('pd'):
		if (is_array($params['idx'])) $ar_data=$params['idx'];
		else $ar_data[0]=$params['idx'];
		
		foreach ($ar_data as $k=>$v) {
			$fileRow=$SysLib->Get_Content("select * from tb_board_popup where idx='$v'");
		
			//원본파일과 썸네일 있음 썸네일도 삭제
			if($fileRow['attach_thum']){				
				if (file_exists($upDir.'/'.$fileRow['attach_thum'])) unlink($upDir.'/'.$fileRow['attach_thum']);
				if (file_exists($upDir.'/s_'.$fileRow['attach_thum'])) unlink($upDir.'/s_'.$fileRow['attach_thum']);
			}
			if($fileRow['attach_file']){
				if (file_exists($upDir.'/'.$fileRow['attach_file'])) unlink($upDir.'/'.$fileRow['attach_file']);
			}

			//포토리스트 삭제
			$IDATA=$SysLib->Get_List_All("select f_file from tb_board_file where p_idx='$v'");
			for($i=0; $i<count($IDATA); $i++) {
				if (file_exists($upDir.'/'.$IDATA[$i]['f_file'])) unlink($upDir.'/'.$IDATA[$i]['f_file']);
				if (file_exists($upDir.'/s_'.$IDATA[$i]['f_file'])) unlink($upDir.'/s_'.$IDATA[$i]['f_file']);
			}

			$SysLib->Act_Del("tb_board_file","p_idx='$v'");
	
			$SysLib->Act_Del("tb_board_popup","idx='$v'");
		}

		$SysLib->pageNext($_SESSION['URL_QUERY']['PAGING']."&page=".$cpage,1);
	break;
	
	default:
		$kfield=$params['kfield'];
		$kword=$params['kword'];
		$s_sdate=$params['sch_from'];
		$s_edate=$params['sch_to'];
		$sel_line=10;

		//board set
		$row=$SysLib->Get_Content("SELECT b_name,b_row FROM tb_board_set WHERE b_cd='".$b_cd."' ");
		if ($row['b_row']>0) $sel_line=$row['b_row'];
		$sel_line = 10;
		
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