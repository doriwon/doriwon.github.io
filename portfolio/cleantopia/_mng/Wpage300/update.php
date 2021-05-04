<?
//update.php

$connect = new PDO('mysql:host=localhost;dbname=bcjahwal02', 'bcjahwal02', 'abab550725');

if(isset($_POST["idx"]))
{
 $query = "
 UPDATE tb_board_cal 
 SET title=:title, start_ymd=:start_ymd, end_ymd=:end_ymd 
 WHERE idx=:idx
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_ymd' => $_POST['start'],
   ':end_ymd' => $_POST['end'],
   ':idx'   => $_POST['idx']
  )
 );

}?>