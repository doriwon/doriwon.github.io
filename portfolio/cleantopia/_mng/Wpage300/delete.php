<?
//delete.php

if(isset($_POST["idx"]))
{
 $connect = new PDO('mysql:host=localhost;dbname=bcjahwal02', 'bcjahwal02', 'abab550725');
 $query = "
 DELETE from tb_board_cal WHERE idx=:idx
 ";
 $statement = $connect->prepare($query);
 $statement->execute(
  array(
   ':idx' => $_POST['idx']
  )
 );
}
?>