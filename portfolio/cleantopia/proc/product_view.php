<?php
	$list_qry1="select ta.*, tb.name as cate1name, tc.name as cate2name ";
	$list_qry1.=" from tb_product ta left join tb_cate tb on ta.cate1= tb.idx left join tb_cate tc on ta.cate2=tc.idx where ta.idx>0 ";
	$list_qry2=" ORDER BY ta.idx DESC";
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/product";


	$SysLib = new SysLib;
	$SysLib->dbCon();

	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	
	$idx=$params['idx'];
	if(!$b_cd) $b_cd = "product";
	$cate1=$params['cate1'];
	$cate2=$params['cate2'];
	$kfield=$params['kfield'];
	$kword=$params['kword'];
	
	if($cate1) {
		$cate2Arr = $SysLib->Get_List_All("select idx, name from tb_cate where p_idx = $cate1 order by orby asc, idx asc");
		$cateName = $SysLib->Get_Content("select name from tb_cate where idx = $cate1 ", 1);
	}
	if(!$cate2) {
		$cate2 = $SysLib->Get_Content("select idx from tb_cate where p_idx = $cate1 order by orby asc, idx asc limit 0, 1", 1);
	}

	if($idx) {		
		$row=$SysLib->Get_Content($list_qry1." and  ta.idx='$idx'");

		$row['title']=stripslashes($row['title']);
		$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/product";
		/*
		if($row['attach_thum1']) {
			$row['attach_thum1s'] = $SysLib->Get_ThumName($fileDir."/".$row['attach_thum1']);	
			$row['attach_thum1'] = $fileDir."/".$row['attach_thum1'];
		}
		if($row['attach_thum2']) {
			$row['attach_thum2s'] = $SysLib->Get_ThumName($fileDir."/".$row['attach_thum2']);	
			$row['attach_thum2'] = $fileDir."/".$row['attach_thum2'];
		}
		if($row['attach_thum3']) {
			$row['attach_thum3s'] = $SysLib->Get_ThumName($fileDir."/".$row['attach_thum3']);	
			$row['attach_thum3'] = $fileDir."/".$row['attach_thum3'];
		}
		*/

		$fileList=$SysLib->Get_List_All("select * from tb_product_file where p_idx='$idx' order by fidx asc");
		for($i=0; $i<count($fileList); $i++) {
			$fileList[$i]['f_file_real'] = $fileDir."/".$fileList[$i]['f_file'];
			$fileList[$i]['f_file_thum'] = $SysLib->Get_ThumName($fileDir."/".$fileList[$i]['f_file']);
		}

	}

	$SysLib->dbClose();
?>