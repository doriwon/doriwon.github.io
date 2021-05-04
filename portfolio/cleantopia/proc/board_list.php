<?php

	$file_join=" select b_cd as fb_cd, p_idx, count(*) filecnt from tb_board_file group by b_cd, p_idx ";
	$file_join1=" select b_cd as fb_cd, p_idx, count(*) filecnt, min(fidx) as fidx, f_file from tb_board_file group by b_cd, p_idx ";
	$list_qry1="SELECT ta.*, ifnull(tb.filecnt,0) as filecnt, tb.f_file FROM tb_board_data ta left join ({$file_join1}) tb on ta.b_cd = tb.fb_cd and ta.idx = tb.p_idx where ta.idx>0 ";

	$list_qry2=" ORDER BY ta.notice desc,ta.reg_dt desc,ta.idx DESC";
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

	$colum_pw="b_cd,name,email,tel,title,contents,step,row,notice,hit,reg_dt,mod_dt,reg_ip";

	$SysLib = new SysLib;
	$SysLib->dbCon();

	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	$kfield=$params['kfield'];
	$kword=$params['kword'];
	$s_sdate=$params['sch_from'];
	$s_edate=$params['sch_to'];
	$cpage=$params['page'];
	if(!$cpage) $cpage=1;
	$sel_line=10;
	$sel_block=5; //블럭수

	//board set
	//$row=$SysLib->Get_Content("SELECT b_name,b_row FROM tb_board_set WHERE b_cd='".$b_cd."' ");
	//if ($row['b_row']>0) $sel_line=$row['b_row'];

	if ($b_cd =="bcnews") $sel_line=6;

	
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
	$pageArr=$SysLib->listPaging($cpage,$sel_block,$sel_line);
	$intListNum		= $pageArr['listcntnum'];					// 번호

	$SysLib->dbClose();
?>