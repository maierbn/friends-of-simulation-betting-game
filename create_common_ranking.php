<?
  include("connect.php");
  
  $query = "SELECT nation_id,name,SUM(rank) FROM `ranking_user` JOIN nation ON (nation.id = nation_id) WHERE user_id != 4 && user_id != 28 && user_id != 39 GROUP BY nation_id ORDER BY SUM(rank),name";
  $result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
  
  $query = "TRUNCATE TABLE ranking_common; INSERT INTO ranking_common (nation_id, rank, value) VALUES ";
  
  $msg = "";
  for($i = 0; $line = $result->fetch_assoc(); $i++)
  {
    if ($i != 0)
      $query .= ", ";
    $value = (32-$i);
    $query .= "(".$line["nation_id"].", $i, ".$value.")";
    $msg .= $i.". ".$line["name"]." ($value)<br>";
  }
  $result = $mysqli->multi_query($query) or die("error in query [$query]: ".$mysqli->error);
  
  echo "create ranking_common:<br><br>".$msg;
  echo $query;
?>
