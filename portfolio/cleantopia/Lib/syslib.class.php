<?
Class SysLib
{
	var $DBINFO=array();
	var $conn;
	var $view_que;
	var $total;
	var $ar_format;
	var $RDATAN;
	var $WEBDIR=array();
	var $WEBCNF=array();

	function SysLib()
	{
		global $DBINFO;
		global $WEBDIR;
		global $WEBCNF;
		$this->DBINFO = $DBINFO;
		$this->WEBDIR = $WEBDIR;
		$this->WEBCNF = $WEBCNF;
		$_SESSION['URL_QUERY']['PAGING']=basename($_SERVER['PHP_SELF'])."?WS=";
		$_SESSION['URL_QUERY']['ADDSQL']='';
		$_SESSION['URL_QUERY']['ADDORD']='';
		$this->ar_format=array('jpg','gif','JPG','GIF');
	}

	//======================= Add Function =============================

	function GetAddress($scode,$sk)
	{
		if (strpos($scode,"-")>-1) {
			$rtv=$sk;
		} else {
			$rtv=$this->Get_Content("select Aaddress from ds_Agent where Acode='$scode'",1);
		}

		return $rtv;
	}

	// 파리미터 받아 배열로 반환(GET,POST)
	function getParameterToArray() { 
		$params = array(); 	
		foreach($_GET as $key=>$val) { 
			$params[$key] = $val;		
		}
		
		foreach($_POST as $key=>$val) { 
			$params[$key] = $val;		
		}
		
		//기본params
		if(!$params['id'])	   $params['id']	 = $_SESSION['admin_id'];
		if(!$params['name'])   $params['name']	 =$_SESSION['admin_name'];
		if(!$params['reg_dt']) $params['reg_dt'] = date("Y-m-d H:i:s");
		if(!$params['mod_dt']) $params['mod_dt'] = date("Y-m-d H:i:s");
		if(!$params['reg_ip']) $params['reg_ip'] = $_SERVER['REMOTE_ADDR'];

		return $params;
	} 

	function GetAcode($address)
	{
		$sch_sql='';
		$ar_data=explode(" ",$address);
		$sch_sql="sido='".$ar_data[0]."'";
		if ($this->GetSubstr($ar_data[2])=='구') {
			$sch_sql.=" and '".$ar_data[1]." ".$ar_data[2]."'";
			$sch_sql.=" and dong='".$ar_data[3]."'";
			if ($this->GetSubstr($ar_data[3])=='동') {
				if (iconv_substr($ar_data[4],0,1,'UTF-8')=='산') {
					$ar_data[4]=iconv_substr($ar_data[4],1,($ar_data[4].length-1),'UTF-8');
					$sch_sql.=" and san='1'";
				}
				if (strpos($ar_data[4],"-")>-1) {
					$ar_data2=explode("-",$ar_data[4]);
					$sch_sql.=" and bungi='".$ar_data2[0]."' and hoo='".$ar_data2[1]."'";
				} else {
					$sch_sql.=" and bungi='".$ar_data[4]."'";
				}
			} else {
				$sch_sql.=" and lee='".$ar_data[4]."'";
				if (iconv_substr($ar_data[5],0,1,'UTF-8')=='산') {
					$ar_data[5]=iconv_substr($ar_data[5],1,($ar_data[5].length-1),'UTF-8');
					$sch_sql.=" and san='1'";
				}
				if (strpos($ar_data[5],"-")>-1) {
					$ar_data2=explode("-",$ar_data[5]);
					$sch_sql.=" and bungi='".$ar_data2[0]."' and hoo='".$ar_data2[1]."'";
				} else {
					$sch_sql.=" and bungi='".$ar_data[5]."'";
				}
			}
		} else {
			$sch_sql.=" and gugun='".$ar_data[1]."'";
			$sch_sql.=" and dong='".$ar_data[2]."'";
			if ($this->GetSubstr($ar_data[2])=='동') {
				if (iconv_substr($ar_data[3],0,1,'UTF-8')=='산') {
					$ar_data[3]=iconv_substr($ar_data[3],1,($ar_data[3].length-1),'UTF-8');
					$sch_sql.=" and san='1'";
				}
				if (strpos($ar_data[3],"-")>-1) {
					$ar_data2=explode("-",$ar_data[3]);
					$sch_sql.=" and bungi='".$ar_data2[0]."' and hoo='".$ar_data2[1]."'";
				} else {
					$sch_sql.=" and bungi='".$ar_data[3]."'";
				}
			} else {
				$sch_sql.=" and lee='".$ar_data[3]."'";
				if (iconv_substr($ar_data[4],0,1,'UTF-8')=='산') {
					$ar_data[4]=iconv_substr($ar_data[4],1,($ar_data[4].length-1),'UTF-8');
					$sch_sql.=" and san='1'";
				}
				if (strpos($ar_data[4],"-")>-1) {
					$ar_data2=explode("-",$ar_data[4]);
					$sch_sql.=" and bungi='".$ar_data2[0]."' and hoo='".$ar_data2[1]."'";
				} else {
					$sch_sql.=" and bungi='".$ar_data[4]."'";
				}
			}
		}

		//echo $sch_sql;
		$RDATA=$this->Get_Content("select * from ds_Address3 where $sch_sql");

		return $RDATA;
	}

	function GetSubstr($str) {

		$str=iconv_substr($str,-1,1,'UTF-8');

		return $str;
	}

	//======================= Add Function =============================

	//======================= Content Function =============================
	/*
	function SchQuery($qry,$vname,$val1,$val2,$dlv='~')
	{
		if ($val1) {
			if ($val2) {
				$ar_vname=explode(',',$vname);
				if ($qry=='%%') {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and (".$val1." like '%".$val2."%')";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[0]."=".$val1."&".$ar_vname[1]."=".$val2;
				} else if ($qry=='and') {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and ".$ar_vname[0]."='".$val1."' and ".$ar_vname[1]."='".$val2."'";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[2]."=".$val1.$dlv.$val2;
				} else {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and (".$val1." like '".$val2.$qry."')";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[0]."=".$val1."&".$ar_vname[1]."=".$val2;
				}
			} else {
				$ar_vname=explode(',',$vname);
				if (count($ar_vname)==1) {
					if ($qry) $_SESSION['URL_QUERY']['ADDSQL'].=$qry."'".$val1."'";
					$_SESSION['URL_QUERY']['PAGING'].="&".$vname."=".$val1;
				}
			}
		}
	}
	*/

	function SchQuery($qry,$vname,$val1,$val2,$dlv='~')
	{
		if ($val1) {
			if ($val2) {
				$ar_vname=explode(',',$vname);
				if ($qry=='%%') {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and (".$val1." like '%".$val2."%')";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[0]."=".$val1."&".$ar_vname[1]."=".$val2;
				} else if ($qry=='and') {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and ".$ar_vname[0]."='".$val1."' and ".$ar_vname[1]."='".$val2."'";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[2]."=".$val1.$dlv.$val2;
				} else {
					$_SESSION['URL_QUERY']['ADDSQL'].=" and (".$val1." like '".$val2.$qry."')";
					$_SESSION['URL_QUERY']['PAGING'].="&".$ar_vname[0]."=".$val1."&".$ar_vname[1]."=".$val2;
				}
			} else {
				$ar_vname=explode(',',$vname);
				if (count($ar_vname)==1) {
					if ($qry) $_SESSION['URL_QUERY']['ADDSQL'].=$qry."'".$val1."'";
					$_SESSION['URL_QUERY']['PAGING'].="&".$vname."=".$val1;
				}
			}
		}
	}

	function UrlQuery($val1,$val2)
	{
		if($val2) $_SESSION['URL_QUERY']['PAGING'].="&".$val1."=".$val2;
	}

	//Simple select list
	function Get_List_Page($Squery,$Oquery,$cpage,$listmax)
	{
		if ($_SESSION['URL_QUERY']['ADDSQL']!='') $Squery.=$_SESSION['URL_QUERY']['ADDSQL'];

		$Squery.=$Oquery;

		$rlt=$this->dbQuery($Squery);
		$this->total=mysqli_num_rows($rlt);
		
		if ( $cpage>0 ) {			
			$lists=($cpage-1)*$listmax;
			$no=$this->total-($listmax*($cpage-1));
		} else {
			$no=$this->total;
			$lists=0;
			$listmax=$this->total;
		}

		if ( $cpage>0 ) $Squery.=" limit $lists,$listmax";
		//else  $Squery.=$Oquery;
		//echo $Squery;
		$rlt=$this->dbQuery($Squery);

		while($dat=mysqli_fetch_array($rlt))
		{
			$row=$dat;
			$row['no']=$no;
			$row['title']=stripslashes($row['title']);
			$row['contents']=stripslashes($row['contents']);
			$ret_row[]=$row;
			$no--;
		}

		return $ret_row;
	}

	function Get_List_All($Squery)
	{
		$rlt=$this->dbQuery($Squery);
		$this->total=mysqli_num_rows($rlt);
		$no=$this->total;

		$rlt=$this->dbQuery($Squery);
		
		while($dat=mysqli_fetch_array($rlt))
		{
			$row=$dat;
			$row['no']=$no;
			$ret_row[]=$row;
			$no--;
		}

		return $ret_row;
	}

	function Get_Content($Squery,$rtc=0)
	{
		$nrlt = $this->dbQuery($Squery);
		//$data=mysqli_fetch_array($nrlt);
		$data=$nrlt->fetch_array();

		if($data) {
			$data['title']=stripslashes($data['title']);
			$data['contents']=stripslashes($data['contents']);  
		}

		if ($rtc==1) return $data[0];
		else return $data;
	}

	//Simple set content
	function Set_Content($table,$colum,$Nval,$Skey,$enabled=null)
	{
		if ($Skey) $Squery="UPDATE ".$table." SET ";
		else $Squery="INSERT INTO ".$table." SET ";

		$ar_column=explode(',',$colum);
		$ar_Nval=explode(',',$Nval);

		

		if (count($ar_column)==count($ar_Nval)) { 
			for ($i=0;$i<count($ar_column);$i++) {
				if ($ar_Nval[$i]=='now()') {
					if ($ad_sql) $ad_sql.=",";
					$ad_sql.=$ar_column[$i]."=now()";
				} else if (substr($ar_Nval[$i],-2)=='+1' || $enabled) {
					if ($ad_sql) $ad_sql.=",";
					if ($enabled) $ad_sql.=$ar_column[$i]."='".$ar_Nval[$i]."'";
					else $ad_sql.=$ar_column[$i]."=".$ar_Nval[$i];
				} else {
					if (($this->getParam($ar_Nval[$i]))||$this->getParam($ar_Nval[$i])!='') {
						$istr=str_replace(',', '', $this->getParam($ar_Nval[$i]));
						if (!is_numeric($istr)) {
							$istr=$this->getParam($ar_Nval[$i]);
						}
						if ($ad_sql) $ad_sql.=",";
						if ($istr=='snull') $ad_sql.=$ar_column[$i]."=NULL";
						else $ad_sql.=$ar_column[$i]."='".$istr."'";
					}
				}
			}
		} else {
			echo "No match column and value count!";
			exit;
		}

		if ($Skey) $Squery.=$ad_sql." WHERE ".$Skey;
		else $Squery.=$ad_sql;
			//echo $Squery; die();
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//Simple set json content
	function Set_JSContent($table,$colum,$Nval,$Skey,$enabled=null)
	{
		$Sdata = json_decode($this->getParam('data'),true);

		if ($Skey) $Squery="UPDATE ".$table." SET ";
		else $Squery="INSERT INTO ".$table." SET ";

		$ar_column=explode(',',$colum);
		$ar_Nval=explode(',',$Nval);

		if (count($ar_column)==count($ar_Nval)) { 
			for ($i=0;$i<count($ar_column);$i++) {
				if ($ar_Nval[$i]=='now()') {
					if ($ad_sql) $ad_sql.=",";
					$ad_sql.=$ar_column[$i]."=now()";
				} else if (substr($ar_Nval[$i],-2)=='+1' || $enabled) {
					if ($ad_sql) $ad_sql.=",";
					if ($enabled) $ad_sql.=$ar_column[$i]."='".$ar_Nval[$i]."'";
					else $ad_sql.=$ar_column[$i]."=".$ar_Nval[$i];
				} else {
					if (($Sdata[$ar_Nval[$i]])||$Sdata[$ar_Nval[$i]]!='') {
						if ($ad_sql) $ad_sql.=",";
						if ($Sdata[$ar_Nval[$i]]=='snull') $ad_sql.=$ar_column[$i]."=NULL";
						else $ad_sql.=$ar_column[$i]."='".$Sdata[$ar_Nval[$i]]."'";
					}
				}
			}
		} else {
			echo "No match column and value count!";
			exit;
		}

		if ($Skey) $Squery.=$ad_sql." WHERE ".$Skey;
		else $Squery.=$ad_sql;
			//echo $Squery; die();
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//Simple set content all value
	function Set_ContentA($table,$colum,$Nval,$Skey,$enabled=null)
	{
		if ($Skey) $Squery="UPDATE ".$table." SET ";
		else $Squery="INSERT INTO ".$table." SET ";

		$ar_column=explode(',',$colum);
		$ar_Nval=explode(',',$Nval);

		if (count($ar_column)==count($ar_Nval)) { 
			for ($i=0;$i<count($ar_column);$i++) {
				
				if ($ar_Nval[$i]=='now()') {
					if ($ad_sql) $ad_sql.=",";
					$ad_sql.=$ar_column[$i]."=now()";
				} else if (substr($ar_Nval[$i],-2)=='+1' || $enabled) {
					if ($ad_sql) $ad_sql.=",";
					if ($enabled) $ad_sql.=$ar_column[$i]."='".$ar_Nval[$i]."'";
					else		  $ad_sql.=$ar_column[$i]."=".$ar_Nval[$i];
				} else {
					$istr=str_replace(',', '', $this->getParam(trim($ar_Nval[$i])));
					if (!is_numeric($istr)) {
						$istr=addslashes($this->getParam(trim($ar_Nval[$i])));
					}
					if ($ad_sql) $ad_sql.=",";
					if ($istr=='snull') $ad_sql.=$ar_column[$i]."=NULL";
					else $ad_sql.=$ar_column[$i]."='".$istr."'";
				}
			}
		} else {
			echo "No match column and value count.";
			exit;
		}

		if ($Skey) $Squery.=$ad_sql." WHERE ".$Skey;
		else $Squery.=$ad_sql;
		//echo $Squery; exit;
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//Simple set content params value
	function Set_ContentP($table,$colum,$params,$Skey)
	{
		$ar_column=explode(',',$colum);
		for ($i=0;$i<count($ar_column);$i++) {
			if ($ad_sql) $ad_sql.=",";

			$istr= $params[$ar_column[$i]];
			if (!is_numeric($istr)) {
				if($ar_column[$i] != "contents" ) $istr=addslashes($params[$ar_column[$i]]);
				if($ar_column[$i] != "subtext" ) $istr=addslashes($params[$ar_column[$i]]);
				//$istr=addslashes($params[$ar_column[$i]]);
				$istr=$this->removeHackTag($istr);

			} 
			
			if ($istr=='snull') $ad_sql.=$ar_column[$i]."=NULL";
			else $ad_sql.=$ar_column[$i]."='".$istr."'";
		}
		
		if ($Skey) {
			$Squery="UPDATE ".$table." SET ".$ad_sql." WHERE ".$Skey;
		} else {
			$Squery="INSERT INTO ".$table." SET ".$ad_sql;
		}

		//echo $Squery; exit;
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//$onepwd :단뱡향 암호화할 컬럼명 지정, $twopwd : 양방향
	function Set_ContentED($table,$colum,$params,$Skey, $onepwd, $twopwd)
	{
		$ar_column=explode(',',$colum);
		for ($i=0;$i<count($ar_column);$i++) {
			if ($ad_sql) $ad_sql.=",";

			$istr= $params[$ar_column[$i]];
			if (!is_numeric($istr)) $istr=addslashes($params[$ar_column[$i]]);
			
		
			if ($istr=='snull') {
				$ad_sql.=$ar_column[$i]."=NULL";
			} else {
				if($ar_column[$i] == $onepwd) {
					$ad_sql.=$ar_column[$i]."= password('".$istr."') ";

				} else if($ar_column[$i] == $twopwd) {
					$ad_sql.=$ar_column[$i]."= HEX(AES_ENCRYPT('".$istr."','".AES_KEY."')) ";
				} else {
					$ad_sql.=$ar_column[$i]."='".$istr."'";
				}
			}		
		}
		
		if ($Skey) {
			$Squery="UPDATE ".$table." SET ".$ad_sql." WHERE ".$Skey;
		} else {
			$Squery="INSERT INTO ".$table." SET ".$ad_sql;
		}
		
		//echo $Squery; exit;
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	function Set_ContentParam($table,$param,$Skey)
	{
		if ($Skey) $Squery="UPDATE ".$table." SET ";
		else $Squery="INSERT INTO ".$table." SET ";

		$ar_column=explode(',',$colum);
		$ar_Nval=explode(',',$Nval);

		if (count($ar_column)==count($ar_Nval)) { 
			for ($i=0;$i<count($ar_column);$i++) {
				if ($ar_Nval[$i]=='now()') {
					if ($ad_sql) $ad_sql.=",";
					$ad_sql.=$ar_column[$i]."=now()";
				} else if (substr($ar_Nval[$i],-2)=='+1' || $enabled) {
					if ($ad_sql) $ad_sql.=",";
					if ($enabled) $ad_sql.=$ar_column[$i]."='".$ar_Nval[$i]."'";
					else $ad_sql.=$ar_column[$i]."=".$ar_Nval[$i];
				} else {
					$istr=str_replace(',', '', $this->getParam($ar_Nval[$i]));
					if (!is_numeric($istr)) {
						$istr=$this->getParam($ar_Nval[$i]);
					}
					if ($ad_sql) $ad_sql.=",";
					if ($istr=='snull') $ad_sql.=$ar_column[$i]."=NULL";
					else $ad_sql.=$ar_column[$i]."='".$istr."'";
				}
			}
		} else {
			echo "No match column and value count!!";
			exit;
		}

		if ($Skey) $Squery.=$ad_sql." WHERE ".$Skey;
		else $Squery.=$ad_sql;
		//echo $Squery; exit;
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//Simple set json content all value
	function Set_JSContentA($table,$colum,$Nval,$Skey,$enabled=null)
	{
		$Sdata = json_decode($this->getParam('data'),true);

		if ($Skey) $Squery="UPDATE ".$table." SET ";
		else $Squery="INSERT INTO ".$table." SET ";

		$ar_column=explode(',',$colum);
		$ar_Nval=explode(',',$Nval);

		if (count($ar_column)==count($ar_Nval)) { 
			for ($i=0;$i<count($ar_column);$i++) {
				if ($ar_Nval[$i]=='now()') {
					if ($ad_sql) $ad_sql.=",";
					$ad_sql.=$ar_column[$i]."=now()";
				} else if (substr($ar_Nval[$i],-2)=='+1' || $enabled) {
					if ($ad_sql) $ad_sql.=",";
					if ($enabled) $ad_sql.=$ar_column[$i]."='".$ar_Nval[$i]."'";
					else $ad_sql.=$ar_column[$i]."=".$ar_Nval[$i];
				} else {
					if ($ad_sql) $ad_sql.=",";
					if ($Sdata[$ar_Nval[$i]]=='snull') $ad_sql.=$ar_column[$i]."=NULL";
					else $ad_sql.=$ar_column[$i]."='".$Sdata[$ar_Nval[$i]]."'";
				}
			}
		} else {
			echo "No match column and value count!";
			exit;
		}

		if ($Skey) $Squery.=$ad_sql." WHERE ".$Skey;
		else $Squery.=$ad_sql;
		//echo $Squery; exit;
		if ($ad_sql) $this->dbQuery($Squery);
		if ($Skey) return 1;
		else return mysqli_insert_id($this->conn);
	}

	//Simple update content
	function Chg_Content($table,$colum,$value,$Skey)
	{
		if ($Skey) {
			$Squery="UPDATE ".$table." SET ".$colum."=".$value." WHERE ".$Skey;
			$this->dbQuery($Squery);
		}
	}

	//Simple del list
	function Act_Del($table,$Skey)
	{
		if ($Skey) {
			$Squery="DELETE FROM ".$table." WHERE ".$Skey;
			$this->dbQuery($Squery);
		}
	}

	//Simple select array
	function Get_ArrayQ($Squery)
	{
		$nrlt = $this->dbQuery($Squery);

		while( $dat = mysqli_fetch_array($nrlt) )
		{
			$row_goods[$dat[0]]=$dat[1];
		}

		return $row_goods;
	}

	function Get_ArrayCQ($Squery)
	{
		$nrlt = $this->dbQuery($Squery);

		while( $dat = mysqli_fetch_array($nrlt) )
		{
			if ($retStr) $retStr.=",".$dat[0];
			else $retStr=$dat[0];
		}

		return $retStr;
	}

	function Get_ArrayA($Darray,$val1,$val2)
	{
		for ($i=0;$i<count($Darray);$i++)
		{
			$row_goods[$Darray[$i][$val1]]=$Darray[$i][$val2];
		}

		return $row_goods;
	}

	//Simple get select tag
	function Get_SelectQ($Squery,$selval,$default)
	{
		$tc=0;
		$nrlt = $this->dbQuery($Squery);

		if ($default) $ret_select='<option value="">'.$default.'</option>';
		while( $dat = mysqli_fetch_array($nrlt) )
		{
			if (($dat[0])&&($dat[0]==$selval)) $check1="selected";
			else $check1="";
			$ret_select.='<option value="'.$dat[0].'" '.$check1.'>'.$dat[1].'</option>';
			$tc=1;
		}

		if ($tc>0) return $ret_select;
		else return '';
	}

	function Get_SelectA($Darray,$selval,$default,$vk=0)
	{
		if ($default) $ret_select='<option value="">'.$default.'</option>';
		for ($i=0;$i<count($Darray);$i++)
		{
			
			if ($vk==0) {
				if (($Darray[$i])&&($Darray[$i]==$selval)) $check1="selected";
				else $check1="";
				$ret_select.='<option value="'.$Darray[$i].'" '.$check1.'>'.$Darray[$i].'</option>';
			} else {
				if (($i+1)==$selval) $check1="selected";
				else $check1="";
				$ret_select.='<option value="'.($i+1).'" '.$check1.'>'.$Darray[$i].'</option>';
			}
		}

		return $ret_select;
	}

	//Simple get check tag
	function Get_CheckQ($Squery,$ar_chkval,$valkey,$default)
	{
		$tc=0;
		$nrlt = $this->dbQuery($Squery);

		while( $dat = mysqli_fetch_array($nrlt) )
		{
			if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="checked";
			else $check1="";
			$ret_check.='<label><input type="checkbox" name="'.$valkey.'" value="'.$dat[0].'" '.$check1.'/> '.$dat[1].'</label>'.$default;
			$tc=1;
		}

		if ($tc>0) return $ret_check;
		else return '';
	}

	function Get_CheckA($Darray,$ar_chkval,$valkey,$default,$vk=0)
	{
		for ($i=0;$i<count($Darray);$i++)
		{
			if ($vk==0) {
				if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="selected";
				else $check1="";
				$ret_check.='<label><input type="checkbox" name="'.$valkey.'" value="'.$Darray[$i].'" '.$check1.'/> '.$Darray[$i].'</label>'.$default;
			} else {
				if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="selected";
				else $check1="";
				$ret_check.='<label><input type="checkbox" name="'.$valkey.'" value="'.($i+1).'" '.$check1.'/> '.$Darray[$i].'</label>'.$default;
			}
		}

		return $ret_check;
	}

	//Simple get radio tag
	function Get_RadioQ($Squery,$ar_chkval,$valkey,$default)
	{
		$tc=0;
		$nrlt = $this->dbQuery($Squery);

		while( $dat = mysqli_fetch_array($nrlt) )
		{
			if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="checked";
			else $check1="";
			$ret_check.='<label><input type="radio" name="'.$valkey.'" value="'.$dat[0].'" '.$check1.'/> '.$dat[1].'</label>'.$default;
			$tc=1;
		}

		if ($tc>0) return $ret_check;
		else return '';
	}

	function Get_RadioA($Darray,$ar_chkval,$valkey,$default,$vk=0)
	{
		for ($i=0;$i<count($Darray);$i++)
		{
			if ($vk==0) {
				if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="selected";
				else $check1="";
				$ret_check.='<label><input type="radio" name="'.$valkey.'" value="'.$Darray[$i].'" '.$check1.'/> '.$Darray[$i].'</label>'.$default;
			} else {
				if ($this->Chk_inVal($ar_chkval,$dat[0])>0) $check1="selected";
				else $check1="";
				$ret_check.='<label><input type="radio" name="'.$valkey.'" value="'.($i+1).'" '.$check1.'/> '.$Darray[$i].'</label>'.$default;
			}
		}

		return $ret_check;
	}

	function Chk_inVal($ar_data,$chval)
	{
		$ck=0;

		for($i=0;$i<count($ar_data);$i++) {
			if ($ar_data[$i]==$chval) $ck=1;
		}

		return $ck;
	}

	//======================= Content Function =============================

	//======================= Function for file =============================
	function GetExcel($dir,$name,$EDATA,$head,$column)
	{
		$ar_col=explode(',',$column);
		$list='<table border="1">'.$head;

		for ($i=0;$i<count($EDATA);$i++) {
			$list.='<tr>';
			foreach($ar_col as $k => $v)
			{
				if ($v=='NULL') $list.='<td>&nbsp;</td>';
				else $list.='<td>'.$EDATA[$i][$v].'</td>';
			}
			$list.='</tr>';
		}

		$list.='</table>';
		$this->writeFile($dir.$name,$list);
		$this->downEXfile($dir.$name,$name);
		unlink($dir.$name);
		exit;
	}

	function writeFile($file,$data)
	{
		$data=iconv('utf-8','euc-kr',$data);
		if($fp=fopen($file,'w'))
		{
			flock($fp,2);
			$data=stripslashes($data);
			fwrite($fp,$data);
			flock($fp,3);
			fclose($fp);
			@chmod($file,0777);
		}
		else
		{
			echo 'Error: '.$file;
			return false;
		}
	}

	function downEXfile($dir,$name)
	{
		$name=iconv('utf-8','euc-kr',$name);

		$file=getImagesize($dir);
		$size=filesize($dir);
		header('Content-type: file/'.$file['mime']);
		header('Content-Length: '.$size);
		header('Content-Disposition: attachment; filename='.$name);
		header('Pragma: no-cache');
		header('Expires: 0');
		if(is_file($dir))
		{
			$fp=fopen($dir,'r'); 
			if(!fpassthru($fp))
			{
				fclose($fp);
			}
		}
	}

	function Del_File($fdir,$fname)
	{
		if (file_exists($fdir."/".$fname)) unlink($fdir."/".$fname);
	}

	function Set_File($dir_afile,$Fval,$maxsizeW,$maxsizeH)
	{
		$afile=$_FILES[$Fval];
		if ( is_uploaded_file($afile['tmp_name']) ) {
			$ar_imginfo['fname'] = $afile['name'];
			$ar_imginfo['fsize'] = $afile['size'];								
			$ar_imginfo['format'] = substr($ar_imginfo['fname'],-3);
			$name="f".substr(md5(uniqid(time())),0,8);
			$name2=$name;
			while(file_exists($dir_afile."/".$name2.".".$ar_imginfo['format'])) {
				$name2 = $name."(".$i.")";
				$i++;
		    }

			$ar_imginfo['ofname']=$name2.".".$ar_imginfo['format'];
			$this->uploadFile($dir_afile,$afile,$ar_imginfo['ofname']);

			if (in_array($ar_imginfo['format'],$this->ar_format) && $maxsizeW>0) {
				$this->resizeImage($maxsizeW,$maxsizeH,$dir_afile.'/s_'.$ar_imginfo['ofname'],$dir_afile.'/'.$ar_imginfo['ofname']);
				$ar_imginfo['sfname']='s_'.$ar_imginfo['ofname'];
			}
		}

		return $ar_imginfo;
	}
	
	
	function Set_File2($dir_afile,$afile,$maxsizeW,$maxsizeH)
	{
		
			$ar_imginfo['fname'] = $afile['name'];
			$ar_imginfo['fsize'] = $afile['size'];								
			$ar_imginfo['format'] = substr($ar_imginfo['fname'],-3);
			$name="f".substr(md5(uniqid(time())),0,8);
			$name2=$name;
			while(file_exists($dir_afile."/".$name2.".".$ar_imginfo['format'])) {
				$name2 = $name."(".$i.")";
				$i++;
		    }

			$ar_imginfo['ofname']=$name2.".".$ar_imginfo['format'];
			$this->uploadFile($dir_afile,$afile,$ar_imginfo['ofname']);

			if (in_array($ar_imginfo['format'],$this->ar_format) && $maxsizeW>0) {
				//$this->resizeImage($maxsizeW,$maxsizeH,$dir_afile.'/s_'.$ar_imginfo['ofname'],$dir_afile.'/'.$ar_imginfo['ofname']);
				//$ar_imginfo['sfname']='s_'.$ar_imginfo['ofname'];
				
			
				$ar_imginfo['sfname']='s_'.$ar_imginfo['ofname'];
				$param1 = array(
					'o_path' => $dir_afile.'/'.$ar_imginfo['ofname'], 'n_path' => $dir_afile.'/'.$ar_imginfo['sfname'],
					'mode' => 'ratio', 'fill_yn'=>'Y','width' => $maxsizeW, 'height' => $maxsizeH
				);

				$rtn = $this->getThumb($param1);
				//var_dump($rtn);
				
			}
	

		return $ar_imginfo;
	}

	/*
	Function	: getThumb($param)
	Param		: $param['o_path'] = 원본 파일 경로
				  $param['n_path'] = 새 파일 경로
				  $param['width'] = 썸네일 이미지 넓이
				  $param['height'] = 썸네일 이미지 높이
				  $param['mode'] = ratio or fixed	(ratio => 비율유지, fixed => 파라메터의 크기로 강제 변경)
				  $param['fill_yn'] = 'Y' or 'N'	(mode가 ratio일 경우 부족한부분 투명 배경처리)
				  $param['preview_yn'] = 'Y' or 'N' (미리보기 방지 여부 => 미리보기방지 대체 이미지 제공)
	Return		: array('bool' => true or false, 'src' => 썸네일 이미지 url, 'msg' => 성공, 실패 메세지)

	$param1 = array(
		'o_path' => $original, 'n_path' => './result/ratio_100x100.png',
		'mode' => 'ratio', 'width' => 100, 'height' => 100
	);
	*/
	function getThumb($param){
		if(empty($param['o_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
		if(empty($param['n_path']))		return array('bool' => false, 'msg' => '원본 파일 경로가 없습니다.');
		if(!in_array($param['mode'], array('ratio', 'fixed')))	$param['mode'] = 'ratio';
		if(empty($param['width']))		$param['width'] = 300;
		if(empty($param['height']))		$param['height'] = 300;
		if(!in_array($param['fill_yn'], array('Y', 'N')))		$param['fill_yn'] = 'N';
		if(!in_array($param['preview_yn'], array('Y', 'N')))	$param['preview_yn'] = 'Y';

		// 미리보기 방지 이미지 url
		if($param['preview_yn'] == 'N')	$param['o_path'] = './hidden.png';

		$src = array();
		$dst = array();

		$src['path'] = $param['o_path'];
		$dst['path'] = $param['n_path'];

		// 썸네일 이미지 갱신 기간 (1주일)
		if(file_exists($dst['path'])){
			if(mktime() - filemtime($dst['path']) < 60 * 60 * 24 * 7)	return array('bool' => true, 'src' => $dst['path']);
		}

		$imginfo = getimagesize($src['path']);
		$src['mime'] = $imginfo['mime'];

		// 원본 이미지 리소스 호출
		switch($src['mime']){
			case 'image/jpeg' :	$src['img'] = imagecreatefromjpeg($src['path']);	break;
			case 'image/gif' :	$src['img'] = imagecreatefromgif($src['path']);		break;
			case 'image/png' :	$src['img'] = imagecreatefrompng($src['path']);		break;
			case 'image/bmp' :	$src['img'] = imagecreatefrombmp($src['path']);		break;
			// mime 타입이 해당되지 않으면 return false
			default :		return array('bool' => false, 'msg' => '이미지 파일이 아닙니다.');						break;
		}

		// 원본 이미지 크기 / 좌표 초기값
		$src['w'] = $imginfo[0];
		$src['h'] = $imginfo[1];
		$src['x'] = 0;
		$src['y'] = 0;

		// 썸네일 이미지 좌표 초기값 설정
		$dst['x'] = 0;
		$dst['y'] = 0;

		// 썸네일 이미지 가로, 세로 비율 계산
		$dst['ratio']['w'] = $src['w'] / $param['width'];
		$dst['ratio']['h'] = $src['h'] / $param['height'];

		switch($param['mode']){
			case 'ratio' :
				// 썸네일 이미지의 비율계산 (가로 == 세로)
				if($dst['ratio']['w'] == $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = $param['height'];
				}
				// 썸네일 이미지의 비율계산 (가로 > 세로)
				elseif($dst['ratio']['w'] > $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = round(($param['width'] * $src['h']) / $src['w']);
				}
				// 썸네일 이미지의 비율계산 (가로 < 세로)
				elseif($dst['ratio']['w'] < $dst['ratio']['h']){
					$dst['w'] = round(($param['height'] * $src['w']) / $src['h']);
					$dst['h'] = $param['height'];
				}

				if($param['fill_yn'] == 'Y'){
					$dst['canvas']['w'] = $param['width'];
					$dst['canvas']['h'] = $param['height'];
					$dst['x'] = $param['width'] > $dst['w'] ? ($param['width'] - $dst['w']) / 2 : 0;
					$dst['y'] = $param['height'] > $dst['h'] ? ($param['height'] - $dst['h']) / 2 : 0;
				}
				else{
					$dst['canvas']['w'] = $dst['w'];
					$dst['canvas']['h'] = $dst['h'];
				}
				break;
			case 'fixed' :
				// 썸네일 이미지의 비율계산 (가로 == 세로)
				if($dst['ratio']['w'] == $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = $param['height'];
				}
				// 썸네일 이미지의 비율계산 (가로 > 세로)
				elseif($dst['ratio']['w'] > $dst['ratio']['h']){
					$dst['w'] = $src['w'] / $dst['ratio']['h'];
					$dst['h'] = $param['height'];

					$src['x'] = ($dst['w'] - $param['width']) / 2;
				}
				// 썸네일 이미지의 비율계산 (가로 < 세로)
				elseif($dst['ratio']['w'] < $dst['ratio']['h']){
					$dst['w'] = $param['width'];
					$dst['h'] = $src['h'] / $dst['ratio']['w'];

					$dst['y'] = 0;
				}
				$dst['canvas']['w'] = $param['width'];
				$dst['canvas']['h'] = $param['height'];
				break;
		}

		// 썸네일 이미지 리소스 생성
		$dst['img'] = imagecreatetruecolor($dst['canvas']['w'], $dst['canvas']['h']);

		// 배경색 처리
		if(in_array($src['mime'], array('image/png', 'image/gif'))){
			// 배경 투명 처리
			imagetruecolortopalette($dst['img'], false, 255);
			$bgcolor = imagecolorallocatealpha($dst['img'], 255, 255, 255, 127);
			imagefilledrectangle($dst['img'], 0, 0, $dst['canvas']['w'],$dst['canvas']['h'], $bgcolor);	
		}
		else{
			// 배경 흰색 처리
			$bgclear = imagecolorallocate($dst['img'],255,255,255);
			imagefill($dst['img'],0,0,$bgclear);
		}

		// 원본 이미지 썸네일 이미지 크기에 맞게 복사
		imagecopyresampled($dst['img'],$src['img'],$dst['x'],$dst['y'],$src['x'],$src['y'],$dst['w'],$dst['h'],$src['w'],$src['h']);

		// imagecopyresampled 함수 사용 불가시 사용
		//imagecopyresized($dst['img'],$src['img'],$dst['x'],$dst['y'],$src['x'],$src['y'],$dst['w'],$dst['h'],$src['w'],$src['h']);

		ImageInterlace($dst['img']);

		// 썸네일 이미지 리소스를 기반으로 실제 이미지 생성
		switch($src['mime']){
			case 'image/jpeg' :	imagejpeg($dst['img'], $dst['path']);	break;
			case 'image/gif' :	imagegif($dst['img'], $dst['path']);	break;
			case 'image/png' :	imagepng($dst['img'], $dst['path']);	break;
			case 'image/bmp' :	imagebmp($dst['img'], $dst['path']);	break;
		}

		// 원본 이미지 리소스 종료
		imagedestroy($src['img']);
		// 썸네일 이미지 리소스 종료
		imagedestroy($dst['img']);

		// 썸네일 파일경로 존재 여부 확인후 리턴
		return file_exists($dst['path']) ? array('bool' => true, 'src' => $dst['path']) : array('bool' => false, 'msg' => '파일 생성에 실패하였습니다.');
	}

	function Get_ThumName($orgName) {
		$arr = explode("/", $orgName);
		$thumb_name = $arr[count($arr) - 1];
		$thumb_name =  str_replace($thumb_name, "s_".$thumb_name, $orgName);
		if (is_file($_SERVER["DOCUMENT_ROOT"].$thumb_name)) {
			return $thumb_name;
		} else {
			return $orgName;
		}
	}

	function uploadFile($dir,$file,$name)
	{
		$file_tmp=$dir.'/'.$name;
		if($file['type']=='text/html' || $file['type']=='text/css' || $file['type']=='text/plain' || $file['type']=='application/octet-stream')
		{
			$this->altPopBack('Not allowed to upload file format');
		}
		else
		{
			if(move_uploaded_file($file[tmp_name],$file_tmp)) return true;
			else return false;
		}
	}

	function resizeImage($maxsizeW,$maxsizeH,$smallfile,$picture)
	{
		$picsize=@getimagesize($picture);
		if ($picsize) {
			if ($maxsizeW>$picsize[0] && $maxsizeH>$picsize[1]) {
				$new_w=$picsize[0];
				$new_h=$picsize[1];
			} else {
				$rateW=$picsize[0]/$maxsizeW;
				$rateH=$picsize[1]/$maxsizeH;

				if ($rateW>=$rateH) {
					$new_w=$maxsizeW;
					$new_h=($picsize[1]*$maxsizeW)/$picsize[0];
				} else {
					$new_w=($picsize[0]*$maxsizeH)/$picsize[1];
					$new_h=$maxsizeH;
				}
			}

			if(in_array('imagecreatetruecolor',get_extension_funcs('gd')))
			{
				$dstimg=imagecreatetruecolor($new_w,$new_h);
			} else {
				$dstimg=ImageCreate($new_w,$new_h);
			}

			switch($picsize[2])
			{
				case 1: // gif
					$srcimg=ImageCreateFromGIF($picture);
					if(in_array('imagecopyresampled',get_extension_funcs('gd')))
					{
						imagecopyresampled($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					} else {
						ImageCopyResized($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					}
					ImageGIF($dstimg,$smallfile,100);
				break;

				case 2: // jpg
					$srcimg=ImageCreateFromJPEG($picture);
					if(in_array('imagecopyresampled',get_extension_funcs('gd')))
					{
						imagecopyresampled($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					} else {
						ImageCopyResized($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					}
					ImageJPEG($dstimg,$smallfile,100);
				break;

				case 3: // png
					$srcimg=ImageCreateFromPNG($picture);
					if( in_array('imagecopyresampled',get_extension_funcs('gd')))
					{
						imagecopyresampled($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					} else {
						ImageCopyResized($dstimg,$srcimg,0,0,0,0,$new_w,$new_h,ImageSX($srcimg),ImageSY($srcimg));
					}
					ImagePNG($dstimg,$smallfile,100);
				break;
			}

			@ImageDestroy($dstimg);
			@ImageDestroy($srcimg);
		}
	}
	//======================= Function for file =============================

	//======================= Function for paging =============================
	function listPage($page,$pagemax,$listmax,$pgname='page')
	{
		if (!$page) $page=1;

		$icon['back_on']='<img src="./images/board_btn_pprev.gif" alt="첫번째페이지"/>';
		$icon['back_one']='<img src="./images/board_btn_prev.gif" alt="이전페이지"/>';
		$icon['next_on']='<img src="./images/board_btn_next.gif" alt="다음페이지"/>';
		$icon['next_one']='<img src="./images/board_btn_nnext.gif"  alt="마지막페이지"/>';

		if($this->total)
		{
			$pagemax_tmp1=$page / $pagemax;
			$pagemax_tmp2=$page % $pagemax;
			if($pagemax_tmp2==0) $pagemax_tmp1=$pagemax_tmp1-1;
			$pagemax_tmp1=(int)$pagemax_tmp1;
			$pagemax_prev=$pagemax_tmp1*$pagemax;
			$pagemax_next=$pagemax_prev+$pagemax+1;
			$pagemax_start=$pagemax_prev+1;
			$pagemax_end=$pagemax_next-1;

			$pagecount=$this->total/$listmax;
			if(!$pagecount) $pagecount=1;
			$pagecount2=(int)$pagecount;
			if($pagecount!=$pagecount2) $pagecount=$pagecount2+1;

			if($page>$pagemax) {
				$pages=1;
				$hrefurl=$_SESSION['URL_QUERY']['PAGING'].'&'.$pgname.'=1';
				$page_btn.='<a href="'.$hrefurl.'" class="btn_arr first">'.$icon['back_on'].'</a> ';
			}

			if($page>$pagemax) {
				$pages=$pagemax_prev;
				$hrefurl=$_SESSION['URL_QUERY']['PAGING'].'&'.$pgname.'='.$pages;
				$page_btn.='<a href="'.$hrefurl.'" class="btn_arr prev">'.$icon['back_one'].'</a> ';
			}
			
			//$page_btn.='<ul>';
			for($pages=$pagemax_start;$pages<=$pagemax_end;$pages++)
			{
				if($pages<$pagecount+1)
				{
					if($pages==$page)
					{
						$page_btn.=' <a class="on" href="#">'.$pages.'</a> ';
					}
					else
					{
						$hrefurl=$_SESSION['URL_QUERY']['PAGING'].'&'.$pgname.'='.$pages;
						$page_btn.=' <a href="'.$hrefurl.'">'.$pages.'</a> ';
					}
				}
			}
			//$page_btn.='</ul>';

			if($pagemax_next<=$pagecount) {
				$pages=$pagemax_next;
				$hrefurl=$_SESSION['URL_QUERY']['PAGING'].'&'.$pgname.'='.$pages;
				$page_btn.=' <a href="'.$hrefurl.'" class="btn_arr next">'.$icon['next_on'].'</a> ';
			}

			if($pagemax_next<=$pagecount) {
				$pages=$pagecount;
				$hrefurl=$_SESSION['URL_QUERY']['PAGING'].'&'.$pgname.'='.$pagecount;
				$page_btn.=' <a href="'.$hrefurl.'" class="btn_arr last">'.$icon['next_on'].'</a>';
			}

		}
		else
		{
			$page_btn='<a class="on" href="#">1</a>';
		}

		return $page_btn;
	}
	//======================= Function for paging =============================

	function listPaging($page,$pagemax,$listmax,$pgname='page')
	{
		if (!$page) $page=1;
		$intPage			= ( $page )			? $page: 1;			//현재페이지
		$intPageLine		= ( $listmax )		? $listmax: 10;		//한페이지당 row수
		$intPageBlock		= ( $pagemax )		? $pagemax: 10;		//블록수
		$intTotPage						= ceil( $this->total / $intPageLine );	//전체블록수

		$intTotBlock		= ceil($intTotPage / $intPageBlock);		// 전체 블럭 수
		$intBlock			= ceil($intPage / $intPageBlock);			// 현재 블럭
		$intPrevBlock		= (($intBlock - 2) * $intPageBlock) + 1;	// 이전 블럭
		$intNextBlock		= ($intBlock * $intPageBlock) + 1;			// 다음 블럭
		$intFirstBlock		= (($intBlock - 1) * $intPageBlock) + 1;	// 현재 블럭 시작 시저
		$intLastBlock		= $intBlock * $intPageBlock;				// 현재 블럭 종료 시점
		

		if($intFirstBlock <= 0) { $intFirstBlock	= 1; }
		if($intPrevBlock  <= 0) { $intPrevBlock	= 1; }
		if($intNextBlock >= $intTotPage) { $intNextBlock	= $intTotPage; }
		if($intLastBlock >= $intTotPage) { $intLastBlock	= $intTotPage; }
		if($intLastBlock <= 0) $intLastBlock = 1;

		$page_rtn				= "";
		$page_rtn['first']		= 1;
		$page_rtn['fblock']		= $intFirstBlock;
		$page_rtn['prev']		= $intPrevBlock;
		$page_rtn['next']		= $intNextBlock;
		$page_rtn['lblock']		= $intLastBlock;
		$page_rtn['last']		= $intTotPage;
		$page_rtn['listcntnum']	= $this->total - ( $intPageLine * ( $page - 1 ) );	
		

		

		return $page_rtn;
	}

	//======================= Function for value act =============================
	function getZipQry($kword)
	{
		if (strrpos($kword,"길")>0) {
			$kw1=substr($kword,0,(strrpos($kword,"길")+3));
			$kw2=substr($kword,(strrpos($kword,"길")+4));
			if ($kw2) {
				if (strrpos($kw2,"-")>0) {
					$ar_kw=explode('-',$kw2);
					$list_qry.="and a2.a2_bnum='".$ar_kw[0]."' and a2.a2_bsnum='".$ar_kw[1]."' ";
				} else {
					$list_qry.="and a2.a2_bnum='".$kw2."' ";
				}
			}
			$list_qry.="and a1.r_name='".str_replace(' ','',$kw1)."' ";
		} else if (strrpos($kword,"로")>0) {
			$kw1=substr($kword,0,(strrpos($kword,"로")+3));
			$kw2=substr($kword,(strrpos($kword,"로")+4));
			if ($kw2) {
				if (strrpos($kw2,"-")>0) {
					$ar_kw=explode('-',$kw2);
					$list_qry.="and a2.a2_bnum='".$ar_kw[0]."' and a2.a2_bsnum='".$ar_kw[1]."' ";
				} else {
					$list_qry.="and a2.a2_bnum='".$kw2."' ";
				}
			}
			$list_qry.="and a1.r_name='".str_replace(' ','',$kw1)."' ";
		} else {
			$list_qry="and a1.r_name='".str_replace(' ','',$kword)."' ";
		}

		return $list_qry;
	}
	function getParam($name)
	{
		if (isset($_POST[$name]) || $_POST[$name]!='' || $_POST[$name]=='0') {
			return $_POST[$name];
		} else {
			if(!isset($_GET[$name]) || $_GET[$name]=='') return false;
			else return $_GET[$name];
		}
	}

	function Str_cut($str, $size, $ex = "...")	//utf-8
	{
		$substr = substr($str, 0, $size*2);
		$multi_size = preg_match_all('/[\x80-\xff]/', $substr, $multi_chars);

		if($multi_size >0)
			$size = $size + intval($multi_size/3)-1;

		if(strlen($str)>$size)
		{
			$str = substr($str, 0, $size);
			$str = preg_replace('/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str);
			$str .= $ex;
		}

		return $str;
	}

	function conv_num($num) {
		if ($num=='0') {
			return '0';
		} else {
			$number = (int)str_replace(',', '', $num);
			return $number;
		}
	}

	function CulNumber($Tval,$Cgrd,$Ckind)
	{
		$Rval=0;

		if ($Tval>0)
		{
			switch ($Ckind)
			{
			case '1':	//올림
				$Rval=ceil(($Tval/$Cgrd))*$Cgrd;
			break;
			case '2':	//반올림
				$Rval=round(($Tval/$Cgrd))*$Cgrd;
			break;
			case '3':	//버림
				$Rval=floor(($Tval/$Cgrd))*$Cgrd;
			break;
			}
		}

		return $Rval;
	}

	function SetBstr($ar_str,$Cdel="|")
	{
		if (is_array($ar_str)) {
			foreach($ar_str as $k => $v)
			{
				if ($u_option) $u_option.=$Cdel.$v;
				else $u_option=$v;
			}
		} else {
			$u_option=' ';
		}

		return $u_option;
	}

	function htol($ipaddr) { 
		$b = explode('.',$ipaddr); 
		if (sizeof($b) == 4) { 
			$c = ($b[0]*256*256*256) + ($b[1]*256*256) + ($b[2]*256) + ($b[3]*1); 
			return $c; 
		} else return; 
	}

	function sql_password($value)
	{    
		$result = mysql_query(" select password('$value') as pass ");
		$row = mysqli_fetch_array($result);
		return $row[pass];
	}

	function Send_Mail($subject,$toName,$toEmail,$content)
	{
		$charset				= 'UTF-8';																		// 문자셋 : UTF-8
		$fromName				= $this->WEBCNF['TITLE'];														// 보내는이 이름
		$fromEmail				= $this->WEBCNF['email'];														// 보내는이 이메일주소
		$body					= $content;																		// 메일내용
		$encoded_subject		= "=?".$charset."?B?".base64_encode($subject)."?=\n";							// 인코딩된 제목
		$to						= "\"=?".$charset."?B?".base64_encode($toName)."?=\" <".$toEmail.">" ;			// 인코딩된 받는이
		$from					= "\"=?".$charset."?B?".base64_encode($fromName)."?=\" <".$fromEmail.">" ;		// 인코딩된 보내는이
		$headers				= "MIME-Version: 1.0\n".
								"Content-Type: text/html; charset=".$charset."; format=flowed\n".
								//"To: ". $to ."\n".
								"From: ".$from."\n".
								"Return-Path: ".$from."\n";
		//$mail					= mail ( $to , $encoded_subject , $body , $headers );							// 메일 보내기		
		$mail					= mail ( $toEmail , $encoded_subject , $body , $headers );							// 메일 보내기		
	}
	//======================= Function for value act =============================

	//======================= Function for page act =============================
	function altPopBack($msg)
	{
		$result='
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<script language="javascript">
			<!--
				window.alert(\''.$msg.'\');
				history.back(-1);
			//-->
			</script>
		';

		echo $result;
		exit;
	}

	function altPopNext($msg,$url)
	{
		$result='
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
			<script language="javascript">
			<!--
				window.alert(\''.$msg.'\');
				location.href="'.$url.'";
			//-->
			</script>
		';

		echo $result;
		exit;
	}

	function pageNext($url,$opt=0)
	{
		if($opt==1)
		{
			$result='<META HTTP-EQUIV="REFRESH" CONTENT="0;URL='.$url.'">';
			echo $result;
		}
		else
		{
			header('Location: '.$url);
		}

		exit;
	}
	//======================= Function for page act =============================

	//======================= Function for DB =============================
	// Connect
	function dbCon()
	{
		$this->conn = new mysqli($this->DBINFO['host'],$this->DBINFO['user'], $this->DBINFO['pass'], $this->DBINFO['name']);

		if($this->conn->connect_errno){
			echo '[fail] : '.$this->conn->connect_error.'<br>'; 
			die();
		} 
		$this->conn->set_charset('utf8');
	}

	// Select, Insert, Update, Delete
	function dbQuery($que,$err=null)
	{	
		if ($_SESSION['view_query'] =="YES") echo "<div>$que</div>";
		//$rlt=mysql_query($que);
		$rlt= $this->conn->query($que); 

		if(!$rlt)
		{
			if(!$err) $err=$this->conn->error;
			$this->writeLog($que);
			echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8">ERROR : '.$err;
			//$this->altPopBack("정상처리가 불가합니다.");

			exit;
		}

		return $rlt;
	}

	function writeLog($que)
	{	
		## 기본 설정
		$strNow = date("Ymd");
		$strLogDir =$this->WEBDIR['web_root'].$this->WEBDIR['fdata']."/log";

		$strLogFile = $strLogDir."/{$strNow}.log";

	
		if(!is_dir($strLogDir)) mkdir($strLogDir,0777);


		$logFileContent	 = "";		
		$logFileContent	.= "[query]:".$que;
		$logFileContent	.= "\n[error]:".$this->conn->error;
		$logFileContent	.= "\n[ip]:".$_SERVER["REMOTE_ADDR"];
		$logFileContent	.= "\n[url]:".$_SERVER['REQUEST_URI'];




		
		if(!file_exists($strLogFile)) {
			$fp = fopen($strLogFile,"w");
		} else {
			$fp = fopen($strLogFile,"a");
		}

		flock( $fp, LOCK_EX );

		fwrite($fp,"#".date("Y-m-d H:i:s")."------------------------------------\n");
		fwrite($fp,$logFileContent);
		fwrite($fp,"\n\n\n");

		flock( $fp, LOCK_UN );
		fclose($fp);

		@chmod( $strLogFile , 0707 );
		
	}

	function removeHackTag($content) 
    {
		 /*
		$removeJSEvent = function ($matches) {
			$tag = strtolower($matches[1]);
			if(preg_match('/(src|href)=("|\'?)javascript:/i',$matches[2])) $matches[0] = preg_replace('/(src|href)=("|\'?)javascript:/i','$1=$2_javascript:', $matches[0]);
			return preg_replace('/ on([a-z]+)=/i',' _on$1=',$matches[0]);
		};
		*/


        // iframe 제거
        //$content = preg_replace("!<iframe(.*?)<\/iframe>!is", '', $content);

        // script code 제거
        $content = preg_replace("!<script(.*?)<\/script>!is", '', $content);

        // meta 태그 제거
        $content = preg_replace("!<meta(.*?)>!is", '', $content);

        // style 태그 제거
        $content = preg_replace("!<style(.*?)<\/style>!is", '', $content);

        // XSS 사용을 위한 이벤트 제거
        $content = preg_replace_callback("!<([a-z]+)(.*?)>!is", $removeJSEvent, $content);

        
        return $content;
    }
	
	//값 존재여부체크
	function checkAry($params, $val) {
		$rtn = "";
		foreach ($val as $k => $v) {
			if (!isset($params[$k]) || !trim($params[$k])) {
				$rtn = $v;
				break;
			}
		}
		return $rtn;
	}
   

	// Close
	function dbClose()
	{
		if($this->conn) $rlt=$this->conn->close() or die($this->conn->connect_error);

		return $rlt;
	}
	//======================= Function for DB =============================
}
?>
