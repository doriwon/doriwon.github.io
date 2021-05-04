<?php

//load.php

$connect = new PDO('mysql:host=localhost;dbname=bcjahwal02', 'bcjahwal02', 'abab550725');

$data = array();

$query = "SELECT * FROM tb_board_cal ORDER BY idx";

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

foreach($result as $row)
{
 $data[] = array(
  'id'   => $row["idx"],
  'title'   => $row["title"],
  'start'   => date("Y-m-d", strtotime($row["start_ymd"])),
  'end'   => date("Y-m-d", strtotime($row["end_ymd"]))
 );
}

echo json_encode($data);

?>