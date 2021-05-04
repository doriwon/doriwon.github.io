<?

$SysLib->dbCon();

$WEBFILE['LEFT']="/left_static.html";
$WEBFILE['BODY']='/'.$WEBDIR['MKEY'].$_GET['WS'].'.html';

//파라미터 모두 받기
$params=$SysLib->getParameterToArray();

$sel_date	=$params['sel_date'];

if(!$sel_date) $sel_date = date("Ym");

$query = "select substring(c_date, 1, 6) sel_date from tb_count group by substring(c_date, 1, 6) order by	substring(c_date, 1, 6) desc";
$SelList = $SysLib->Get_List_All($query);


$query = "select c_date, max(pc) h_sum, max(mobile) m_sum, ifnull(max(pc) ,0)+ifnull(max(mobile) ,0) as sumhap ";
$query .=	" FROM ( ";
$query .=	"   select c_date ";
$query .=	"   , case gu when 'pc' then cnt else '0' end as 'pc' ";
$query .=	"   , case gu when 'mobile' then cnt else '0' end as 'mobile'    ";
$query .=	"   FROM ( ";
$query .=	"      SELECT  ";
$query .=	"      c_date, gu , cnt ";
$query .=	"      FROM (select c_date, gu, sum(c_cnt) cnt from tb_count where c_date like '".$sel_date."%' group by c_date, gu)c ";
$query .=	"   ) A ";
$query .=	" ) B ";
$query .=	" group by c_date  order by c_date desc";
$VisitList = $SysLib->Get_List_All($query);

$query = " select max(sumhap) from ( ";
$query .= "select c_date, max(pc) h_sum, max(mobile) m_sum, ifnull(max(pc) ,0)+ifnull(max(mobile) ,0) as sumhap ";
$query .=	" FROM ( ";
$query .=	"   select c_date ";
$query .=	"   , case gu when 'pc' then cnt else '0' end as 'pc' ";
$query .=	"   , case gu when 'mobile' then cnt else '0' end as 'mobile'    ";
$query .=	"   FROM ( ";
$query .=	"      SELECT  ";
$query .=	"      c_date, gu , cnt ";
$query .=	"      FROM (select c_date, gu, sum(c_cnt) cnt from tb_count where c_date like '".$sel_date."%' group by c_date, gu)c ";
$query .=	"   ) A ";
$query .=	" ) B ";
$query .=	" group by c_date ";
$query .= ") tb";

$maxVisit = $SysLib->Get_Content($query, 1); 	


// ALL - end //

$SysLib->dbClose();
?>