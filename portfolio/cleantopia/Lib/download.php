<?php
ini_set("memory_limit", "100M");
ini_set ("display_errors", "1");


$filename = trim($_GET['fname']);
$filedir = trim($_GET['fdir']);
$name = trim($_GET['name']);

if (strpos($filename,"./") !== false) {
?>
	<meta charset='utf-8'>
	<script>alert('비정상적인 접근입니다.');</script>
<?php
	exit;
} 

if (strpos($filedir,"./") !== false) {
?>
	<meta charset='utf-8'>
	<script>alert('비정상적인 접근입니다.');</script>
<?php
	exit;
} 



$filename = iconv("UTF-8", "euc-kr", $filename); // 한글깨짐현상 올릴때와 동일하게 마춰준다.
$name = iconv("UTF-8", "euc-kr", $name); // 한글깨짐현상 올릴때와 동일하게 마춰준다.
if (!$filedir || !$filename) {
	exit;
}

$filedir = $_SERVER['DOCUMENT_ROOT']."/FileData/{$filedir}/";
$filepath = $filedir.$filename;        //상대경로    


$filesize = filesize($filepath);
$path_parts = pathinfo($filepath);
$filename = $path_parts['basename'];
$extension = $path_parts['extension'];

 if( !$name ) $name = $filename;

header("Pragma: public");
header("Expires: 0");
header("Content-Type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"$name\"");
header("Content-Transfer-Encoding: binary");
header("Content-Length: $filesize");
 
ob_clean();
flush();
readfile($filepath);