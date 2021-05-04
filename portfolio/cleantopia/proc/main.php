<?
	//모바일사이트 접속여부
	$mobileurl = "pc";
	if (strpos($_SERVER["PHP_SELF"],"/mobile/") !== false) $mobileurl = "mobile";

	//모바일기계여부
	$mobileGubun = "pc";
	$mobileKeyWords = array('iPhone', 'iPod', 'BlackBerry', 'Android', 'Windows CE', 'LG', 'MOT', 'SAMSUNG', 'SonyEricsson');
	for($i = 0 ; $i < count($mobileKeyWords) ; $i++) {
		if(strpos($_SERVER['HTTP_USER_AGENT'],$mobileKeyWords[$i]) == true){
			$mobileGubun = "mobile";
		}
	}

	$SysLib = new SysLib;
	$SysLib->dbCon();

	if (!$_SESSION["VISIT"]) {
		$countSql = "select count(c_date) from tb_count where gu='".$mobileGubun."' and c_date = '".DATE("Ymd")."' and c_time = '".DATE("H")."'";
		$countVisit = $SysLib->Get_Content($countSql, 1); 	
		if ($countVisit == 0){
			$countSql =	" insert into tb_count( gu, c_date, c_time,  c_cnt )  values ( '".$mobileGubun."','".DATE("Ymd")."', '".DATE("H")."', 1 ) ";
		} else {
			$countSql =	" update tb_count  set c_cnt = c_cnt + 1 where gu='".$mobileGubun."' and c_date = '".DATE("Ymd")."' AND C_TIME = '".DATE("H")."' ";
		}

		$SysLib->dbQuery($countSql);
		$_SESSION["VISIT"] = "OK";
	}
	
	$file_join1=" select b_cd as fb_cd, p_idx, count(*) filecnt, min(fidx) as fidx, f_file from tb_product_file group by b_cd, p_idx ";
	$list_qry1=" SELECT ta.*, ifnull(tb.filecnt,0) as filecnt, tb.f_file FROM tb_product ta left join ({$file_join1}) tb on ta.idx = tb.p_idx where ta.idx>0 ";

	$pdtList1=$SysLib->Get_List_All($list_qry1. " and cate1=1 order by order_idx desc limit 4 ");
	$pdtList2=$SysLib->Get_List_All($list_qry1. " and cate1=2 order by order_idx desc limit 4 ");
	$pdtList3=$SysLib->Get_List_All($list_qry1. " and cate1=3 order by order_idx desc limit 4 ");
	$pdtList4=$SysLib->Get_List_All($list_qry1. " and cate1=4 order by order_idx desc limit 4 ");

	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/product";

	$popupList=$SysLib->Get_List_All("select * from tb_board_popup where b_cd='popup' and '".date("Ymd")."' between view_ymd_s and view_ymd_e order by idx desc ");

	for($i=0; $i<count($popupList); $i++) {
		if($popupList[$i]['attach_thum']) $popupList[$i]['attach_thum'] =$WEBDIR['web_default'].$WEBDIR['fdata']."/".$popupList[$i]['b_cd']."/".$popupList[$i]['attach_thum'];

		if(!$popupList[$i]['url']) $popupList[$i]['url'] = "";
	}
	
	$SysLib->dbClose();
?>