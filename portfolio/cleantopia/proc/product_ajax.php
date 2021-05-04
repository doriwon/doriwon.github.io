<?php
	require "../Conf/config.php";
	require $WEBDIR['web_root'].$WEBDIR['conf']."/conf_db.php"; 
	require $WEBDIR['web_root'].$WEBDIR['lib'].'/syslib.class.php';

	$_SESSION['view_query'] = "";

	$file_join1=" select b_cd as fb_cd, p_idx, count(*) filecnt, min(fidx) as fidx, f_file from tb_product_file group by b_cd, p_idx ";
	$list_qry1=" SELECT ta.*, ifnull(tb.filecnt,0) as filecnt, tb.f_file FROM tb_product ta left join ({$file_join1}) tb on ta.idx = tb.p_idx where ta.idx>0 ";
	$list_qry2=" ORDER BY ta.order_idx DESC";

	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/product";

	$SysLib = new SysLib;
	$SysLib->dbCon();
	
	$params=$SysLib->getParameterToArray();
	
	$cpage	=$params['page'];
	$cate1	=$params['cate1'];
	$cate2	=$params['cate2'];
	$kfield =$params['kfield'];
	$kword =$params['kword'];
	if(!$cpage) $cpage=1;
	$sel_line=8;
		
	//검색조건
	$where_qry1 = "  ";
	if ($cate1) $where_qry1.=" and cate1 = '$cate1' ";
	if ($cate2) $where_qry1.=" and cate2 = '$cate2' ";


	

	if ($kword) {
		if($kfield) {
			if($kfield == "pname") {
				$where_qry1.=" and (pname like '%".$kword."%' or subname like '%".$kword."%' ) ";		
			} else {
				$where_qry1.=" and (subtext like '%".$kword."%' or contents like '%".$kword."%' ) ";		
			}
		} else {
			$where_qry1.=" and (pname like '%".$kword."%' or subname like '%".$kword."%' or subtext like '%".$kword."%' or contents like '%".$kword."%' ) ";			
		}
	}

	

	//$rowList=$SysLib->Get_List_Page($list_qry1.$where_qry1,$list_qry2,$cpage,$sel_line);
	$rowList=$SysLib->Get_List_All($list_qry1.$where_qry1.$list_qry2);

	//$replyTotal = $SysLib->total;
	//$PAGING=$SysLib->listPage($cpage,1,$sel_line);
	//$pageArr=$SysLib->listPaging($cpage,1,$sel_line);

	$SysLib->dbClose();

	

	for($i=0; $i<count($rowList); $i++) {
		if($rowList[$i]['f_file']) {
			//$rowList[$i]['f_file'] = $SysLib->Get_ThumName($fileDir."/".$rowList[$i]['f_file']); //썸네일 있으면썸으로 없으면 원
			$rowList[$i]['f_file'] = $fileDir."/".$rowList[$i]['f_file'];
		}
	}
	$resultArray = array();
	$resultArray['boardList'] = $rowList;
	$resultArray['nextPage'] = $pageArr['next'];
	echo json_encode($resultArray);
?>