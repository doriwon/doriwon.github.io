<?php
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/product";
	$SysLib = new SysLib;
	$SysLib->dbCon();

	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	$cate1=$params['cate1'];
	$cate2=$params['cate2'];
	
	//카테고리 가져오기
	if(!$cate1) $cate1 = $cate1Arr[0]['idx'];
	if($cate1) {
		$cate2Arr = $SysLib->Get_List_All("select idx, name from tb_cate where p_idx = $cate1 order by orby asc, idx asc");
		$cateName = $SysLib->Get_Content("select name from tb_cate where idx = $cate1 ", 1);
	}
	if(!$cate2) {
		$cate2 = $SysLib->Get_Content("select idx from tb_cate where p_idx = $cate1 order by orby asc, idx asc limit 0, 1", 1);
	}
	$SysLib->dbClose();
?>