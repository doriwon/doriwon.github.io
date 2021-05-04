<?
//insert.php

$connect = new PDO('mysql:host=localhost;dbname=bcjahwal02', 'bcjahwal02', 'abab550725');

if(isset($_POST["title"]))
{
 $query = "
 INSERT INTO tb_board_cal 
 (title, start_ymd, end_ymd) 
 VALUES (:title, :start_ymd, :end_ymd)
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':title'  => $_POST['title'],
   ':start_ymd' => $_POST['start_ymd'],
   ':end_ymd' => $_POST['end_ymd']
  )
 );
}
echo var_dump($_POST['title']);
?>