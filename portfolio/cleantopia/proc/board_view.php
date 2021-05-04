<?php
	$list_qry1="SELECT ta.* FROM tb_board_data ta where ta.idx>0 ";
	$list_qry2=" ORDER BY ta.idx DESC";
	$fileDir = $WEBDIR['web_default'].$WEBDIR['fdata']."/".$b_cd;

	$colum_pw="b_cd,name,pwd,email,tel,cate,office,title,contents,step,row,notice,hit,reg_dt,mod_dt,reg_ip";

	$SysLib = new SysLib;
	$SysLib->dbCon();

	//파라미터 모두 받기
	$params=$SysLib->getParameterToArray();

	$idx=$params['idx'];
	if(!$b_cd) $b_cd = $params['b_cd'];
	$kfield=$params['kfield'];
	$kword=$params['kword'];
	$s_sdate=$params['sch_from'];
	$s_edate=$params['sch_to'];
	$cpage=$params['page'];


	if(!$cpage) $cpage=1;
	$sel_line=10;

	if($idx) {		
		$row=$SysLib->Get_Content("select * from tb_board_data where idx='$idx'");
		$row['title']=stripslashes($row['title']);

		

		$strReadList = $_SESSION['hit'][$b_cd];
		$aryReadList = explode(",", $strReadList);
		if(!in_array($idx, $aryReadList)):

			

			$SysLib->dbQuery("update tb_board_data set hit=hit+1 where idx='$idx'");

			if($_SESSION['hit'][$b_cd]) { 
				$_SESSION['hit'][$b_cd] = $_SESSION['hit'][$b_cd].",".$idx; 
			} else {
				$_SESSION['hit'][$b_cd] = $idx; 
			}
		endif;

			
		$fileList=$SysLib->Get_List_All("select * from tb_board_file where b_cd='$b_cd' and p_idx='$idx' order by fidx asc");
		

		//다음글
		$n_row=$SysLib->Get_Content("select idx,title from tb_board_data where b_cd ='$b_cd' and idx>'$idx' ORDER BY notice desc, idx ASC limit 0,1");	
		if($n_row) {
			$n_row["title"] = stripslashes($n_row['title']);
		}
		//이전글
		$p_row=$SysLib->Get_Content("select idx,title from tb_board_data where b_cd ='$b_cd' and idx<'$idx' ORDER BY notice desc, idx DESC limit 0,1");
		if($p_row) {
			$p_row["title"] = stripslashes($p_row['title']);
		}

	}

	$SysLib->dbClose();
?>