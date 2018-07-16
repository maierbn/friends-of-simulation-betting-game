

<h1>Highscore</h1>

<?
include("connect.php");

$query = 'SELECT * FROM user ORDER BY displayed_name';
$result = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
$n_users = $result->num_rows;

// if there was no match in the database
if ($result->num_rows == 0)
{
  echo "None.";
}
else 
{
?>
    
<?    
  $user_strings = array();

  // loop over users
  while($line = $result->fetch_assoc())
  {
    // compute score for user
    $user_score = 0;
    include_once("compute_current_ranking.php");


    $query = 'SELECT * FROM user_holds_nation WHERE user_id="'.$line["id"].'" ORDER BY time_from';
    $result2 = $mysqli->query($query) or die("error in query [$query]: ".$mysqli->error);
    $user_info = "";

    // loop over holding nations
    $user_n_nations = 0;
    $user_nations_sum = 0;
    while($line2 = $result2->fetch_assoc())
    {
      compute_current_ranking($part_ranking_current, $part_ranking_current_pos, $line2["nation_id"], $line2["time_from"], $line2["time_to"]);
     
      //echo "<tr><td colspan=3>nation_id=".$line2["nation_id"].", time_from=".$line2["time_from"].", time_to=".$line2["time_to"]."<br><pre>";
      //print_r($part_ranking_current);
      //echo "</pre></td></tr>";
       
      $line3 = $part_ranking_current[0];
      $nation_score = $line3["score"];
      $payed_cost = $line2["payed"];
      
      $user_score += $nation_score;
      $user_score -= $payed_cost;
      
      $user_info .= "<p>".date("d/m",strtotime($line2["time_from"]));
      if (strtotime($line2["time_to"]) < time())
      {
        $user_info .= " - ".date("d/m",strtotime($line2["time_to"]));
      }
      $nation_value = $ranking_common[$ranking_common_pos[$line3["id"]]-1]["value"];
      $user_nations_sum += $nation_value;
      $user_info .= ": ".$line3["name"]." <img src=\"/images/".$line3["image"]."\" class=\"nation\"/>(".$nation_value.")";
      if ($payed_cost)
        $user_info .= " (costs ".$payed_cost.")";
      $user_info .= "</p>";
      
      if ($user_n_nations == 3)
      {
        $user_info .= "Sum: $user_nations_sum";
        if ($line["excuse"] != "")
        {
          $user_info .= "<br>Excuse: ".$line["excuse"];
        }
        if ($line["id"] == 33)
        {
          $user_info .= "<br><span style='color:orange'>Note: Integer overflow occurs at 9223372036854775807 (8 Byte Integers ;-) )</span>";
        }
      }
      $user_n_nations++;
    }
    
    if ($line["id"] == $_SESSION["user_id"])
    {
      $own_score = $user_score;
    }
    
    ob_start();
?>
      <td class="users-table"><?=$line["displayed_name"]?></td>
      <td class="users-table"><?=$user_score?></td>
      <td class="users-table">
        <a href="#" id="user-info-button-<?=$line["id"]?>" class="nation ui-btn ui-btn-inline ui-icon-info ui-shadow ui-btn-icon-notext ui-corner-all" 
          onclick="$('#user-info-<?=$line["id"]?>').show();$('#user-info-button-<?=$line["id"]?>').hide();"></a>
      </td>
    </tr>
    <tr style="display:none" id="user-info-<?=$line["id"]?>" >
      <td class="users-table user-info" colspan="4">
        <?=$user_info?>
        <a href="#" class="nation ui-btn ui-btn-inline ui-icon-delete ui-shadow ui-btn-icon-notext ui-corner-all" 
          onclick="$('#user-info-<?=$line["id"]?>').hide();$('#user-info-button-<?=$line["id"]?>').show();"></a>
      </td>
    </tr>
<?
  $user_string = ob_get_contents();

  $user_strings[] = array("string"=>$user_string, "key"=>$user_score, "user_id"=>$line["id"]);

  ob_end_clean();
  
  } 
  
function cmp($a, $b)
{
  if ($a["key"] == $b["key"])
  {
    return 0;
  }
  return ($a["key"] < $b["key"]) ? 1 : -1;
}
  uasort($user_strings, 'cmp');
  
?>
  
  <table class="users-table">
    <tr class="users-table">
      <th class="users-table">Rank</th>
      <th class="users-table">Name</th>
      <th class="users-table">Score</th>
    </tr>
    <?
    $rank = 0;
    $counter = 0;
    $last_score = -1;
    foreach ($user_strings as $value)
    {
      $counter++;
      if ($value["key"] != $last_score)
        $rank = $counter;
      $last_score = $value["key"];
      
      if ($value["user_id"] == $_SESSION["user_id"])
      {
        $own_rank = $rank;
      }
?>      
    <tr class="users-table">
      <td class="users-table"><?=$rank?></td>
<?
      echo $value["string"];
    }?>
  </table>
<?  
}
?>

